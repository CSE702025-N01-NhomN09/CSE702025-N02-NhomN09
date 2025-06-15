<?php
  session_start();
  include 'cndb.php';

  if (!isset($_SESSION['msv'])) {
      header("Location: login.php");
      exit();
  }

  $msv = $_SESSION['msv'];

  $sql = "SELECT * FROM sinhvien WHERE MSV = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $msv);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();

  function generateRandomMaPhanAnh($length = 6) {
      return 'PA' . str_pad(mt_rand(0, pow(10, $length)-1), $length, '0', STR_PAD_LEFT);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
      $noidung = $conn->real_escape_string($_POST['content']);
      $ngaygui = date('Y-m-d');
      $trangthai = 'Đang xử lý';

      do {
          $maphananh = generateRandomMaPhanAnh();
          $check = $conn->prepare("SELECT Maphananh FROM phananh WHERE Maphananh = ?");
          $check->bind_param("s", $maphananh);
          $check->execute();
          $result = $check->get_result();
          $check->close();
      } while ($result->num_rows > 0);

      $sql = "INSERT INTO phananh (Maphananh, Noidung, Ngaygui, Trangthai, MSV) VALUES (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssss", $maphananh, $noidung, $ngaygui, $trangthai, $msv);

      if ($stmt->execute()) {
          echo json_encode(["success" => true, "Maphananh" => $maphananh]);
      } else {
          echo json_encode(["success" => false, "error" => $stmt->error]);
      }

      exit;
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dangky'])) {
      $tendv = $_POST['tendv'];
      $chiphi = (int) $_POST['chiphi'];

      $stmt = $conn->prepare("INSERT INTO dichvu (MSV, Tendichvu, Chiphi) VALUES (?, ?, ?)");
      $stmt->bind_param("isi", $msv, $tendv, $chiphi);
      $stmt->execute();

      echo "<script>alert('Đăng ký dịch vụ thành công!');</script>";
  }

  $thongbao = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['maphong'])) {
      $maphong = $_POST['maphong'];

      $check = $conn->query("SELECT * FROM danhsacho WHERE MSV = '$msv'");
      if ($check->num_rows > 0) {
          $thongbao = "<script>alert('Bạn đã đăng ký phòng rồi. Không thể đăng ký thêm.');</script>";
      } else {

          $sql_sv = $conn->query("SELECT * FROM sinhvien WHERE MSV = '$msv'");
          $sv = $sql_sv->fetch_assoc();

          $sql_phong = $conn->query("SELECT * FROM phong WHERE Maphong = '$maphong'");
          $phong = $sql_phong->fetch_assoc();

          if ($sv['Gioitinh'] != $phong['Gioitinh']) {
              $thongbao = "<script>alert('Không thể đăng ký. Giới tính không phù hợp.');</script>";
          } elseif ($phong['Songuoio'] >= $phong['Succhua']) {
              $thongbao = "<script>alert('Phòng đã đủ người. Không thể đăng ký.');</script>";
          } else {
              $conn->query("INSERT INTO danhsacho (Maphong, TenSV, MSV, Khoa, Gioitinh, Sodienthoai)
                          VALUES ('$maphong', '{$sv['Hoten']}', '{$sv['MSV']}', '{$sv['Khoa']}', '{$sv['Gioitinh']}', '{$sv['Sodienthoai']}')");
              $conn->query("UPDATE phong SET Songuoio = Songuoio + 1 WHERE Maphong = '$maphong'");
              $thongbao = "<script>alert('Đăng ký thành công vào phòng');</script>";
          }
      }
  }

  $sql_sv = "SELECT * FROM sinhvien WHERE MSV = ?";
  $stmt_sv = $conn->prepare($sql_sv);
  $stmt_sv->bind_param("i", $msv);
  $stmt_sv->execute();
  $result_sv = $stmt_sv->get_result();
  $data_sv = $result_sv->fetch_assoc();

  $sql_phong = "SELECT p.Giathue, p.Maphong
                FROM danhsacho ds
                JOIN phong p ON ds.Maphong = p.Maphong
                WHERE ds.MSV = ?";
  $stmt_phong = $conn->prepare($sql_phong);
  $stmt_phong->bind_param("i", $msv);
  $stmt_phong->execute();
  $result_phong = $stmt_phong->get_result();

  $giathue = 0;
  $maphong = 0;
  if ($row = $result_phong->fetch_assoc()) {
      $giathue = $row['Giathue'];
      $maphong = $row['Maphong'];
  }

  $sql_dichvu = "SELECT Tendichvu, Chiphi FROM dichvu WHERE MSV = ?";
  $stmt_dv = $conn->prepare($sql_dichvu);
  $stmt_dv->bind_param("i", $msv);
  $stmt_dv->execute();
  $result_dv = $stmt_dv->get_result();

  $tongchiphi = $giathue;

  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['thanhtoan'])) {
    $msv = $_SESSION['msv'];
    $stmt = $conn->prepare("DELETE FROM dichvu WHERE MSV = ?");
    $stmt->bind_param("i", $msv);
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit;
}
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['thanhtoan'])) {
    $msv = $_SESSION['msv'];
    $thang = date('n');
    $nam = date('Y');
    $ngay = date('Y-m-d');
    $tongTien = 0;

    $stmt1 = $conn->prepare("SELECT p.Giathue FROM danhsacho d JOIN phong p ON d.Maphong = p.Maphong WHERE d.MSV = ?");
    $stmt1->bind_param("i", $msv);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    if ($row1 = $res1->fetch_assoc()) {
        $tongTien += (int)$row1['Giathue'];
    }

    $stmt2 = $conn->prepare("SELECT Chiphi FROM dichvu WHERE MSV = ?");
    $stmt2->bind_param("i", $msv);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while ($row2 = $res2->fetch_assoc()) {
        $tongTien += (int)$row2['Chiphi'];
    }

    $trangthai = "Đã TT";
    $stmt3 = $conn->prepare("INSERT INTO giaodich (Ngaygiaodich, Trangthai, Sotien, MSV, Thang, Nam) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt3->bind_param("ssiiii", $ngay, $trangthai, $tongTien, $msv, $thang, $nam);
    $stmt3->execute();

    $stmt4 = $conn->prepare("DELETE FROM dichvu WHERE MSV = ?");
    $stmt4->bind_param("i", $msv);
    $stmt4->execute();

    echo json_encode(["success" => true]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang Sinh viên</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
  <link rel="stylesheet" href="./assets/css/dashboard.css"/>
  <link rel="stylesheet" href="./assets/fonts/fontawesome-free-6.7.2-web/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
  <div class="dashboard">
    <header class="header">
      <nav class="header__navbar">
        <ul class="header__navbar-list">
          <li class="header__navbar-items"><a onclick="showSection('profile')">Thông tin cá nhân</a></li>
          <li class="header__navbar-items"><a onclick="showSection('info_room')">Thông tin phòng</a></li>
          <li class="header__navbar-items"><a onclick="showSection('register')">Đăng ký phòng</a></li>
          <li class="header__navbar-items"><a onclick="showSection('service')">Dịch vụ</a></li>
          <li class="header__navbar-items"><a onclick="showSection('pay')">Thanh toán</a></li>
          <li class="header__navbar-items"><a onclick="showSection('request')">Phản ánh</a></li>
        </ul>
        <ul class="header__navbar-list">
          <li class="header__navbar-items">
            <a class="logout" href="login.php">Đăng xuất <i class="fa-solid fa-right-to-bracket fa-lg"></i></a>
          </li>
        </ul>
      </nav>
    </header>
    <div class="container">
      <div id="profile" class="section">
        <h2>Thông tin cá nhân</h2>
        <p><strong>Họ tên : </strong> <?= $data['Hoten'] ?></p>
        <p><strong>MSV : </strong> <?= $data['MSV'] ?></p>
        <p><strong>Ngày sinh : </strong> <?= date("d/m/Y", strtotime($data['Ngaysinh'])) ?></p>
        <p><strong>Khoa : </strong> <?= $data['Khoa'] ?></p>
        <p><strong>Lớp : </strong> <?= $data['Lop'] ?></p>
        <p><strong>Giới tính : </strong> <?= $data['Gioitinh'] ?></p>
        <p><strong>Quê quán : </strong> <?= $data['Quequan'] ?></p>
        <p><strong>SĐT : </strong> <?= $data['Sodienthoai'] ?></p>
      </div>
      <div id="info_room" class="section">
        <div class="main_info_room">
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_4.jpg">
              <h1>101</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Cho phép</p>
              <p><i class="fa-solid fa-person"></i> Số người : 4 (Nam)</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 1,000,000đ</p>
            </div>
          </div>
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_4.jpg">
              <h1>102</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Cho phép</p>
              <p><i class="fa-solid fa-person"></i> Số người : 4 (Nam)</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 1,000,000đ</p>
            </div>
          </div>
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_4.jpg">
              <h1>103</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Cho phép</p>
              <p><i class="fa-solid fa-person"></i> Số người : 4 (Nữ)</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 1,000,000đ</p>
            </div>
          </div>
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_4.jpg">
              <h1>104</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Cho phép</p>
              <p><i class="fa-solid fa-person"></i> Số người : 4 (Nữ)</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 1,000,000đ</p>
            </div>
          </div>
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_6.jpg">
              <h1>105</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Cho phép</p>
              <p><i class="fa-solid fa-person"></i> Số người : 6 (Nam)</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 800,000đ</p>
            </div>
          </div>
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_6.jpg">
              <h1>106</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Cho phép</p>
              <p><i class="fa-solid fa-person"></i> Số người : 6 (Nữ)</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 800,000đ</p>
            </div>
          </div>
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_6.jpg">
              <h1>107</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Không cho phép</p>
              <p><i class="fa-solid fa-person"></i> Số người : 6 (Nam)</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 600,000đ</p>
            </div>
          </div>
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_6.jpg">
              <h1>108</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Không cho phép</p>
              <p><i class="fa-solid fa-person"></i> Số người : 6 (Nữ)</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 600,000đ</p>
            </div>
          </div>
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_8.jpg">
              <h1>109</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Không cho phép</p>
              <p><i class="fa-solid fa-person"></i> Số người : 8 (Nam)</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 400,000đ</p>
            </div>
          </div>
          <div class="home_product">
            <div class="info">
              <img class="review_room" src="assets/img/phong_8.jpg">
              <h1>110</h1>
              <p><i class="fa-solid fa-fan"></i> Máy lạnh : Có</p>
              <p><i class="fa-solid fa-bowl-food"></i> Nấu ăn : Không cho phép (Nữ)</p>
              <p><i class="fa-solid fa-person"></i> Số người : 8</p>
              <p class="status">Hoạt động tốt</p>
              <p><i class="fa-solid fa-money-bill"></i> Giá : 400,000đ</p>
            </div>
          </div>
        </div>
      </div>
      <div id="register" class="section">
        <div class="main_register">
          <?php if (!empty($thongbao)): ?>
              <div class="thongbao"><?= $thongbao ?></div>
          <?php endif; ?>

          <table class="DangKyPhong">
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
        </div>
      </div>
      <div id="service" class="section">
        <div class="main-service">
          <div class="box" id="Box1">
            <h1>Gửi xe</h1>
            <p><i class="fa-solid fa-bicycle"></i> 20,000đ/tháng (Xe đạp)</p>
            <p><i class="fa-solid fa-motorcycle"></i> 40,000đ/tháng (Xe máy)</p>  
            <p><i class="fa-solid fa-car"></i> 2,000,000đ/tháng (Ô tô)</p>
            <button onclick="showForm('GuiXeForm')">Đăng ký</button>
          </div>
          <div class="box" id="Box2">
            <h1>Giặt đồ</h1>
            <p>10,000đ/kg (Quần áo thường)</p>
            <p>25,000đ/kg (Chăn lông , Các đồ nặng)</p>
            <button onclick="showForm('GiatDoForm')">Đăng ký</button>
          </div>
          <div class="box" id="Box3">
            <h1>Vệ sinh</h1>
            <p>200,000đ/tháng (Dọn dẹp phòng ở , hành lang)</p>
            <p>250,000/tháng (Dọn dẹp phòng ở , hành lang , nhà vệ sinh)</p>
            <button onclick="showForm('VeSinhForm')">Đăng ký</button>
          </div>
          <div id="GuiXeForm" class="form-overlay">
            <h2>Đăng ký gửi xe</h2>
            <form method="POST">
              <select name="tendv" required onchange="updateGuiXePrice(this)">
                <option value="Gửi xe đạp">Xe đạp - 20,000đ</option>
                <option value="Gửi xe máy">Xe máy - 40,000đ</option>
                <option value="Gửi ô tô">Ô tô - 2,000,000đ</option>
              </select><br><br>
              <input type="hidden" name="chiphi" id="chiphi_gui">
              <button type="submit" name="dangky" onclick="return setGia('GuiXeForm')">Xác nhận</button>
            </form>
          </div>

          <div id="GiatDoForm" class="form-overlay">
            <h2>Đăng ký giặt đồ</h2>
            <form method="POST">
              <select name="tendv" id="giatLoai" required>
                <option value="Giặt đồ thường">Quần áo thường - 10,000đ/kg</option>
                <option value="Giặt đồ nặng">Đồ nặng - 25,000đ/kg</option>
              </select><br><br>
              <input type="number" id="soKg" min="1" placeholder="Nhập số kg" required><br><br>
              <input type="hidden" name="chiphi" id="chiphi_giat">
              <button type="submit" name="dangky" onclick="return setGia('GiatDoForm')">Xác nhận</button>
            </form>
          </div>

          <div id="VeSinhForm" class="form-overlay">
            <h2>Đăng ký vệ sinh</h2>
            <form method="POST">
              <select name="tendv" required onchange="updateVeSinhPrice(this)">
                <option value="Vệ sinh cơ bản">Dọn phòng + hành lang - 200,000đ</option>
                <option value="Vệ sinh đầy đủ">Thêm nhà vệ sinh - 250,000đ</option>
              </select><br><br>
              <input type="hidden" name="chiphi" id="chiphi_vesinh">
              <button type="submit" name="dangky" onclick="return setGia('VeSinhForm')">Xác nhận</button>
            </form>
          </div>
        </div>
      </div>
      <div id="pay" class="section">
        <div class="main_pay">
          <h2>Chi tiết thanh toán</h2>
          <p><strong>Sinh viên:</strong> <?= htmlspecialchars($data_sv['Hoten'] ?? 'Không rõ') ?> - <strong>MSV:</strong> <?= $msv ?></p>
          <p><strong>Thanh toán cho tháng <?= date('m') ?>/<?= date('Y') ?></strong></p>
          <table>
              <tr>
                  <th>Loại chi phí</th>
                  <th>Chi tiết</th>
                  <th>Số tiền (VND)</th>
              </tr>
              <tr>
                  <td>Tiền phòng</td>
                  <td>Phòng số <?= $maphong ?></td>
                  <td><?= number_format($giathue) ?></td>
              </tr>
              <?php while ($row = $result_dv->fetch_assoc()): ?>
                  <tr>
                      <td>Dịch vụ</td>
                      <td><?= htmlspecialchars($row['Tendichvu']) ?></td>
                      <td><?= number_format($row['Chiphi']) ?></td>
                  </tr>
                  <?php $tongchiphi += $row['Chiphi']; ?>
              <?php endwhile; ?>
              <tr class="tong">
                  <td colspan="2"><strong>Tổng cộng</strong></td>
                  <td><strong><?= number_format($tongchiphi) ?> VND</strong></td>
              </tr>
          </table>
          <button onclick="xacNhanThanhToan()">Xác nhận thanh toán</button>
        </div>
      </div>
      <div id="request" class="section">
        <div class="box_request">
          <div class="main_request">
              <div class="input">
                <h2>Phản Ánh Dịch Vụ</h2>
                <form id="request_form">
                  <textarea id="content" name="content" placeholder="Nội dung phản ánh" required></textarea><br><br>
                  <button type="submit">Xác nhận</button>
                </form>
              </div>
              <div class="confirm">
                <table id="request_table">
                  <thead>
                    <tr>
                      <th>STT</th>
                      <th>Nội dung</th>
                      <th>Ngày gửi</th>
                      <th>Trạng thái</th>
                      <th>Mã phản ánh</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
          </div>
        </div>
      </div>
    </div>
    <footer class="footer">
    </footer>
  </div>
  <script src="kytucxa.js"></script>
</body>
</html>