<?php
session_start();
require_once 'config.php';  // Đảm bảo kết nối cơ sở dữ liệu

// Xử lý form đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_username']) && isset($_POST['register_password']) && isset($_POST['register_email'])) {
    $username = trim($_POST['register_username']);
    $password = $_POST['register_password'];
    $email = trim($_POST['register_email']);
    
    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $register_error = "Tên đăng nhập đã tồn tại.";
    } else {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Thêm người dùng vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $email);
        if ($stmt->execute()) {
            $register_success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
        } else {
            $register_error = "Lỗi trong quá trình đăng ký. Vui lòng thử lại.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - ElecS</title>
    <link rel="stylesheet" href="log_reg.css">
</head>
<body>

    <h1>Đăng ký tài khoản</h1>

    <?php if (isset($register_error)) : ?>
        <p class="error"><?php echo $register_error; ?></p>
    <?php endif; ?>
    <?php if (isset($register_success)) : ?>
        <p class="success"><?php echo $register_success; ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <label for="register_username">Tên đăng nhập:</label>
        <input type="text" id="register_username" name="register_username" required><br>

        <label for="register_password">Mật khẩu:</label>
        <input type="password" id="register_password" name="register_password" required><br>

        <label for="register_email">Email:</label>
        <input type="email" id="register_email" name="register_email" required><br>

        <button type="submit">Đăng ký</button>
    </form>

    <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>

</body>
</html>

<?php $conn->close(); ?>
