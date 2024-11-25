<?php
session_start();
require_once 'config.php';  // Đảm bảo kết nối cơ sở dữ liệu

// Xử lý form đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_username']) && isset($_POST['login_password'])) {
    $username = trim($_POST['login_username']);
    $password = $_POST['login_password'];

    // Kiểm tra xem tên đăng nhập có tồn tại trong cơ sở dữ liệu không
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Lấy thông tin người dùng
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");  // Điều hướng lại về trang chính sau khi đăng nhập thành công
            exit();
        } else {
            $login_error = "Mật khẩu không chính xác.";
        }
    } else {
        $login_error = "Tên đăng nhập không tồn tại.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - ElecS</title>
    <link rel="stylesheet" href="log_reg.css">
</head>
<body>

    <h1>Đăng nhập</h1>

    <?php if (isset($login_error)) : ?>
        <p class="error"><?php echo $login_error; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="login_username">Tên đăng nhập:</label>
        <input type="text" id="login_username" name="login_username" required><br>

        <label for="login_password">Mật khẩu:</label>
        <input type="password" id="login_password" name="login_password" required><br>

        <button type="submit">Đăng nhập</button>
    </form>

    <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>

</body>
</html>

<?php $conn->close(); ?>
