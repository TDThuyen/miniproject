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
    <title>Chỉnh Sửa Bài Viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3>Chỉnh Sửa Bài Viết</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger">
                                <?= $_SESSION['error_message'];
                                unset($_SESSION['error_message']); ?></div>
                        <?php endif; ?>

                        <form action="<?= $base_path ?>/my-posts/update" method="POST">
                            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">

                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?= htmlspecialchars($post['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Nội dung</label>
                                <textarea class="form-control" id="content" name="content" rows="10"
                                    required><?= htmlspecialchars($post['content']) ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="<?= $base_path ?>/my-posts" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        CKEDITOR.replace('content');
    </script>
</body>

</html>