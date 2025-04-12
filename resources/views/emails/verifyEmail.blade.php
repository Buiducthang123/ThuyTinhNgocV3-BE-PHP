<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
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
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .logo-header {
            text-align: center;
            padding: 30px 20px;
            background: #ffffff;
            border-bottom: 1px solid #eaeaea;
        }
        .logo-header img {
            height: 40px;
            width: auto;
        }
        .main-content {
            padding: 40px 50px;
            background: #ffffff;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0 0 20px 0;
        }
        .message {
            font-size: 16px;
            color: #444444;
            margin-bottom: 30px;
        }
        .cta-button {
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
        .cta-button:hover {
            background-color: #0052CC;
        }
        .approval-notice {
            background-color: #FFF8E6;
            border-left: 4px solid #FFC107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .approval-notice h3 {
            color: #B7832F;
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: 600;
        }
        .approval-notice p {
            color: #666666;
            margin: 0;
            font-size: 14px;
        }
        .divider {
            height: 1px;
            background-color: #eaeaea;
            margin: 30px 0;
        }
        .alternate-link {
            word-break: break-all;
            color: #666666;
            font-size: 14px;
            margin-top: 15px;
        }
        .footer {
            background-color: #fafafa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #eaeaea;
        }
        .footer p {
            margin: 5px 0;
            color: #666666;
            font-size: 14px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-link {
            display: inline-block;
            margin: 0 10px;
            color: #666666;
            text-decoration: none;
        }
        .help-text {
            font-size: 13px;
            color: #999999;
        }
        @media only screen and (max-width: 600px) {
            .main-content {
                padding: 30px 20px;
            }
            .greeting {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="logo-header">
                <!-- Replace with your logo -->
                <h1 style="margin: 0; color: #0066FF;">{{ config('app.name') }}</h1>
            </div>

            <div class="main-content">
                <h2 class="greeting">Xin chào {{ $user->full_name }}!</h2>

                <p class="message">
                    Cảm ơn bạn đã đăng ký tài khoản tại <strong>{{ config('app.name') }}</strong>.
                    Để bắt đầu sử dụng dịch vụ của chúng tôi, vui lòng xác nhận địa chỉ email của bạn.
                </p>

                @if($user->role_id == 3)
                <div class="approval-notice">
                    <h3>🕒 Tài khoản đang chờ phê duyệt</h3>
                    <p>Sau khi xác nhận email, tài khoản của bạn sẽ được chuyển đến quản trị viên để xem xét.
                    Chúng tôi sẽ thông báo ngay khi quá trình phê duyệt hoàn tất.</p>
                </div>
                @endif

                <a href="{{ $url }}" class="cta-button">Xác nhận Email</a>

                <p class="alternate-link">
                    Nếu nút không hoạt động, vui lòng sao chép và dán liên kết sau vào trình duyệt:<br>
                    <a href="{{ $url }}" style="color: #0066FF; text-decoration: none;">{{ $url }}</a>
                </p>

                <div class="divider"></div>

                <p class="help-text">
                    Nếu bạn không tạo tài khoản tại {{ config('app.name') }}, vui lòng bỏ qua email này.
                </p>
            </div>

            <div class="footer">
                <p><strong>{{ config('app.name') }}</strong></p>
                <div class="social-links">
                    <a href="#" class="social-link">Website</a>
                    <a href="#" class="social-link">Facebook</a>
                    <a href="#" class="social-link">Twitter</a>
                </div>
                <p class="help-text">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                    <a href="#" style="color: #666666;">Privacy Policy</a> •
                    <a href="#" style="color: #666666;">Terms of Service</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
