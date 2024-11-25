<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    
    // Kiểm tra số lượng tồn kho
    $stock_check = $conn->query("SELECT stock FROM products WHERE product_id = $product_id");
    $stock = $stock_check->fetch_assoc()['stock'];
    
    if ($stock <= 0) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm đã hết hàng']);
        exit;
    }

    // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
    $cart_check = $conn->query("SELECT quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    
    if ($cart_check->num_rows > 0) {
        // Cập nhật số lượng
        $current_quantity = $cart_check->fetch_assoc()['quantity'];
        if ($current_quantity < $stock) {
            $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
        } else {
            echo json_encode(['success' => false, 'message' => 'Đã đạt số lượng tối đa có thể mua']);
            exit;
        }
    } else {
        // Thêm mới vào giỏ hàng
        $conn->query("INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES ($user_id, $product_id, 1, NOW())");
    }

    // Lấy số lượng sản phẩm trong giỏ hàng
    $cart_count = $conn->query("SELECT COUNT(*) as count FROM cart WHERE user_id = $user_id")->fetch_assoc()['count'];
    
    echo json_encode(['success' => true, 'cart_count' => $cart_count]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>