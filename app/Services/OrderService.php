<?php
namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use function Pest\Laravel\json;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderService
{
    protected $orderRepository;

    protected $productRepository;

    protected $paymentService;

    public function __construct(OrderRepositoryInterface $orderRepository, ProductRepositoryInterface $productRepository, PaymentService $paymentService)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->paymentService = $paymentService;
    }

    public function create($data = [])
    {

        $itemOrder = $data['items'];

        $productIds = array_column($itemOrder, 'product_id');

        $products = $this->productRepository->getProductInArrId($productIds);

        $user = Auth::user();

        $shipping_address = $data['shipping_address'];

        $apiUrl = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee';

        $totalShippingFee = 0; // phí ship

        $totalWeight = 0; // tổng cân nặng

        $totalLength = 0; // tổng chiều dài

        $totalWidth = 0; // tổng chiều rộng

        $totalHeight = 0; // tổng chiều cao

        $totalQuantity = 0; // tổng số lượng

        $totalPrice = 0; // tổng giá cac sản phẩm

        $totalDiscount = 0; // tổng giảm giá

        foreach ($itemOrder as $index => $item) {
            $totalQuantity += $item['quantity'];

            $product = $products->where('id', $item['product_id'])->load('discountTiers')->first();

            $discountTiers = $product->discountTiers ? $product->discountTiers->toArray() : [];

            usort($discountTiers, function ($a, $b) {
                return $b['minimum_quantity'] <=> $a['minimum_quantity']; // Sắp xếp giảm dần theo số lượng tối thiểu
            });

            if ($user->role->name == 'company') {
                $tierFound = collect($discountTiers)->first(function ($tier) use ($item) {
                    return $item['quantity'] >= $tier['minimum_quantity'];
                });

                if ($tierFound) {

                    $itemOrder[$index]['discount'] = 0;
                    $itemOrder[$index]['price'] = $tierFound['price'];

                    $totalDiscount += 0;
                    $totalPrice += $tierFound['price'] * $item['quantity'];
                } else {
                    // tính tổng giảm giá
                    $totalDiscount += ($product->price * $product->discount / 100) * $item['quantity'];
                    $totalPrice += ($product->price * (100 - $product->discount) / 100) * $item['quantity'];
                }

            } else {
                // tính tổng giá sản phẩm sau khi giảm giá
                $totalPrice += ($product->price * (100 - $product->discount) / 100) * $item['quantity'];
                // tính tổng giảm giá
                $totalDiscount += ($product->price * $product->discount / 100) * $item['quantity'];
            }
        }

        if ($totalQuantity < 10) {
            foreach ($itemOrder as $item) {
                $product = $products->where('id', $item['product_id'])->first();

                $totalWeight += $product->weight * $item['quantity'];

                $totalHeight += $product->height * $item['quantity'];
            }

            // Convert mảng
            $productsArray = $products->toArray();

            // Lấy chiều dài lớn nhất
            $totalLength = max(array_map(function ($item) {
                return $item['dimension_length'];
            }, $productsArray));

            // Lấy chiều rộng lớn nhất
            $totalWidth = max(array_map(function ($item) {
                return $item['dimension_width'];
            }, $productsArray));

            $postData = [
                'shop_id' => (int) config('app.ghn_shop_id'),
                'service_id' => 53321,
                'to_district_id' => $shipping_address['district']['DistrictID'],
                'to_ward_code' => $shipping_address['ward']['WardCode'],
                'service_type_id' => 2,
                'weight' => ceil($totalWeight),
                'length' => ceil($totalLength),
                'width' => ceil($totalWidth),
                'height' => ceil($totalHeight),
            ];

            $headerData = [
                'Content-Type' => 'application/json',
                'token' => config('app.ghn_token'),
            ];

            // return $postData;

            $response = Http::withHeaders($headerData)->post($apiUrl, $postData);

            $statusResponse = $response->status();

            if ($statusResponse == 200) {
                $totalShippingFee = $response->json()['data']['total'];
                $totalShippingFee = ceil($totalShippingFee / 1000) * 1000;
            }

        }

        $finalPrice = $totalPrice + $totalShippingFee;

        unset($shipping_address['id'], $shipping_address['user_id']);

        $dataCreateOrder = [
            'user_id' => $user->id,
            'total_amount' => $totalPrice,
            'shipping_fee' => $totalShippingFee,
            'discount_amount' => $totalDiscount,
            'final_amount' => $finalPrice,
            'shipping_address' => json_encode($shipping_address),
            'payment_method' => $data['payment_method'],
            'note' => $data['note'],
        ];

        if ($data['shipping_address'] == null) {

        }

        if ($data['payment_method'] == PaymentMethod::BANK_TRANSFER) {
            $dataCreateOrder['status'] = OrderStatus::NOT_PAID;
        }

        DB::beginTransaction();

        try {
            $order = $this->orderRepository->create($dataCreateOrder);

            $order->orderItems()->createMany($itemOrder);

            DB::commit();

            if ($data['payment_method'] == PaymentMethod::BANK_TRANSFER) {
                $url = $this->paymentService->createPaymentUrl([
                    'order_id' => $order->id,
                    'order_info' => 'Thanh toán đơn hàng',
                    'amount' => $finalPrice,
                    'vnp_ReturnUrl' => config('app.url') . '/api/order/' . $order->id . '/payment-return',
                ]);
                return $url;
            } else {
                return $order;

            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateStatusAfterPayment($id, $data)
    {
        $checkPayment = $this->paymentService->checkPayment($data);

        if ($checkPayment) {

            $dataUpdate = [
                'status' => OrderStatus::PENDING,
                'payment_date' => $data['vnp_PayDate'], // ngày thanh toán
                'transaction_id' => $data['vnp_TransactionNo'], // mã giao dịch
                'ref_id' => $data['vnp_TxnRef'], // mã đơn hàng
            ];

            $order = $this->orderRepository->find($id);

            $dataPayment = [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'amount' => $data['vnp_Amount'] / 100,
                'transaction_type' => PaymentType::DEPOSIT,
            ];

            $this->paymentService->create($dataPayment);

            $this->orderRepository->update($data['vnp_TxnRef'], $dataUpdate);

            return redirect()->away(config('app.frontend_url') . '/checkout?status=success');

        }

        return redirect()->away(config('app.frontend_url') . '/checkout?status=fail');

    }

    public function getAll($paginate = null, $with = [], $filter = null, $sort = null)
    {
        return $this->orderRepository->getAll($paginate, $with, $filter, $sort);
    }

    public function show($id, $with = [])
    {
        return $this->orderRepository->show($id, $with);
    }

    public function update($id, $data)
    {
        $status = $data['status'];

        if ($status == OrderStatus::CANCELLED) {
            $order = $this->orderRepository->find($id);

            if ($order->payment_method == PaymentMethod::BANK_TRANSFER) {
                if ($order->transaction_id != null && $order->ref_id != null) {
                    $refund = $this->paymentService->refund($order);
                    if ($refund) {
                        $data['status'] = OrderStatus::CANCELLED;
                        $data['ref_id'] = null;
                        $data['transaction_id'] = null;
                        $data['payment_date'] = null;

                        $dataPayment = [
                            'order_id' => $order->id,
                            'user_id' => $order->user_id,
                            'amount' => $order->final_amount,
                            'transaction_type' => PaymentType::REFUND,
                        ];

                        $this->paymentService->create($dataPayment);
                        return $this->orderRepository->update($id, $data);

                    } else {
                        return false;
                    }
                }
            }
        }

        $oldStatus = $this->orderRepository->find($id)->status;

        $update = $this->orderRepository->update($id, $data);
        if ($update) {
            if ($oldStatus != $status) {
                $order = $this->orderRepository->find($id);
                $order = $order->load(['orderItems','orderItems.product']);
                $user = $this->orderRepository->find($id)->user;
                $this->orderRepository->sendMailOrderStatus($order, $user);
            }
            return $update;
        }
        return false;
    }

    public function myOrder($paginate = null, $with = [], $filter = null, $sort = null)
    {
        return $this->orderRepository->myOrder($paginate, $with, $filter, $sort);
    }

    public function cancelOrder($id, $status = null){
        $user = Auth::user();
        $order = $this->orderRepository->find($id);

        if (!$status || !in_array($order->status, [OrderStatus::PENDING, OrderStatus::REQUEST_CANCEL])) {
            throw new \Exception('Trạng thái hủy đơn hàng không hợp lệ');
        }

        if($order->user_id != $user->id){
            throw new \Exception('Bạn không có quyền hủy đơn hàng này');
        }

        if($order->status == OrderStatus::CANCELLED){
            throw new \Exception('Đơn hàng đã được hủy');
        }

        $result = $this->orderRepository->update($id, ['status' => $status]);

        if($result){
            return true;
        }

        return false;
    }
}
