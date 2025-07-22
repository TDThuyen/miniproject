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
    <title>Chỉnh Sửa Bài Viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }

        .edit-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .edit-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .edit-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .edit-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .form-container {
            padding: 2.5rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-label-modern {
            color: #f093fb;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control-modern {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(240, 147, 251, 0.02);
        }

        .form-control-modern:focus {
            border-color: #f093fb;
            box-shadow: 0 0 20px rgba(240, 147, 251, 0.15);
            background: rgba(240, 147, 251, 0.05);
        }

        /* Không áp dụng style cho CKEditor container */
        .ckeditor-wrapper {
            margin-top: 10px;
        }

        .btn-modern {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.3);
        }

        .btn-warning-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(240, 147, 251, 0.4);
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

        .btn-info-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-info-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .alert-modern {
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            margin-bottom: 2rem;
        }

        .form-actions {
            padding: 2rem;
            background: rgba(240, 147, 251, 0.05);
            border-top: 1px solid rgba(240, 147, 251, 0.1);
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .post-info {
            background: rgba(240, 147, 251, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid #f093fb;
        }

        .post-meta {
            display: flex;
            gap: 20px;
            color: #6c757d;
            font-size: 0.9rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
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
            background: rgba(240, 147, 251, 0.1);
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
            animation-delay: 5s;
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
            .form-container {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .post-meta {
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

    <div class="container edit-container">
        <div class="edit-card">
            <!-- Header -->
            <div class="edit-header">
                <div class="edit-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h1 class="h2 mb-1">Chỉnh Sửa Bài Viết</h1>
                <p class="mb-0 opacity-75">Cập nhật và hoàn thiện nội dung của bạn</p>
            </div>

            <!-- Form -->
            <div class="form-container">
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-modern">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= $_SESSION['error_message'];
                        unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Info -->
                <div class="post-info">
                    <h6 class="mb-2" style="color: #f093fb; font-weight: 600;">
                        <i class="fas fa-info-circle me-2"></i>Thông tin bài viết
                    </h6>
                    <div class="post-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Tạo: <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
                        </div>
                        <?php if ($post['updated_at'] && $post['updated_at'] !== $post['created_at']): ?>
                            <div class="meta-item">
                                <i class="fas fa-calendar-edit"></i>
                                <span>Sửa: <?= date('d/m/Y H:i', strtotime($post['updated_at'])) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="meta-item">
                            <i class="fas fa-hashtag"></i>
                            <span>ID: #<?= $post['id'] ?></span>
                        </div>
                    </div>
                </div>

                <form action="<?= $base_path ?>/my-posts/update" method="POST" id="editForm">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">

                    <div class="form-group">
                        <label for="title" class="form-label-modern">
                            <i class="fas fa-heading"></i>
                            Tiêu đề bài viết
                        </label>
                        <input type="text" class="form-control form-control-modern" id="title" name="title"
                            value="<?= htmlspecialchars($post['title']) ?>"
                            placeholder="Nhập tiêu đề hấp dẫn cho bài viết của bạn..." required>
                    </div>

                    <div class="form-group">
                        <label for="content" class="form-label-modern">
                            <i class="fas fa-edit"></i>
                            Nội dung bài viết
                        </label>
                        <div class="ckeditor-wrapper">
                            <textarea id="content" name="content"
                                required><?= htmlspecialchars($post['content']) ?></textarea>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-lightbulb me-1"></i>
                            Hãy tạo những thay đổi tích cực để bài viết của bạn thêm hấp dẫn.
                        </small>
                    </div>
                </form>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="submit" form="editForm" class="btn btn-warning-modern btn-modern" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Cập Nhật Bài Viết
                </button>
                <a href="<?= $base_path ?>/posts/<?= $post['id'] ?>" class="btn btn-info-modern btn-modern">
                    <i class="fas fa-eye"></i>
                    Xem Trước
                </a>
                <a href="<?= $base_path ?>/my-posts" class="btn btn-outline-modern btn-modern">
                    <i class="fas fa-arrow-left"></i>
                    Quay Lại
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        // Initialize CKEditor with proper configuration
        CKEDITOR.replace('content', {
            height: 400,
            width: '100%',
            toolbar: [{
                    name: 'document',
                    items: ['Source', '-', 'Preview']
                },
                {
                    name: 'clipboard',
                    items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                },
                {
                    name: 'editing',
                    items: ['Find', 'Replace']
                },
                '/',
                {
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
                },
                {
                    name: 'links',
                    items: ['Link', 'Unlink']
                },
                {
                    name: 'insert',
                    items: ['Image', 'Table', 'HorizontalRule']
                },
                '/',
                {
                    name: 'styles',
                    items: ['Format', 'Font', 'FontSize']
                },
                {
                    name: 'colors',
                    items: ['TextColor', 'BGColor']
                },
                {
                    name: 'tools',
                    items: ['Maximize']
                }
            ],
            removeDialogTabs: 'image:advanced;link:advanced',
            filebrowserImageBrowseUrl: '',
            filebrowserImageUploadUrl: '',
            allowedContent: true,
            extraAllowedContent: 'img(*)[*]; div(*)[*]; span(*)[*]; p(*)[*]; h1(*)[*]; h2(*)[*]; h3(*)[*]; h4(*)[*]; h5(*)[*]; h6(*)[*]'
        });

        // Form validation and submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const content = CKEDITOR.instances.content.getData().trim();
            const submitBtn = document.getElementById('submitBtn');

            if (!title || !content) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ tiêu đề và nội dung!');
                return;
            }

            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
            submitBtn.disabled = true;
        });

        // Track changes for unsaved warning
        let hasUnsavedChanges = false;
        let originalTitle = document.getElementById('title').value;
        let originalContent = '';

        CKEDITOR.instances.content.on('instanceReady', function() {
            originalContent = CKEDITOR.instances.content.getData();

            CKEDITOR.instances.content.on('change', function() {
                hasUnsavedChanges = true;
            });
        });

        document.getElementById('title').addEventListener('input', function() {
            hasUnsavedChanges = (this.value !== originalTitle);
        });

        // Warn before leaving with unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = '';
                return 'Bạn có thay đổi chưa được lưu. Bạn có chắc muốn rời khỏi trang?';
            }
        });

        // Reset unsaved changes flag on successful submission
        document.getElementById('editForm').addEventListener('submit', function() {
            hasUnsavedChanges = false;
        });

        // Animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.edit-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            setTimeout(() => {
                card.style.transition = 'all 0.8s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200);
        });

        // Auto-save functionality
        let autoSaveInterval;

        function startAutoSave() {
            autoSaveInterval = setInterval(() => {
                const title = document.getElementById('title').value;
                const content = CKEDITOR.instances.content.getData();

                // Save to localStorage with post ID
                localStorage.setItem(`edit_draft_${<?= $post['id'] ?>}_title`, title);
                localStorage.setItem(`edit_draft_${<?= $post['id'] ?>}_content`, content);

                // Show subtle save indicator
                const saveIndicator = document.createElement('div');
                saveIndicator.textContent = '✓ Đã tự động lưu nháp';
                saveIndicator.style.position = 'fixed';
                saveIndicator.style.top = '20px';
                saveIndicator.style.right = '20px';
                saveIndicator.style.background = 'rgba(40, 167, 69, 0.9)';
                saveIndicator.style.color = 'white';
                saveIndicator.style.padding = '8px 15px';
                saveIndicator.style.borderRadius = '5px';
                saveIndicator.style.zIndex = '9999';
                saveIndicator.style.fontSize = '14px';

                document.body.appendChild(saveIndicator);
                setTimeout(() => saveIndicator.remove(), 3000);
            }, 30000); // Auto-save every 30 seconds
        }

        // Start auto-save after CKEditor is ready
        CKEDITOR.instances.content.on('instanceReady', function() {
            startAutoSave();
        });

        // Clear auto-save interval when leaving
        window.addEventListener('beforeunload', function() {
            if (autoSaveInterval) {
                clearInterval(autoSaveInterval);
            }
        });

        // Load saved draft if exists
        window.addEventListener('load', function() {
            const savedTitle = localStorage.getItem(`edit_draft_${<?= $post['id'] ?>}_title`);
            const savedContent = localStorage.getItem(`edit_draft_${<?= $post['id'] ?>}_content`);

            if (savedTitle && savedTitle !== document.getElementById('title').value) {
                if (confirm('Có nháp được lưu trước đó. Bạn có muốn khôi phục không?')) {
                    document.getElementById('title').value = savedTitle;
                    if (savedContent && CKEDITOR.instances.content) {
                        CKEDITOR.instances.content.setData(savedContent);
                    }
                }
            }
        });

        // Clear draft after successful submission
        document.getElementById('editForm').addEventListener('submit', function() {
            localStorage.removeItem(`edit_draft_${<?= $post['id'] ?>}_title`);
            localStorage.removeItem(`edit_draft_${<?= $post['id'] ?>}_content`);
        });
    </script>
</body>

</html>