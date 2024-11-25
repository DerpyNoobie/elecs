<?php
require_once ("config.php");

// Biến để lưu trữ sản phẩm và kiểm tra sản phẩm có hợp lệ
$product = null;

// Kiểm tra nếu có từ khóa tìm kiếm
if (isset($_GET['query'])) {
    // Lấy từ khóa tìm kiếm từ URL
    $query = $_GET['query'];
    
    // Truy vấn tìm kiếm sản phẩm theo tên
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
    $searchTerm = "%" . $query . "%"; // Bao quanh từ khóa với dấu '%' để tìm kiếm theo mẫu
    $stmt->bind_param("s", $searchTerm); // Bind tham số

    // Thực thi truy vấn
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy sản phẩm đầu tiên tìm được (nếu có)
    if ($result->num_rows > 0) {
        $products = $result->fetch_all(MYSQLI_ASSOC); // Lấy tất cả sản phẩm
    } else {
        echo "<p>Không tìm thấy sản phẩm nào khớp với từ khóa này.</p>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả tìm kiếm - <?= isset($query) ? htmlspecialchars($query) : 'Sản phẩm'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="comment.css">
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
            padding: 10px 0;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 50px;
            padding: 5px 10px;
            width: 50%;
        }

        .search-bar input {
            width: 100%;
            border: none;
            padding: 5px;
            font-size: 14px;
            border-radius: 25px;
        }

        .search-bar button {
            background-color: #ff5000;
            border: none;
            padding: 7px 10px;
            border-radius: 50%;
            cursor: pointer;
        }

        .search-bar button i {
            color: white;
            font-size: 18px;
        }

        .user-actions a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
        }

        .swiper {
            width: 100%;
            height: 300px;
            margin: 20px 0;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin: 40px 0 20px;
            color: #333;
        }

        .categories, .products-grid {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            padding: 0 20px;
        }

        .category-card, .product-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 30%;
            transition: transform 0.3s ease-in-out;
        }

        .category-card:hover, .product-card:hover {
            transform: scale(1.05);
        }

        .category-card i, .product-card img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
        }

        .category-card h3, .product-card h3 {
            padding: 15px;
            font-size: 18px;
            color: #333;
        }

        .category-card p, .product-card .price {
            padding: 0 15px 15px;
            font-size: 16px;
            color: #777;
        }

        .product-rating {
            padding: 0 15px;
        }

        .product-rating i {
            color: #FFD700;
        }

        .product-card button {
            background-color: #ff5000;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
            border-radius: 0 0 10px 10px;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Form tìm kiếm -->
        <form action="product.php" method="GET" class="search-bar">
    <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..." required>
    <button type="submit"><i class="fas fa-search"></i></button>
        
        <h2>Kết quả tìm kiếm cho: "<?= htmlspecialchars($query); ?>"</h2>

        <!-- Hiển thị kết quả tìm kiếm -->
        <div class="products-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <!-- Hình ảnh sản phẩm -->
                        <img src="<?= htmlspecialchars($product['image_url']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
                        
                        <!-- Tên sản phẩm -->
                        <h3><?= htmlspecialchars($product['name']); ?></h3>

                        <!-- Giá sản phẩm -->
                        <p class="price"><?= number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>

                        <!-- Nút thêm vào giỏ -->
                        <button onclick="addToCart(<?= $product['product_id']; ?>)">Thêm vào giỏ</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không tìm thấy sản phẩm nào khớp với từ khóa của bạn.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".add-to-cart-btn");
    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            const productId = button.getAttribute("data-id");

            // Gửi yêu cầu AJAX đến server
            fetch("add_to_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `product_id=${productId}`,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Sản phẩm đã được thêm vào giỏ!");
                } else {
                    alert("Có lỗi xảy ra. Vui lòng thử lại.");
                }
            })
            .catch((error) => {
                console.error("Lỗi khi thêm sản phẩm vào giỏ:", error);
            });
        });
    });
});

function addToCart(productId) {
    // Gửi yêu cầu AJAX đến server để thêm sản phẩm vào giỏ
    fetch("add_to_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `product_id=${productId}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Sản phẩm đã được thêm vào giỏ!");
        } else {
            alert("Có lỗi xảy ra: " + data.message);
        }
    })
    .catch(error => {
        console.error("Lỗi khi thêm sản phẩm vào giỏ:", error);
    });
}

</script>

</html>







