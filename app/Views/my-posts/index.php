<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = dirname($_SERVER['SCRIPT_NAME']);
if ($base_path === '/' || $base_path === '\\') {
    $base_path = '';
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $base_path . '/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Bài Viết Của Tôi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }

        .posts-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .header-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
        }

        .post-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .post-header {
            padding: 1.5rem 2rem 1rem;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }

        .post-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .post-title:hover {
            color: #667eea;
        }

        .post-meta {
            color: #6c757d;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .post-actions {
            padding: 1rem 2rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-modern {
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }

        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
            color: white;
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
        }

        .btn-warning-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
            color: white;
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .btn-danger-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
            color: white;
        }

        .btn-outline-modern {
            background: transparent;
            border: 2px solid #6c757d;
            color: #6c757d;
        }

        .btn-outline-modern:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: #dee2e6;
        }

        .alert-modern {
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .post-stats {
            display: flex;
            gap: 20px;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        @media (max-width: 768px) {
            .post-actions {
                flex-direction: column;
                gap: 10px;
                align-items: stretch;
            }

            .btn-group {
                width: 100%;
                display: flex;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container posts-container">
        <!-- Header -->
        <div class="header-card">
            <div class="header-content">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1">
                            <i class="fas fa-newspaper me-3"></i>
                            Quản Lý Bài Viết
                        </h1>
                        <p class="mb-0 opacity-75">Tạo và quản lý các bài viết của bạn</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= $base_path ?>/my-posts/create" class="btn btn-success-modern btn-modern">
                            <i class="fas fa-plus"></i>
                            Tạo Bài Viết
                        </a>
                        <a href="<?= $base_path ?>/dashboard" class="btn btn-outline-modern btn-modern">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-modern">
                <i class="fas fa-check-circle me-2"></i>
                <?= $_SESSION['success_message'];
                unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Posts List -->
        <?php if (empty($posts)): ?>
            <div class="post-card">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Chưa có bài viết nào</h3>
                    <p class="mb-4">Hãy bắt đầu chia sẻ câu chuyện của bạn với thế giới!</p>
                    <a href="<?= $base_path ?>/my-posts/create" class="btn btn-primary-modern btn-modern">
                        <i class="fas fa-plus me-2"></i>
                        Tạo Bài Viết Đầu Tiên
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <div class="post-header">
                        <a href="<?= $base_path ?>/posts/<?= $post['id'] ?>" class="post-title">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                        <div class="post-meta">
                            <div class="stat-item">
                                <i class="fas fa-calendar-alt"></i>
                                <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>Xem chi tiết</span>
                            </div>
                        </div>
                    </div>
                    <div class="post-actions">
                        <div class="post-stats">
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                Đã tạo <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="<?= $base_path ?>/posts/<?= $post['id'] ?>" class="btn btn-primary-modern btn-modern">
                                <i class="fas fa-eye"></i>
                                Xem
                            </a>
                            <a href="<?= $base_path ?>/my-posts/edit/<?= $post['id'] ?>"
                                class="btn btn-warning-modern btn-modern">
                                <i class="fas fa-edit"></i>
                                Sửa
                            </a>
                            <form action="<?= $base_path ?>/my-posts/delete" method="POST" style="display: inline;"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button type="submit" class="btn btn-danger-modern btn-modern">
                                    <i class="fas fa-trash"></i>
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            setTimeout(() => {
                alertElement.style.transition = 'opacity 0.5s ease';
                alertElement.style.opacity = '0';
                setTimeout(() => alertElement.remove(), 500);
            }, 4000);
        }

        // Animate cards on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.post-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>