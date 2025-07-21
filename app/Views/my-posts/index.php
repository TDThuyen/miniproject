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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Bài Viết Của Tôi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Bài Viết Của Tôi</h1>
            <div>
                <a href="<?= $base_path ?>/my-posts/create" class="btn btn-primary">Tạo bài viết mới</a>
                <a href="<?= $base_path ?>/dashboard" class="btn btn-secondary">Quay lại Dashboard</a>
            </div>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_message'];
                                                unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>

        <div class="list-group">
            <?php if (empty($posts)): ?>
                <p class="text-center">Bạn chưa có bài viết nào. Hãy tạo một bài viết mới!</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <a href="<?= $base_path ?>/posts/<?= $post['id'] ?>" class="text-decoration-none text-dark">
                                <h5 class="mb-1"><?= htmlspecialchars($post['title']) ?></h5>
                            </a>
                            <small>Tạo lúc: <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></small>
                        </div>
                        <div>
                            <a href="<?= $base_path ?>/my-posts/edit/<?= $post['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>

                            <form action="<?= $base_path ?>/my-posts/delete" method="POST"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');" style="display: inline;">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const alertElement = document.querySelector('.alert');

        if (alertElement) {
            setTimeout(() => {
                alertElement.style.transition = 'opacity 0.5s ease';
                alertElement.style.opacity = '0';
                setTimeout(() => alertElement.remove(), 500);
            }, 3000);
        }
    </script>
</body>

</html>