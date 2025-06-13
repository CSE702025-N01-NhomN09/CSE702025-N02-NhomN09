<?php
session_start();
include 'cndb.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tentk = $_POST['username'];
    $matkhau = $_POST['password'];

    $sql = "SELECT * FROM taikhoan WHERE TentaiKhoan = ? AND Matkhau = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $tentk, $matkhau);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['msv'] = $row['MSV'];
        header("Location: kytucxa.php");
        exit();
    } else {
        $error = "Sai tên tài khoản hoặc mật khẩu!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập - Sinh viên</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
  <link rel="stylesheet" href="assets/css/login.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
  <div class="main_login">
    <img class="backgroud_login" src="assets/img/background.jpg">
    <img class="banner-login" src="assets/img/header.png" alt="">
    <div class="login">
      <main>
        <h1 class="title">ĐĂNG NHẬP</h1>
        <form method="post">
            <input type="text" name="username" placeholder="Tên tài khoản" required />
            <input type="password" name="password" placeholder="Mật khẩu" required />
            <ul class="login-support">
                <li><a class="login-support-items" href="#">Quên mật khẩu</a></li>
                <li><a class="login-support-items" href="#">Trợ giúp</a></li>
            </ul>
            <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
            <button type="submit">Đăng nhập</button>
        </form>
      </main>
    </div>
  </div>
</body>
</html>