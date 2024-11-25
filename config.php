<?php
$server = "localhost";
$user = "root";
$pass = "";
$db_name = "elecs";
//muốn kết nối sử dụng đối tượng connection có sẵn của php kdl resource
$conn = new mysqli($server, $user, $pass, $db_name);
if ($conn->connect_error) {
    die("Lỗi kết nối" . $conn->connect_error);
}
//echo "Kết nối thành công";

?>