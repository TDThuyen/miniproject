<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
if ($base_path === '/' || $base_path === '\\') {
    $base_path = '';
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $base_path . '/login');
    exit;
}

$displayName = $_SESSION['display_name'] ?? 'Guest';
$username = $_SESSION['username'] ?? 'user';

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mini Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --info-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard-container {
        max-width: 1000px;
        margin-top: 30px;
        margin-bottom: 50px;
    }

    .welcome-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: none;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .welcome-header {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        margin: 0 auto 1rem;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .action-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        padding: 2rem;
    }

    .action-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        transition: all 0.3s ease;
    }

    .action-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .action-card.explore::before {
        background: var(--primary-gradient);
    }

    .action-card.manage::before {
        background: var(--success-gradient);
    }

    .action-card.profile::before {
        background: var(--info-gradient);
    }

    .action-card:hover::before {
        height: 100%;
        opacity: 0.05;
    }

    .card-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        color: white;
    }

    .explore .card-icon {
        background: var(--primary-gradient);
    }

    .manage .card-icon {
        background: var(--success-gradient);
    }

    .profile .card-icon {
        background: var(--info-gradient);
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .card-description {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .stats-row {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        text-align: center;
        color: white;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        display: block;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .logout-btn {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 2px solid rgba(220, 53, 69, 0.3);
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .logout-btn:hover {
        background: #dc3545;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
    }

    .floating-shapes {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
    }

    .shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 20s infinite linear;
    }

    .shape:nth-child(1) {
        width: 80px;
        height: 80px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .shape:nth-child(2) {
        width: 120px;
        height: 120px;
        top: 60%;
        right: 10%;
        animation-delay: 5s;
    }

    .shape:nth-child(3) {
        width: 60px;
        height: 60px;
        bottom: 20%;
        left: 20%;
        animation-delay: 10s;
    }

    @keyframes float {
        0% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(180deg);
        }

        100% {
            transform: translateY(0) rotate(360deg);
        }
    }

    @media (max-width: 768px) {
        .action-cards {
            grid-template-columns: 1fr;
            padding: 1rem;
        }

        .dashboard-container {
            margin-top: 20px;
            padding: 0 15px;
        }
    }
    </style>
</head>

<body>
    <!-- Floating Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container dashboard-container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="welcome-header">
                <div class="user-avatar">
                    <?= strtoupper(substr($displayName, 0, 1)) ?>
                </div>
                <h1 class="h3 mb-1">Chào mừng trở lại!</h1>
                <h2 class="h4 mb-0"><?= htmlspecialchars($displayName) ?></h2>
                <p class="mb-0 opacity-75">@<?= htmlspecialchars($username) ?></p>
            </div>

            <!-- Stats Row -->
            <div class="stats-row">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            <span class="stat-label">Hôm nay</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">
                                <i class="fas fa-fire"></i>
                            </span>
                            <span class="stat-label">Đang hoạt động</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <a href="<?= $base_path ?>/logout" class="logout-btn text-decoration-none">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="action-cards">
                <a href="<?= $base_path ?>/posts" class="action-card explore">
                    <div class="card-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3 class="card-title">Khám Phá Bài Viết</h3>
                    <p class="card-description">
                        Tìm hiểu những bài viết thú vị từ cộng đồng. Khám phá nội dung mới và kết nối với những người có
                        cùng sở thích.
                    </p>
                </a>

                <a href="<?= $base_path ?>/my-posts" class="action-card manage">
                    <div class="card-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h3 class="card-title">Quản Lý Bài Viết</h3>
                    <p class="card-description">
                        Tạo, chỉnh sửa và quản lý tất cả bài viết của bạn. Chia sẻ suy nghĩ và kinh nghiệm với mọi
                        người.
                    </p>
                </a>

                <a href="<?= $base_path ?>/profile" class="action-card profile">
                    <div class="card-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3 class="card-title">Thông Tin Cá Nhân</h3>
                    <p class="card-description">
                        Cập nhật thông tin tài khoản, thay đổi mật khẩu và cá nhân hóa hồ sơ của bạn.
                    </p>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Smooth animations
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.action-card');

        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 200);
        });
    });
    </script>
</body>

</html>