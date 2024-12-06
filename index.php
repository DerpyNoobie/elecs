<?php
session_start();
require_once 'config.php';

// Lấy sản phẩm nổi bật (dựa trên số lượng đơn hàng)
$featured_sql = "SELECT p.*, COUNT(oi.order_item_id) as order_count 
                FROM products p 
                LEFT JOIN orderitems oi ON p.product_id = oi.product_id 
                GROUP BY p.product_id 
                ORDER BY order_count DESC 
                LIMIT 6";
$featured_result = $conn->query($featured_sql);

// Lấy danh mục
$categories_sql = "SELECT * FROM categories"; // Truy vấn để lấy danh mục sản phẩm
$categories_result = $conn->query($categories_sql);

// Lấy sản phẩm mới nhất
$new_products_sql = "SELECT p.*, c.name as category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.category_id 
                    ORDER BY p.created_at DESC 
                    LIMIT 8";
$new_products_result = $conn->query($new_products_sql);

// Lấy sản phẩm được đánh giá cao
$top_rated_sql = "SELECT p.*, AVG(r.rating) as avg_rating, COUNT(r.review_id) as review_count 
                  FROM products p 
                  LEFT JOIN reviews r ON p.product_id = r.product_id 
                  GROUP BY p.product_id 
                  HAVING AVG(r.rating) >= 4 
                  ORDER BY avg_rating DESC 
                  LIMIT 8";
$top_rated_result = $conn->query($top_rated_sql);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    header('Content-Type: application/json'); // Đặt tiêu đề phản hồi là JSON
    session_start();

    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Kiểm tra và khởi tạo giỏ hàng
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Thêm sản phẩm vào giỏ hàng
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Phản hồi JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
        'cart_count' => count($_SESSION['cart']),
    ]);
    exit;
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElecS - Cửa hàng điện tử trực tuyến</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">
            <img alt="Logo" height="40" src="https://storage.googleapis.com/a1aa/image/mylyLKCkYlrtGdtPhUwBwsGK28OTaoRR5MajXoUeZJUQ5D4JA.jpg" width="40"/>
                <h1>ElecS</h1>
            </div>
            <form action="product.php" method="GET" class="search-bar">
    <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..." required>
    <button type="submit"><i class="fas fa-search"></i></button>
</form>

            <div class="user-actions">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <a href="profile.php" title="Hồ sơ cá nhân">
                        <i class="fas fa-user"></i>
                    </a>

                    <a href="order_history.php">Lịch sử đơn hàng</a>


                    <a href="logout.php">Đăng xuất</a>
                <?php else: ?>
                    <a href="login.php">Đăng nhập</a>
                    <a href="register.php">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <!-- Slider -->
        <div class="swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="https://storage.googleapis.com/a1aa/image/PelelF7G8Vlm10vnUeQ1ujlj5aBb9Fnhwwg2FMwPiAafIfAeE.jpg" alt="Promotion 1">
                </div>
                <div class="swiper-slide">
                    <img src="https://storage.googleapis.com/a1aa/image/Y9R7hmRFUh5HOhfaceoQinsHIlY7radMNWh0h7nGf1bwkPgnA.jpg" alt="Promotion 2">
                </div>
                <div class="swiper-slide">
                    <img src="https://storage.googleapis.com/a1aa/image/fFHZDcfCShoA40GdC8q3fOEnq4FvXFHRkGBgrLpzHIvYkPgnA.jpg" alt="Promotion 3">
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>

        <!-- Danh mục sản phẩm -->
        <h2 class="section-title">Danh mục sản phẩm</h2>
        <div class="categories">
            <?php if ($categories_result->num_rows > 0): ?>
                <?php while($category = $categories_result->fetch_assoc()): ?>
                    <a href="category.php?id=<?php echo $category['category_id']; ?>" class="category-card">
                        <i class="fas fa-folder-open"></i>
                        <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                        <p>20 sản phẩm</p>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Không có danh mục nào</p>
            <?php endif; ?>
        </div>

        <!-- Sản phẩm nổi bật -->
        <h2 class="section-title">Sản phẩm nổi bật</h2>
        <div class="products-grid">
            <?php while($product = $featured_result->fetch_assoc()): ?>
                <div class="product-card">
    <!-- Hình ảnh sản phẩm -->
    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
    
    alt="<?php echo htmlspecialchars($product['name']); ?>" 
    class="product-image">

    <!-- Tên sản phẩm -->
    <h3><?php echo htmlspecialchars($product['name']); ?></h3>


    <!-- Đánh giá sản phẩm -->
    <div class="product-rating">
        <?php
        // Lấy điểm đánh giá trung bình, mặc định là 0 nếu không có đánh giá
        $rating = isset($product['avg_rating']) ? round($product['avg_rating']) : 0;
        
        // Hiển thị sao đánh giá
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                echo '<i class="fas fa-star"></i>';  // Sao vàng (được đánh giá)
            } else {
                echo '<i class="far fa-star"></i>';  // Sao xám (chưa đánh giá)
            }
        }
        ?>
    </div>

    <!-- Giá sản phẩm -->
    <p class="price"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>
    <!-- Nút thêm vào giỏ -->
    <button onclick="addToCart(<?= $product['product_id']; ?>)">Thêm vào giỏ</button>



</div>

            <?php endwhile; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 ElecS. Tất cả quyền được bảo lưu.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    </script>

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
</body>
</html>
