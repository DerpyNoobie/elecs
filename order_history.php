<?php
session_start();
require_once 'config.php';

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của người dùng
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng</title>
    <link rel="stylesheet" href="style.css"> <!-- Thêm CSS nếu có -->
</head>
<body>
    <h1>Lịch sử đơn hàng</h1>
    <?php if (empty($orders)): ?>
        <p>Bạn chưa có đơn hàng nào.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo date("d-m-Y H:i:s", strtotime($order['order_date'])); ?></td>
                        <td><?php echo number_format($order['total_amount'], 2); ?> đ</td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><a href="order_detail.php?order_id=<?php echo $order['order_id']; ?>">Xem chi tiết</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
