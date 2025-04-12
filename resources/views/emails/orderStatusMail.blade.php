<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trạng thái đơn hàng</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333333;
        }

        .email-wrapper {
            background-color: #f5f5f5;
            padding: 40px 20px;
        }

        .email-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #0066FF;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 24px;
        }

        .content {
            padding: 40px 50px;
            background: #ffffff;
        }

        .order-info {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .order-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .status {
            font-size: 18px;
            font-weight: bold;
            color: #4caf50;
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777;
            background-color: #f7f7f7;
        }

        .button {
            display: inline-block;
            padding: 14px 35px;
            background-color: #0066FF;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            transition: background-color 0.2s;
        }

        .button:hover {
            background-color: #0052CC;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        img {
            border-radius: 5px;
            object-fit: cover;
        }

        td img {
            display: block;
            margin: 0 auto;
        }

        .text-right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        img {
            border-radius: 5px;
            object-fit: cover;
        }

        td img {
            display: block;
            margin: 0 auto;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    @php
        use App\Enums\OrderStatus;
    @endphp
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                Trạng Thái Đơn Hàng
            </div>
            <div class="content">
                <p>Xin chào, {{ $user->full_name }}!</p>
                <p>Đơn hàng của bạn có mã <strong>#{{ $order->id }}</strong> đã được cập nhật trạng thái:</p>
                <p class="status">{{ OrderStatus::getLabelStatus($order->status) }}</p>

                <!--Dia chi nhan hang-->
                <div class="order-info ">
                    <p><strong>Địa chỉ nhận hàng:</strong></p>
                    <p>
                        Người nhận: {{ $order->shipping_address->receiver_name }}<br>
                        Địa chỉ: {{ $order->shipping_address->specific_address }} -
                        {{ $order->shipping_address->ward->WardName }} -
                        {{ $order->shipping_address->district->DistrictName }}
                        - {{ $order->shipping_address->province->ProvinceName }}<br>
                        <br>
                        Số điện thoại: {{ $order->shipping_address->receiver_phone_number }}<br>
                    </p>
                    <p style="font-weight: 600">
                        Tổng số tiền: {{ $order['final_amount'] }}₫ (đã bao gồm phí vận chuyển)
                    </p>
                </div>

                <hr>

                @if (!empty($order['orderItems']))
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th class="text-right">Giá</th>
                                <th class="text-right">Giảm giá</th>
                                <th class="text-right">Giá sản phẩm sau khi giảm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order['orderItems'] as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ $item['product']['cover_image'] }}"
                                            alt="{{ $item['product']['title'] }}" style="width: 50px; height: 50px;">
                                    </td>
                                    <td style="width: 250px;">{{ $item['product']['title'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td class="text-right">{{ number_format($item['price'], 0, ',', '.') }} đ</td>
                                    <td class="text-right">{{ $item['discount'] }}%</td>
                                    <td class="text-right">
                                        {{ number_format($item['price'] * $item['quantity'] * (1 - $item['discount'] / 100), 0, ',', '.') }}
                                        đ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Không có sản phẩm nào trong đơn hàng.</p>
                @endif

                @php

                    $fe_url = config('app.frontend_url') . '/customer/orders';
                @endphp

                <a href="{{ $fe_url }}" class="button">Xem chi tiết đơn hàng</a>
            </div>
            <div class="footer">
                Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi!
            </div>
        </div>
    </div>
</body>

</html>
