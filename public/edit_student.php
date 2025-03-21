<?php
include 'db_connect.php';

// 1. Kiểm tra MaSV trên URL
if (!isset($_GET['MaSV'])) {
    echo "Không có mã sinh viên để sửa.";
    exit;
}

$MaSV = $_GET['MaSV'];

// 2. Lấy thông tin sinh viên từ CSDL
$sql = "SELECT * FROM SinhVien WHERE MaSV = '$MaSV'";
$result = $conn->query($sql);

if ($result->num_rows < 1) {
    echo "Không tìm thấy sinh viên có MaSV = $MaSV";
    exit;
}

// Lưu dữ liệu của sinh viên vào $row
$row = $result->fetch_assoc();

// Giữ đường dẫn ảnh cũ (nếu có)
$oldImagePath = $row['Hinh'];

// 3. Lấy danh sách ngành từ bảng NganhHoc để hiển thị
$sqlNganh = "SELECT MaNganh, TenNganh FROM NganhHoc";
$resultNganh = $conn->query($sqlNganh);

// 4. Nếu form được submit (POST), ta xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $HoTen    = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh  = $_POST['MaNganh'];

    // Mặc định giữ nguyên ảnh cũ
    $Hinh = $oldImagePath;

    // Nếu người dùng upload ảnh mới
    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] === 0) {
        $uploadDir  = 'uploads/';                     // Thư mục lưu file
        $fileName   = basename($_FILES['Hinh']['name']);
        $targetFile = $uploadDir . $fileName;

        // Di chuyển file tạm vào thư mục đích
        if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $targetFile)) {
            // Nếu upload thành công, ta cập nhật đường dẫn mới
            $Hinh = $targetFile;
        } else {
            echo "Có lỗi xảy ra khi upload file. Ảnh cũ vẫn được giữ.";
        }
    }

    // 5. Thực hiện UPDATE CSDL
    $sqlUpdate = "UPDATE SinhVien
                  SET HoTen    = '$HoTen',
                      GioiTinh = '$GioiTinh',
                      NgaySinh = '$NgaySinh',
                      Hinh     = '$Hinh',
                      MaNganh  = '$MaNganh'
                  WHERE MaSV   = '$MaSV'";

    if ($conn->query($sqlUpdate) === TRUE) {
        // Cập nhật thành công -> về trang danh sách
        header("Location: index.php");
        exit;
    } else {
        echo "Lỗi khi cập nhật: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thông tin sinh viên</title>
    <!-- Link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Thanh navigation (nếu có) -->
    <?php include('nav.php'); ?>

    <div class="container mt-4">
        <h1 class="mb-4">Chỉnh sửa thông tin sinh viên</h1>

        <!-- Form sửa thông tin sinh viên -->
        <!-- CHÚ Ý: phải có enctype="multipart/form-data" để upload file -->
        <form action="" method="POST" class="row g-3" enctype="multipart/form-data">
            <!-- Họ Tên -->
            <div class="col-md-6">
                <label for="HoTen" class="form-label">Họ Tên</label>
                <input type="text" class="form-control" id="HoTen" name="HoTen"
                       value="<?php echo $row['HoTen']; ?>" required>
            </div>

            <!-- Giới Tính -->
            <div class="col-md-6">
                <label for="GioiTinh" class="form-label">Giới Tính</label>
                <input type="text" class="form-control" id="GioiTinh" name="GioiTinh"
                       value="<?php echo $row['GioiTinh']; ?>" required>
            </div>

            <!-- Hình (chọn file mới) -->
            <div class="col-md-6">
                <label for="Hinh" class="form-label">Chọn hình</label>
                <input type="file" class="form-control" id="Hinh" name="Hinh">
                <br>
                <!-- Hiển thị ảnh hiện tại (nếu có) -->
                <?php if (!empty($row['Hinh'])): ?>
                    <img src="<?php echo $row['Hinh']; ?>" alt="Ảnh sinh viên" style="max-width: 200px;">
                <?php endif; ?>
            </div>

            <!-- Ngày Sinh -->
            <div class="col-md-6">
                <label for="NgaySinh" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" id="NgaySinh" name="NgaySinh"
                       value="<?php echo $row['NgaySinh']; ?>" required>
            </div>
            
            <!-- Ngành (select) -->
            <div class="col-md-6">
                <label for="MaNganh" class="form-label">Ngành</label>
                <select class="form-select" id="MaNganh" name="MaNganh" required>
                    <option value="">--Chọn ngành--</option>
                    <?php
                    // Nếu bạn không reset, nên truy vấn lại danh sách ngành trước form
                    $sqlNganhAgain = "SELECT MaNganh, TenNganh FROM NganhHoc";
                    $resultNganhAgain = $conn->query($sqlNganhAgain);

                    if ($resultNganhAgain->num_rows > 0) {
                        while ($rowNganh = $resultNganhAgain->fetch_assoc()) {
                            // Đánh dấu selected nếu MaNganh này = MaNganh của SV
                            $selected = ($rowNganh['MaNganh'] === $row['MaNganh']) ? "selected" : "";
                            echo "<option value='" . $rowNganh['MaNganh'] . "' $selected>" 
                                  . $rowNganh['TenNganh'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Nút Save -->
            <div class="col-12">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>

        <!-- Link quay lại trang danh sách -->
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
