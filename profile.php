<?php
session_start();
require_once 'config.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng từ cơ sở dữ liệu
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Xử lý cập nhật thông tin cá nhân
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Cập nhật cơ sở dữ liệu
    if ($new_password) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $new_username, $new_email, $new_password, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['username'] = $new_username; // Cập nhật session
        $update_success = "Cập nhật thông tin thành công!";
    } else {
        $update_error = "Có lỗi xảy ra. Vui lòng thử lại.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Hồ sơ cá nhân</h1>

    <?php if (isset($update_success)) : ?>
        <p class="success"><?php echo $update_success; ?></p>
    <?php endif; ?>

    <?php if (isset($update_error)) : ?>
        <p class="error"><?php echo $update_error; ?></p>
    <?php endif; ?>

    <form action="profile.php" method="POST">
        <label for="username">Tên hiển thị:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label for="password">Mật khẩu mới (để trống nếu không thay đổi):</label>
        <input type="password" id="password" name="password"><br>

        <button type="submit">Cập nhật</button>
    </form>

    <p><a href="logout.php">Đăng xuất</a></p>
</body>
</html>

<?php $conn->close(); ?>
