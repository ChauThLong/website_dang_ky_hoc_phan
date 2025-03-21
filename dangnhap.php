<?php
session_start();
include 'db_connect.php';

// Biến thông báo lỗi (nếu có)
$error = "";

// Nếu form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $MaSV = $_POST['MaSV'];

    // Kiểm tra xem MaSV có tồn tại trong bảng SinhVien hay không
    $sql = "SELECT * FROM SinhVien WHERE MaSV = '$MaSV'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Nếu tìm thấy, coi như đăng nhập thành công
        // Lấy thông tin SV (nếu cần lưu vào session)
        $row = $result->fetch_assoc();

        // Lưu một số thông tin vào session
        $_SESSION['logged_in'] = true;
        $_SESSION['MaSV'] = $row['MaSV'];
        $_SESSION['HoTen'] = $row['HoTen'];

        // Chuyển hướng về trang index (hoặc trang khác tùy ý)
        header("Location: index.php");
        exit;
    } else {
        // Không tìm thấy MaSV
        $error = "Mã sinh viên không tồn tại hoặc sai.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <!-- Link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Thanh navigation (nếu có) -->
<?php include('nav.php'); ?>

<div class="container mt-4">
    <h1 class="mb-4">ĐĂNG NHẬP</h1>

    <!-- Hiển thị thông báo lỗi (nếu có) -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Form đăng nhập -->
    <form action="" method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="MaSV" class="form-label">MaSV</label>
            <input type="text" class="form-control" id="MaSV" name="MaSV" required>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Đăng Nhập</button>
        </div>
    </form>

    <div class="mt-3">
        <a href="index.php">Back to List</a>
    </div>
</div>

<!-- JS Bootstrap (nếu cần) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
