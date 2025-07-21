<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
if ($base_path === '/' || $base_path === '\\') {
    $base_path = '';
}

// Kiểm tra xem người dùng đã đăng nhập chưa. Nếu chưa, chuyển hướng về trang login.
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $base_path . '/login');
    exit;
}

$displayName = $_SESSION['display_name'] ?? 'Guest';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trang Chủ - Bảng Điều Khiển</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .dashboard-container {
            max-width: 800px;
            margin-top: 50px;
        }

        .card-title {
            font-weight: bold;
        }

        .btn-custom {
            margin: 10px 0;
            padding: 15px;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <div class="container dashboard-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Chào mừng trở lại, <span class="text-primary"><?= htmlspecialchars($displayName) ?></span>!
            </h1>
            <a href="<?= $base_path ?>/logout" class="btn btn-outline-danger">Đăng xuất</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="card-title text-center mb-0">Dashboard</h4>
            </div>
            <div class="card-body">
                <p class="text-center text-muted">Chọn một hành động để bắt đầu.</p>
                <div class="d-grid gap-3">
                    <a href="<?= $base_path ?>/posts" class="btn btn-primary btn-lg btn-custom">
                        Khám phá bài viết của mọi người
                    </a>
                    <a href="<?= $base_path ?>/my-posts" class="btn btn-success btn-lg btn-custom">
                        Quản lý bài viết của tôi
                    </a>
                    <a href="<?= $base_path ?>/profile" class="btn btn-info btn-lg btn-custom text-white">
                        Xem thông tin cá nhân
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>