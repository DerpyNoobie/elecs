<?php
session_start();
require_once 'config.php';

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "Mã đơn hàng không hợp lệ.";
    exit();
}

$orderId = intval($_GET['order_id']);
$userId = $_SESSION['user_id'];

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("
    SELECT * FROM orders 
    WHERE order_id = ? AND user_id = ?
");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "Không tìm thấy đơn hàng.";
    exit();
}

// Lấy danh sách sản phẩm trong đơn hàng
$stmt = $conn->prepare("
    SELECT oi.quantity, oi.price, p.name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$orderItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="style.css"> <!-- Thêm CSS nếu có -->
</head>
<body>
    <h1>Chi tiết đơn hàng #<?php echo htmlspecialchars($order['order_id']); ?></h1>
    <p><strong>Ngày đặt:</strong> <?php echo date("d-m-Y H:i:s", strtotime($order['order_date'])); ?></p>
    <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total_amount'], 2); ?> đ</p>
    <p><strong>Địa chỉ giao hàng:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
    <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($order['status']); ?></p>

    <h2>Sản phẩm</h2>
    <table>
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'], 2); ?> đ</td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> đ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="order_history.php">Quay lại lịch sử đơn hàng</a>
</body>
</html>
