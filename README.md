# ThuyTinhNgoc - Laravel API Project

ThuyTinhNgoc là một dự án API Laravel cho hệ thống thương mại điện tử chuyên kinh doanh các sản phẩm thủy tinh ngọc. Hệ thống bao gồm đầy đủ các chức năng từ quản lý người dùng, danh mục, sản phẩm đến đơn hàng, thanh toán và đánh giá.

## Cấu trúc cơ sở dữ liệu

Hệ thống gồm các bảng chính:

- **roles**: Quản lý vai trò người dùng trong hệ thống
- **users**: Thông tin tài khoản người dùng 
- **shipping_addresses**: Lưu trữ địa chỉ giao hàng
- **categories**: Danh mục sản phẩm (hỗ trợ đa cấp)
- **products**: Thông tin chi tiết về sản phẩm
- **promotions**: Các chương trình khuyến mãi
- **shopping_carts**: Giỏ hàng người dùng
- **orders & order_items**: Đơn hàng và chi tiết đơn hàng
- **reviews**: Đánh giá sản phẩm
- **discount_tiers**: Mức giá theo số lượng
- **product_transactions**: Giao dịch sản phẩm (nhập/xuất kho)
- **payments**: Thanh toán đơn hàng

Để biết chi tiết về thuộc tính và quan hệ giữa các bảng, vui lòng xem tệp `DATABASE_STRUCTURE.md`.

## Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- MySQL hoặc MariaDB
- Node.js & NPM (nếu sử dụng Laravel Mix)
- Redis (cho queue và cache)

## Cài đặt

### 1. Clone dự án

```bash
git clone https://github.com/your-username/thuy-tinh-ngoc.git
cd thuy-tinh-ngoc
```

### 2. Cài đặt các gói phụ thuộc

```bash
composer install
npm install
```

### 3. Tạo tệp môi trường

```bash
cp .env.example .env
```

### 4. Cấu hình tệp .env

Cấu hình kết nối cơ sở dữ liệu:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thuy_tinh_ngoc
DB_USERNAME=root
DB_PASSWORD=
```

Cấu hình Redis cho queue và cache:
```
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 5. Tạo khóa ứng dụng

```bash
php artisan key:generate
```

### 6. Tạo liên kết symbolic cho lưu trữ

```bash
php artisan storage:link
```

### 7. Chạy migration và seeder

```bash
php artisan migrate
php artisan db:seed
```

## Chạy dự án

### Khởi động máy chủ phát triển

```bash
php artisan serve
```

Ứng dụng sẽ chạy tại `http://localhost:8000`

### Triển khai API Endpoints

Tất cả các API endpoints được định nghĩa trong thư mục `routes/api.php`. Bạn có thể xem danh sách các routes hiện có bằng lệnh:

```bash
php artisan route:list
```

## Queue và Jobs

Dự án sử dụng hàng đợi Laravel để xử lý các tác vụ nền như gửi email, xử lý đơn hàng, và các tác vụ tốn thời gian khác.

### Cấu hình Queue

Đảm bảo rằng bạn đã cấu hình QUEUE_CONNECTION trong tệp .env:


### Chạy Queue Gửi MailMail

Để xử lý các jobs trong hàng đợi:

```bash
php artisan queue:listen
```
## Cache

### Xóa cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Cập nhật cache config

```bash
php artisan config:cache
```

### Cập nhật cache route

```bash
php artisan route:cache
```

## Artisan Commands

Dự án có thể có các lệnh Artisan tùy chỉnh trong thư mục `app/Console/Commands`. Để xem tất cả các lệnh có sẵn:

```bash
php artisan list
```

## Lưu ý phát triển

1. Sử dụng các kỹ thuật chuẩn RESTful API
2. Tui lười viết

## Liên hệ

Nếu có câu hỏi hoặc gặp vấn đề, vui lòng liên hệ:
i u a ii u aa

