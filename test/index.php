<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ktx");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu chưa đăng nhập
if (!isset($_SESSION['msv'])) {
    echo "Vui lòng đăng nhập trước.";
    exit;
}

$msv = $_SESSION['msv'];
$thongbao = "";

// Nếu có hành động đăng ký
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['maphong'])) {
    $maphong = $_POST['maphong'];

    // Kiểm tra sinh viên đã đăng ký phòng nào chưa
    $check = $conn->query("SELECT * FROM danhsacho WHERE MSV = '$msv'");
    if ($check->num_rows > 0) {
        $thongbao = "❌ Bạn đã đăng ký phòng rồi. Không thể đăng ký thêm.";
    } else {
        // Lấy thông tin sinh viên
        $sql_sv = $conn->query("SELECT * FROM sinhvien WHERE MSV = '$msv'");
        $sv = $sql_sv->fetch_assoc();

        // Lấy thông tin phòng
        $sql_phong = $conn->query("SELECT * FROM phong WHERE Maphong = '$maphong'");
        $phong = $sql_phong->fetch_assoc();

        // Kiểm tra giới tính và chỗ trống
        if ($sv['Gioitinh'] != $phong['Gioitinh']) {
            $thongbao = "❌ Không thể đăng ký. Giới tính không phù hợp.";
        } elseif ($phong['Songuoio'] >= $phong['Succhua']) {
            $thongbao = "❌ Phòng đã đủ người. Không thể đăng ký.";
        } else {
            // Thêm vào danh sách ở
            $conn->query("INSERT INTO danhsacho (Maphong, TenSV, MSV, Khoa, Gioitinh, Sodienthoai)
                        VALUES ('$maphong', '{$sv['Hoten']}', '{$sv['MSV']}', '{$sv['Khoa']}', '{$sv['Gioitinh']}', '{$sv['Sodienthoai']}')");

            // Cập nhật số người ở
            $conn->query("UPDATE phong SET Songuoio = Songuoio + 1 WHERE Maphong = '$maphong'");

            $thongbao = "✅ Đăng ký thành công vào phòng $maphong.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký phòng</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>
    <h2>Danh sách phòng ký túc xá</h2>

    <?php if (!empty($thongbao)): ?>
        <div class="thongbao"><?= $thongbao ?></div>
    <?php endif; ?>

    <table>
        <tr>
            <th>Mã phòng</th>
            <th>Số người ở</th>
            <th>Sức chứa</th>
            <th>Giới tính</th>
            <th>Hành động</th>
        </tr>
        <?php
        $phongs = $conn->query("SELECT * FROM phong");
        while ($row = $phongs->fetch_assoc()):
        ?>
            <tr>
                <td><?= $row['Maphong'] ?></td>
                <td><?= $row['Songuoio'] ?></td>
                <td><?= $row['Succhua'] ?></td>
                <td><?= $row['Gioitinh'] ?></td>
                <td>
                    <form method="post" style="margin:0;">
                        <input type="hidden" name="maphong" value="<?= $row['Maphong'] ?>">
                        <button type="submit">Đăng ký</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
