<?php
include 'db_connect.php';

// Lấy danh sách học phần từ bảng HocPhan
$sql = "SELECT MaHP, TenHP, SoTinChi FROM HocPhan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Học Phần</title>
    <!-- Link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Thanh navigation -->
    <?php include('nav.php'); ?>

    <div class="container mt-4">
        <h1 class="mb-4">DANH SÁCH HỌC PHẦN</h1>
        
        <!-- Bảng hiển thị học phần -->
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th></th> <!-- Cột cho nút Đăng Ký -->
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['MaHP'] . "</td>";
                    echo "<td>" . $row['TenHP'] . "</td>";
                    echo "<td>" . $row['SoTinChi'] . "</td>";
                    // Nút Đăng Ký (điều hướng đến trang xử lý đăng ký)
                    // Bạn có thể thay 'dangkyhp.php' thành tên file xử lý của bạn
                    echo "<td><a href='dangkyhp.php?MaHP=" . $row['MaHP'] . "' class='btn btn-success'>Đăng Ký</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Không có học phần</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- JS Bootstrap (nếu cần) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
