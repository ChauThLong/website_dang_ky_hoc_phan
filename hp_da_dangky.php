<?php
session_start();
include 'db_connect.php';

// Nếu chưa đăng nhập, chuyển về trang đăng nhập
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: dangnhap.php");
    exit;
}

// Lấy MaSV từ session (để lưu vào bảng DangKy)
$MaSV = $_SESSION['MaSV'];

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Xử lý các hành động (remove, save)
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // 1. Xóa một học phần khỏi giỏ
    if ($action === 'remove' && isset($_GET['MaHP'])) {
        $MaHPRemove = $_GET['MaHP'];
        if (isset($_SESSION['cart'][$MaHPRemove])) {
            unset($_SESSION['cart'][$MaHPRemove]);
        }
    }

    // 2. Lưu đăng ký vào DB
    if ($action === 'save') {
        // Nếu giỏ hàng rỗng thì không làm gì
        if (count($_SESSION['cart']) === 0) {
            header("Location: dangky.php");
            exit;
        }

        // Tạo bản ghi trong bảng DangKy
        $NgayDK = date('Y-m-d'); // hoặc date('Y-m-d H:i:s')
        $sqlDangKy = "INSERT INTO DangKy (NgayDK, MaSV) VALUES ('$NgayDK', '$MaSV')";
        if ($conn->query($sqlDangKy) === TRUE) {
            // Lấy MaDK vừa được tạo
            $MaDK = $conn->insert_id;

            // Thêm các dòng vào ChiTietDangKy
            foreach ($_SESSION['cart'] as $MaHP => $val) {
                $sqlChiTiet = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES ($MaDK, '$MaHP')";
                $conn->query($sqlChiTiet);
            }

            // Sau khi lưu thành công, xóa giỏ hàng
            $_SESSION['cart'] = array();

            // Quay lại chính trang này hoặc trang khác
            header("Location: dangky.php");
            exit;
        } else {
            echo "Lỗi khi lưu đăng ký: " . $conn->error;
        }
    }
}

// Lấy danh sách MaHP trong giỏ hàng
$cartItems = array_keys($_SESSION['cart']);

// Nếu giỏ trống, $cartItems = []
$hocPhanData = array();
$tongSoTinChi = 0;

// Nếu có học phần trong giỏ, ta truy vấn DB để lấy chi tiết
if (count($cartItems) > 0) {
    // Tạo chuỗi IN('CNTT01','CNTT02',...)
    $inClause = "'" . implode("','", $cartItems) . "'";
    $sql = "SELECT MaHP, TenHP, SoTinChi FROM HocPhan WHERE MaHP IN ($inClause)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $hocPhanData[] = $row;
        $tongSoTinChi += $row['SoTinChi'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Học Phần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('nav.php'); ?>

<div class="container mt-4">
    <h1>Đăng Kí Học Phần</h1>

    <?php if (count($hocPhanData) === 0): ?>
        <p>Giỏ học phần trống</p>
    <?php else: ?>
        <table class="table table-bordered table-hover mt-3">
            <thead>
                <tr>
                    <th>MaHP</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th></th> <!-- Cột Xóa -->
                </tr>
            </thead>
            <tbody>
            <?php foreach ($hocPhanData as $hp): ?>
                <tr>
                    <td><?php echo $hp['MaHP']; ?></td>
                    <td><?php echo $hp['TenHP']; ?></td>
                    <td><?php echo $hp['SoTinChi']; ?></td>
                    <td>
                        <a href="dangky.php?action=remove&MaHP=<?php echo $hp['MaHP']; ?>" 
                           class="btn btn-link text-danger">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Số học phần:</strong> <?php echo count($hocPhanData); ?></p>
        <p><strong>Tổng số tín chỉ:</strong> <?php echo $tongSoTinChi; ?></p>

        <a href="dangky.php?action=save" class="btn btn-primary">Lưu đăng ký</a>
    <?php endif; ?>

    <div class="mt-3">
        <a href="hocphan.php" class="btn btn-secondary">Quay lại danh sách học phần</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
