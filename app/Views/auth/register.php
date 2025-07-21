<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // Tự động xác định base path
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đăng Ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Đăng Ký Tài Khoản</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        // Hiển thị thông báo lỗi nếu có
                        if (isset($_SESSION['error_message'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                            unset($_SESSION['error_message']); // Xóa thông báo sau khi hiển thị
                        }
                        ?>
                        <form action="<?= $base_path ?>/register" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Tên hiển thị</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Đăng Ký</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Đã có tài khoản? <a href="<?= $base_path ?>/login">Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>