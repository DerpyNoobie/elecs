<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);

    // Kiểm tra xem giỏ hàng đã tồn tại trong session chưa
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra sản phẩm có trong giỏ chưa
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += 1; // Tăng số lượng sản phẩm
    } else {
        $_SESSION['cart'][$productId] = 1; // Thêm sản phẩm mới với số lượng là 1
    }

    // Trả về phản hồi JSON
    echo json_encode([
        'success' => true,
        'message' => 'Sản phẩm đã được thêm vào giỏ hàng',
    ]);
} else {
    // Lỗi khi không có `product_id`
    echo json_encode([
        'success' => false,
        'message' => 'Yêu cầu không hợp lệ',
    ]);
}
