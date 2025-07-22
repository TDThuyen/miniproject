<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
if ($base_path === '/' || $base_path === '\\') {
    $base_path = '';
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Cá Nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .profile-container {
        max-width: 900px;
        margin-top: 30px;
    }

    .profile-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .profile-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
    }

    .avatar-container {
        position: relative;
        display: inline-block;
    }

    .avatar-placeholder {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        border: 4px solid rgba(255, 255, 255, 0.2);
    }

    .status-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 20px;
        height: 20px;
        background: #28a745;
        border-radius: 50%;
        border: 3px solid white;
    }

    .info-item {
        background: rgba(102, 126, 234, 0.05);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
    }

    .info-label {
        color: #667eea;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .info-value {
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .btn-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-outline-modern {
        background: transparent;
        border: 2px solid #667eea;
        border-radius: 50px;
        padding: 10px 25px;
        color: #667eea;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-outline-modern:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .alert-modern {
        border-radius: 15px;
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: white;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    </style>
</head>

<body>
    <div class="container profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1" style="color: #2c3e50;">
                        <i class="fas fa-user-circle me-3" style="color: #667eea;"></i>
                        Thông Tin Cá Nhân
                    </h1>
                    <p class="text-muted mb-0">Quản lý thông tin tài khoản của bạn</p>
                </div>
                <a href="<?= $base_path ?>/dashboard" class="btn btn-outline-modern">
                    <i class="fas fa-arrow-left me-2"></i>Dashboard
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-modern">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-modern">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <!-- Avatar Card -->
                <div class="profile-card p-4 text-center mb-4">
                    <div class="avatar-container mb-3">
                        <div class="avatar-placeholder mx-auto">
                            <?= strtoupper(substr($userData['display_name'] ?: $userData['username'], 0, 1)) ?>
                        </div>
                        <div class="status-badge"></div>
                    </div>
                    <h4 class="mb-1" style="color: #2c3e50;">
                        <?= htmlspecialchars($userData['display_name'] ?: $userData['username']) ?>
                    </h4>
                    <p class="text-muted mb-3">@<?= htmlspecialchars($userData['username']) ?></p>

                    <div class="d-grid gap-2">
                        <a href="<?= $base_path ?>/profile/edit" class="btn btn-modern">
                            <i class="fas fa-edit me-2"></i>Chỉnh Sửa
                        </a>
                        <a href="<?= $base_path ?>/profile/change-password" class="btn btn-outline-modern">
                            <i class="fas fa-key me-2"></i>Đổi Mật Khẩu
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Info Card -->
                <div class="profile-card p-4">
                    <h5 class="mb-4" style="color: #667eea;">
                        <i class="fas fa-info-circle me-2"></i>Chi Tiết Thông Tin
                    </h5>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user me-2"></i>Tên Đăng Nhập
                        </div>
                        <div class="info-value"><?= htmlspecialchars($userData['username']) ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-id-badge me-2"></i>Tên Hiển Thị
                        </div>
                        <div class="info-value"><?= htmlspecialchars($userData['display_name']) ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </div>
                        <div class="info-value"><?= htmlspecialchars($userData['email']) ?></div>
                    </div>

                    <?php if (!empty($userData['created_at'])): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt me-2"></i>Ngày Tham Gia
                        </div>
                        <div class="info-value"><?= date('d/m/Y H:i', strtotime($userData['created_at'])) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>