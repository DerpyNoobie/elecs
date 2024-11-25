<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Yêu cầu đăng nhập trước khi thanh toán
    exit();
}

// Lấy giỏ hàng từ session
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo "Giỏ hàng của bạn đang trống.";
    exit();
}

// Lấy thông tin sản phẩm từ cơ sở dữ liệu
$productIds = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
$stmt->bind_param(str_repeat('i', count($productIds)), ...$productIds);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Tính tổng tiền
$totalAmount = 0;
foreach ($products as $product) {
    $totalAmount += $product['price'] * $cart[$product['product_id']];
}

// Xử lý form thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address'])) {
    $address = trim($_POST['address']);
    $userId = $_SESSION['user_id'];

    // Tạo đơn hàng
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, address) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $userId, $totalAmount, $address);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    // Thêm chi tiết đơn hàng
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($products as $product) {
        $productId = $product['product_id'];
        $quantity = $cart[$productId];
        $price = $product['price'];
        $stmt->bind_param("iiid", $orderId, $productId, $quantity, $price);
        $stmt->execute();
    }

    // Xóa giỏ hàng sau khi thanh toán
    unset($_SESSION['cart']);

    echo "Thanh toán thành công! Mã đơn hàng của bạn là: " . $orderId;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
</head>
<body>
    <h1>Thanh toán</h1>
    <h2>Giỏ hàng của bạn</h2>
    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo number_format($product['price'], 2); ?> đ</td>
                    <td><?php echo $cart[$product['product_id']]; ?></td>
                    <td><?php echo number_format($product['price'] * $cart[$product['product_id']], 2); ?> đ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Tổng cộng: <?php echo number_format($totalAmount, 2); ?> đ</h3>

    <h2>Nhập thông tin giao hàng</h2>
    <form action="checkout.php" method="POST">
        <label for="address">Địa chỉ giao hàng:</label><br>
        <textarea name="address" id="address" required></textarea><br><br>
        <button type="submit">Thanh toán</button>
    </form>
</body>
</html>
