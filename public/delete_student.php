<?php
include 'db_connect.php';

// 1. Kiểm tra MaSV trên URL
if (!isset($_GET['MaSV'])) {
    echo "Không có mã sinh viên để xóa.";
    exit;
}

$MaSV = $_GET['MaSV'];

// 2. Lấy thông tin sinh viên để hiển thị
$sql = "
    SELECT s.MaSV, s.HoTen, s.GioiTinh, s.NgaySinh, s.Hinh,
           n.TenNganh
    FROM SinhVien s
    JOIN NganhHoc n ON s.MaNganh = n.MaNganh
    WHERE s.MaSV = '$MaSV'
";

$result = $conn->query($sql);

if ($result->num_rows < 1) {
    echo "Không tìm thấy sinh viên có MaSV = $MaSV";
    exit;
}

// Lưu lại thông tin để hiển thị
$row = $result->fetch_assoc();

// 3. Nếu người dùng xác nhận xóa (phương thức POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thực hiện câu lệnh DELETE
    $sqlDelete = "DELETE FROM SinhVien WHERE MaSV = '$MaSV'";
    if ($conn->query($sqlDelete) === TRUE) {
        // Xóa thành công -> chuyển về trang danh sách
        header("Location: index.php");
        exit;
    } else {
        echo "Lỗi khi xóa: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa thông tin</title>
    <!-- Link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Thanh navigation (nếu có) -->
<?php include('nav.php'); ?>

<div class="container mt-4">
    <h1 class="mb-4">XÓA THÔNG TIN</h1>

    <!-- Hiển thị thông tin sinh viên để xác nhận -->
    <p class="lead">Are you sure you want to delete this?</p>

    <div class="row">
        <div class="col-md-6">
            <!-- Card hiển thị thông tin -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">SinhVien</h5>

                    <p><strong>Họ Tên:</strong> <?php echo $row['HoTen']; ?></p>
                    <p><strong>Giới Tính:</strong> <?php echo $row['GioiTinh']; ?></p>
                    <p><strong>Ngày Sinh:</strong> <?php echo $row['NgaySinh']; ?></p>
                    
                    <p><strong>Hình:</strong></p>
                    <?php if (!empty($row['Hinh'])): ?>
                        <img src="<?php echo $row['Hinh']; ?>" alt="Ảnh sinh viên" style="max-width: 200px;">
                    <?php else: ?>
                        <p>Không có hình</p>
                    <?php endif; ?>

                    <p><strong>MaNganh:</strong> <?php echo $row['TenNganh']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form xác nhận xóa (POST) -->
    <form method="POST" class="mt-3">
        <button type="submit" class="btn btn-danger">Delete</button>
        <a href="index.php" class="btn btn-secondary">Back to List</a>
    </form>
</div>

<!-- JS Bootstrap (nếu cần) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
