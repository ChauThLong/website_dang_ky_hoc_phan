<?php
include 'db_connect.php';

$sql = "SELECT s.MaSV, s.HoTen, s.GioiTinh, s.NgaySinh, s.Hinh, n.TenNganh
        FROM SinhVien s
        JOIN NganhHoc n ON s.MaNganh = n.MaNganh";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Sinh Viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        img {
            max-width: 200px;
            height: auto;
        }
        .container {
            width: 90%;
            margin: 0 auto;
        }
        h1 {
            margin-top: 20px;
        }
        .actions a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Nhúng navigation -->
    <?php include('nav.php'); ?>

    <div class="container mt-4">
        <h1 class="mb-4">TRANG SINH VIÊN</h1>
        
        <a href="add_student.php" class="btn btn-primary mb-3">Add Student</a>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>MaSV</th>
                    <th>Họ Tên</th>
                    <th>Giới Tính</th>
                    <th>Ngày Sinh</th>
                    <th>Hình</th>
                    <th>Ngành</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['MaSV'] . "</td>";
                        echo "<td>" . $row['HoTen'] . "</td>";
                        echo "<td>" . $row['GioiTinh'] . "</td>";
                        echo "<td>" . $row['NgaySinh'] . "</td>";
                        echo "<td><img src='" . $row['Hinh'] . "' alt='Ảnh sinh viên'></td>";
                        echo "<td>" . $row['TenNganh'] . "</td>";
                        echo "<td>
                                <a href='student_detail.php?MaSV=" . $row['MaSV'] . "' class='btn btn-sm btn-success'>Detail</a>
                                <a href='edit_student.php?MaSV=" . $row['MaSV'] . "' class='btn btn-sm btn-warning'>Edit</a>
                                <a href='delete_student.php?MaSV=" . $row['MaSV'] . "' class='btn btn-sm btn-danger'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
$conn->close();
?>
