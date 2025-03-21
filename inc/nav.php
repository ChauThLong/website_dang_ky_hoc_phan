<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <!-- Logo / Tên dự án (tăng kích thước với fs-3 hoặc fs-4) -->
    <a class="navbar-brand fs-3" href="index.php">Test1</a>

    <!-- Nút toggle trên màn hình nhỏ -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nội dung thanh nav -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Menu bên trái -->
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Sinh Viên</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="hocphan.php">Học Phần</a>
        </li>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <li class="nav-item">
                <a class="nav-link" href="hp_da_dangky.php">Học phần đã chọn</a>
            </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="dangky.php">Đăng Ký</a>
        </li>
        <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
          <li class="nav-item">
            <a class="nav-link" href="dangnhap.php">Đăng Nhập</a>
          </li>
        <?php endif; ?>
      </ul>

      <!-- Menu bên phải: chỉ hiển thị nếu đã đăng nhập -->
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
      <ul class="navbar-nav d-flex align-items-center">
        <li class="nav-item me-3">
          <span class="navbar-text">
            Xin chào, <?php echo $_SESSION['HoTen'] ?? ''; ?>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="dangxuat.php">Đăng Xuất</a>
        </li>
      </ul>
      <?php endif; ?>
    </div>
  </div>
</nav>
