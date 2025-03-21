<?php
// Kết nối CSDL
include 'db_connect.php';

// Kiểm tra xem có MaSV trên URL hay không
if (!isset($_GET['MaSV'])) {
    echo "Không có mã sinh viên để xem chi tiết.";
    exit;
}

$MaSV = $_GET['MaSV'];

// Truy vấn lấy thông tin chi tiết sinh viên
$sql = "
    SELECT s.MaSV, s.HoTen, s.GioiTinh, s.NgaySinh, s.Hinh,
           n.TenNganh
    FROM SinhVien s
    JOIN NganhHoc n ON s.MaNganh = n.MaNganh
    WHERE s.MaSV = '$MaSV'
";

$result = $conn->query($sql);

// Kiểm tra kết quả
if ($result->num_rows < 1) {
    echo "Không tìm thấy sinh viên có MaSV = $MaSV";
    exit;
}

// Lấy dòng dữ liệu
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin chi tiết sinh viên</title>
    <!-- Link CSS Bootstrap (nếu muốn giao diện đẹp) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Thanh navigation (nếu có) -->
<?php include('nav.php'); ?>

<div class="container mt-4">
    <h1 class="mb-4">Thông tin chi tiết sinh viên</h1>

    <!-- Khu vực hiển thị thông tin -->
    <div class="row">
        <div class="col-md-6">
            <!-- Thẻ card của Bootstrap -->
            <div class="card">
                <div class="card-body">
                    <!-- Tiêu đề card (tùy chọn) -->
                    <h5 class="card-title">Sinh Viên</h5>

                    <!-- Thông tin chi tiết -->
                    <p><strong>Họ Tên:</strong> <?php echo $row['HoTen']; ?></p>
                    <p><strong>Giới Tính:</strong> <?php echo $row['GioiTinh']; ?></p>
                    <p><strong>Ngày Sinh:</strong> <?php echo $row['NgaySinh']; ?></p>
                    
                    <p><strong>Hình:</strong></p>
                    <?php if (!empty($row['Hinh'])): ?>
                        <img src="<?php echo $row['Hinh']; ?>" alt="Ảnh sinh viên" style="max-width: 200px;">
                    <?php else: ?>
                        <p>Không có hình</p>
                    <?php endif; ?>

                    <p><strong>Ngành:</strong> <?php echo $row['TenNganh']; ?></p>

                    <!-- Link Edit & Back to List -->
                    <a href="edit_student.php?MaSV=<?php echo $row['MaSV']; ?>" class="btn btn-warning">Edit</a>
                    <a href="index.php" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Bootstrap (nếu cần) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>