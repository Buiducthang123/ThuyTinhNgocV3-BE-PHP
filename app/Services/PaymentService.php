<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\Payment\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    //create payment url
    public function createPaymentUrl($data)
    {
        $order_id = $data['order_id'];

        $amount = ceil($data['amount']) ?? 0;

        $vnp_TmnCode = config('app.VnPay_tmncode'); // Mã website tại VNPAY

        $vnp_HashSecret = config('app.VnPay_hash_secret'); // Chuỗi bí mật

        $vnp_Url = config('app.VnPay_url'); // URL thanh toán

        $vnp_ReturnUrl = $data['vnp_ReturnUrl'] ?? null;

        if ($vnp_ReturnUrl == null) {
            throw new \Exception('Vui lòng cung cấp URL trả về sau khi thanh toán');
        }

        $vnp_TxnRef = $order_id; // Mã đơn hàng

        $vnp_OrderInfo = $data['order_info']; // Thông tin đơn hàng

        $vnp_Amount = $amount * 100; // Số tiền thanh toán (nhân với 100 để chuyển sang đơn vị VNĐ)

        $vnp_Locale = 'vn';

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
        ];

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    public function checkPayment($data)
    {
        $vnp_HashSecret = config('app.VnPay_hash_secret'); // Chuỗi bí mật

        $vnp_SecureHash = $data['vnp_SecureHash'];

        $inputData = array();
        foreach ($data as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash && $data['vnp_ResponseCode'] == '00') {
            return true;
        }
        return false;
    }

    public function refund(Order $order)
    {
        $user = Auth::user();

        $vnp_TmnCode = config('app.VnPay_tmncode'); // Mã website tại VNPAY

        $vnp_HashSecret = config('app.VnPay_hash_secret'); // Chuỗi bí mật

        $vnp_ApiUrl = 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction';

        $vnp_RequestId = rand(1, 10000); // ID yêu cầu

        $vnp_Command = 'refund'; // API command

        $vnp_TransactionType = '03'; // Loại giao dịch (03 là hoàn tiền)

        $vnp_TxnRef = $order->ref_id; // Mã giao dịch cần hoàn tiền

        // $vnp_Amount = $order->final_amount * 100; // Số tiền cần hoàn

        $vnp_Amount = intval($order->final_amount) * 100 - 1; // Số tiền cần hoàn

        $vnp_OrderInfo = 'Hoàn tiền giao dịch ' . $vnp_TxnRef;

        $vnp_CreateDate = now()->format('YmdHis');

        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $vnp_TransactionDate = $order->payment_date;

        $inputData = [
            "vnp_RequestId" => $vnp_RequestId,
            "vnp_Version" => "2.1.0",
            "vnp_Command" => $vnp_Command,
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_TransactionType" => $vnp_TransactionType,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_Amount" => $vnp_Amount,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_TransactionDate" => $vnp_TransactionDate,
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CreateBy" => $user->id
        ];

        // Tạo chuỗi dữ liệu để tạo checksum
        $hashData = implode('|', [
            $inputData['vnp_RequestId'],
            $inputData['vnp_Version'],
            $inputData['vnp_Command'],
            $inputData['vnp_TmnCode'],
            $inputData['vnp_TransactionType'],
            $inputData['vnp_TxnRef'],
            $inputData['vnp_Amount'],
            '', // vnp_TransactionNo (optional)
            $inputData['vnp_TransactionDate'],
            $inputData['vnp_CreateBy'],
            $inputData['vnp_CreateDate'],
            $inputData['vnp_IpAddr'],
            $inputData['vnp_OrderInfo'],
        ]);

        // Tạo SecureHash với SHA512 và khóa bí mật
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Thêm chữ ký bảo mật vào inputData
        $inputData['vnp_SecureHash'] = $secureHash;
        // Gửi yêu cầu tới VNPay
        $response = Http::post($vnp_ApiUrl, $inputData);

        if ($response->successful()) {
            logger('Refund response', $response->json());
            logger('Refund request', $inputData);
            $responseData = $response->json();
            $vnp_ResponseCode = $responseData['vnp_ResponseCode'] ?? null;
            $vnp_Message = $responseData['vnp_Message'] ?? null;

            if ($vnp_ResponseCode === '00') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //
    public function create($data = [])
    {
        $result = $this->paymentRepository->create($data);
        if ($result) {
            return $result;
        }
        return false;
    }

    public function getAll($paginate = null, $with = [], $filter = null, $sort = null)
    {
        $result = $this->paymentRepository->getAll($paginate, $with, $filter, $sort);
        return $result;
    }

    public function getMyPayment($paginate = null, $with = [], $filter = null, $sort = null)
    {
        $user_id = Auth::id();

        if (!$user_id) {
            throw new \Exception('Vui lòng đăng nhập');
        }

        $result = $this->paymentRepository->getMyPayment($paginate, $with, $filter, $sort, $user_id);
        return $result;
    }

}
