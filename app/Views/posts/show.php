<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_path = dirname($_SERVER['SCRIPT_NAME']);
if ($base_path === '/' || $base_path === '\\') {
    $base_path = '';
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title'] ?? 'Bài viết không tồn tại') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }

        .article-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .article-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .article-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .article-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .article-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            opacity: 0.9;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .article-content {
            padding: 2.5rem;
        }

        .post-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #2c3e50;
        }

        .post-content p {
            margin-bottom: 1.5rem;
        }

        .post-content h1,
        .post-content h2,
        .post-content h3,
        .post-content h4,
        .post-content h5,
        .post-content h6 {
            color: #667eea;
            font-weight: 600;
            margin: 2rem 0 1rem;
        }

        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            margin: 1.5rem 0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .post-content blockquote {
            border-left: 4px solid #667eea;
            background: rgba(102, 126, 234, 0.05);
            padding: 1.5rem;
            border-radius: 0 15px 15px 0;
            margin: 1.5rem 0;
            font-style: italic;
        }

        .post-content code {
            background: rgba(102, 126, 234, 0.1);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.9em;
        }

        .post-content pre {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            border-left: 4px solid #667eea;
            overflow-x: auto;
        }

        .article-footer {
            background: rgba(102, 126, 234, 0.05);
            padding: 2rem;
            text-align: center;
            border-top: 1px solid rgba(102, 126, 234, 0.1);
        }

        .btn-modern {
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
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

        .error-card {
            text-align: center;
            padding: 4rem 2rem;
        }

        .error-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1.5rem;
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
        }

        .shape:nth-child(2) {
            width: 60px;
            height: 60px;
            bottom: 30%;
            right: 15%;
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
            .article-title {
                font-size: 1.5rem;
            }

            .article-content {
                padding: 1.5rem;
            }

            .article-meta {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Floating Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container article-container">
        <div class="article-card">
            <?php if ($post): ?>
                <!-- Header -->
                <div class="article-header">
                    <h1 class="article-title"><?= htmlspecialchars($post['title']) ?></h1>
                    <div class="article-meta">
                        <div class="meta-item">
                            <div class="author-avatar">
                                <?= strtoupper(substr($post['display_name'], 0, 1)) ?>
                            </div>
                            <span><strong><?= htmlspecialchars($post['display_name']) ?></strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>5 phút đọc</span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="article-content">
                    <div class="post-content">
                        <?= $purifier->purify($post['content']) ?>
                    </div>
                </div>

                <!-- Footer -->
                <div class="article-footer">
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <button onclick="history.back()" class="btn btn-outline-modern btn-modern">
                            <i class="fas fa-arrow-left"></i>
                            Quay Lại
                        </button>
                        <a href="<?= $base_path ?>/my-posts" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-list"></i>
                            Tất Cả Bài Viết
                        </a>
                        <a href="<?= $base_path ?>/dashboard" class="btn btn-outline-modern btn-modern">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Error State -->
                <div class="error-card">
                    <div class="error-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h2 class="text-danger mb-3">Oops! Không tìm thấy bài viết</h2>
                    <p class="text-muted mb-4">Bài viết bạn đang tìm kiếm có thể đã bị xóa hoặc không tồn tại.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <button onclick="history.back()" class="btn btn-outline-modern btn-modern">
                            <i class="fas fa-arrow-left"></i>
                            Quay Lại
                        </button>
                        <a href="<?= $base_path ?>/dashboard" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-home"></i>
                            Về Trang Chủ
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.article-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            setTimeout(() => {
                card.style.transition = 'all 0.8s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200);
        });

        // Reading progress indicator
        window.addEventListener('scroll', function() {
            const article = document.querySelector('.post-content');
            if (!article) return;

            const articleHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrollTop = window.scrollY;
            const articleTop = article.offsetTop;

            const progress = Math.min(100, Math.max(0,
                (scrollTop - articleTop + windowHeight) / (articleHeight + windowHeight) * 100
            ));

            // You can use this progress value to show a reading progress bar
        });
    </script>
</body>

</html>