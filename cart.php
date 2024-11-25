<?php
session_start();
require_once 'config.php'; // Kết nối cơ sở dữ liệu

// Lấy thông tin giỏ hàng từ session
$cart = $_SESSION['cart'] ?? [];

if (!empty($cart)) {
    echo "<h1>Giỏ Hàng</h1>";
    foreach ($cart as $product_id => $details) {
        echo "Sản phẩm ID: $product_id - Số lượng: {$details['quantity']}<br>";
    }
} else {
    echo "<h1>Giỏ hàng trống</h1>";
}
?>
