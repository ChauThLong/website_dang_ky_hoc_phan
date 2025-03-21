<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $MaSV     = $_POST['MaSV'];
    $HoTen    = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh  = $_POST['MaNganh'];
    $Hinh = "";

    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] === 0) {
        $uploadDir = 'uploads/';

        $fileName = basename($_FILES['Hinh']['name']);

        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $targetFile)) {
            $Hinh = $targetFile;
        } else {
            echo "Có lỗi xảy ra khi upload file.";
        }
    }

    $sqlInsert = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh)
                  VALUES ('$MaSV', '$HoTen', '$GioiTinh', '$NgaySinh', '$Hinh', '$MaNganh')";

    if ($conn->query($sqlInsert) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

$sqlNganh = "SELECT MaNganh, TenNganh FROM NganhHoc";
$resultNganh = $conn->query($sqlNganh);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sinh Viên</title>
    <!-- Link CSS của Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Thanh navigation (nếu có) -->
    <?php include('nav.php'); ?>

    <div class="container mt-4">
        <h1 class="mb-4">THÊM SINH VIÊN</h1>
        
        <!-- Form thêm sinh viên -->
        <!-- Chú ý: phải có enctype="multipart/form-data" để upload file -->
        <form action="add_student.php" method="POST" class="row g-3" enctype="multipart/form-data">
            <!-- MaSV -->
            <div class="col-md-6">
                <label for="MaSV" class="form-label">MaSV</label>
                <input type="text" class="form-control" id="MaSV" name="MaSV" required>
            </div>

            <!-- HoTen -->
            <div class="col-md-6">
                <label for="HoTen" class="form-label">Họ Tên</label>
                <input type="text" class="form-control" id="HoTen" name="HoTen" required>
            </div>

            <!-- GioiTinh -->
            <div class="col-md-6">
                <label for="GioiTinh" class="form-label">Giới Tính</label>
                <input type="text" class="form-control" id="GioiTinh" name="GioiTinh" required>
            </div>

            <!-- NgaySinh -->
            <div class="col-md-6">
                <label for="NgaySinh" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" required>
            </div>

            <!-- Hinh: input type file -->
            <div class="col-md-6">
                <label for="Hinh" class="form-label">Chọn hình</label>
                <input type="file" class="form-control" id="Hinh" name="Hinh">
            </div>

            <!-- MaNganh (select) -->
            <div class="col-md-6">
                <label for="MaNganh" class="form-label">Ngành</label>
                <select class="form-select" id="MaNganh" name="MaNganh" required>
                    <option value="">--Chọn ngành--</option>
                    <?php
                    if ($resultNganh->num_rows > 0) {
                        while ($rowNganh = $resultNganh->fetch_assoc()) {
                            echo "<option value='" . $rowNganh['MaNganh'] . "'>" . $rowNganh['TenNganh'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Nút Submit -->
            <div class="col-12">
                <button type="submit" class="btn btn-success">Create</button>
            </div>
        </form>

        <!-- Link quay lại trang danh sách -->
        <div class="mt-3">
            <a href="index.php">Quay lại</a>
        </div>
    </div>

    <!-- JS Bootstrap (nếu cần) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
