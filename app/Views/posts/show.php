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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .post-content {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .post-content img {
            /* Đảm bảo ảnh không bị tràn ra ngoài */
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if ($post): ?>
                    <article>
                        <h1 class="mb-3"><?= htmlspecialchars($post['title']) ?></h1>
                        <p class="text-muted">
                            Tác giả: <strong><?= htmlspecialchars($post['display_name']) ?></strong> |
                            Đăng ngày <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                        </p>
                        <hr>
                        <div class="post-content">
                            <?= $purifier->purify($post['content']) ?>
                        </div>
                    </article>
                    <hr>
                    <a href="javascript:history.back()" class="btn btn-secondary">Quay lại</a>
                <?php else: ?>
                    <div class="alert alert-danger text-center">
                        <h2>404 - Not Found</h2>
                        <p>Bài viết bạn tìm kiếm không tồn tại.</p>
                        <a href="<?= $base_path ?>/dashboard" class="btn btn-primary">Về trang chủ</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>