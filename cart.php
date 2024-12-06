<?php
session_start();
require_once 'config.php'; // Đảm bảo kết nối cơ sở dữ liệu

echo "<h1>Giỏ hàng</h1>";

// Kiểm tra giỏ hàng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Giỏ hàng của bạn đang trống.</p>";
} else {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Truy vấn để lấy thông tin sản phẩm
        $stmt = $conn->prepare("SELECT name FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $product_name = $product['name'];
            echo "Sản phẩm: $product_name - Số lượng: $quantity<br>";
        } else {
            echo "Sản phẩm ID: $product_id - Số lượng: $quantity<br>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - ElecS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #ff5000;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 24px;
            font-weight: bold;
        }

        .cart-container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-title {
            text-align: center;
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #ddd;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-details {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .item-details img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-name {
            font-size: 16px;
            color: #333;
        }

        .item-quantity {
            text-align: right;
            font-size: 14px;
            color: #555;
        }

        .checkout {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .checkout button {
            background-color: #ff5000;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .checkout button:hover {
            background-color: #e04800;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-title">ElecS</div>
        <div class="header-actions">
            <a href="index.php" style="color: white; text-decoration: none;">Trang Chủ</a>
        </div>
    </header>
    <div class="cart-container">
        <h1 class="cart-title">Giỏ Hàng</h1>
        <!-- Cart items -->
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <div class="item-details">
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <span class="item-name"><?php echo $item['name']; ?></span>
                </div>
                <div class="item-quantity">Số lượng: <?php echo $item['quantity']; ?></div>
            </div>
        <?php endforeach; ?>
        <!-- Checkout button -->
        <div class="checkout">
            <button onclick="window.location.href='checkout.php'">Thanh Toán</button>
        </div>
    </div>
</body>
</html>
