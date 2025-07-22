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
    <title>Chỉnh Sửa Thông Tin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .edit-container {
        max-width: 600px;
        margin-top: 50px;
    }

    .edit-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
    }

    .form-control-modern {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 15px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(102, 126, 234, 0.02);
    }

    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 20px rgba(102, 126, 234, 0.15);
        background: rgba(102, 126, 234, 0.05);
    }

    .form-label-modern {
        color: #667eea;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
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
        border: 2px solid #6c757d;
        border-radius: 50px;
        padding: 10px 25px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-outline-modern:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }

    .alert-modern {
        border-radius: 15px;
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .card-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 2rem;
    }
    </style>
</head>

<body>
    <div class="container edit-container">
        <div class="edit-card">
            <div class="card-header-modern text-center">
                <h2 class="mb-1">
                    <i class="fas fa-user-edit me-3"></i>
                    Chỉnh Sửa Thông Tin
                </h2>
                <p class="mb-0 opacity-75">Cập nhật thông tin cá nhân của bạn</p>
            </div>

            <div class="card-body p-4">
                <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-modern">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $_SESSION['error_message'];
                        unset($_SESSION['error_message']); ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="<?= $base_path ?>/profile/edit" id="editForm">
                    <div class="mb-4">
                        <label for="username" class="form-label form-label-modern">
                            <i class="fas fa-user me-2"></i>Tên Đăng Nhập
                        </label>
                        <input type="text" class="form-control form-control-modern" id="username" name="username"
                            value="<?= htmlspecialchars($userData['username']) ?>" required>
                        <div class="form-text mt-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Tên đăng nhập phải là duy nhất và có thể thay đổi.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="display_name" class="form-label form-label-modern">
                            <i class="fas fa-id-badge me-2"></i>Tên Hiển Thị
                        </label>
                        <input type="text" class="form-control form-control-modern" id="display_name"
                            name="display_name" value="<?= htmlspecialchars($userData['display_name']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label form-label-modern">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control form-control-modern" id="email" name="email"
                            value="<?= htmlspecialchars($userData['email']) ?>" required>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="<?= $base_path ?>/profile" class="btn btn-outline-modern">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                        <button type="submit" class="btn btn-modern">
                            <i class="fas fa-save me-2"></i>Lưu Thay Đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Form validation
    document.getElementById('editForm').addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const displayName = document.getElementById('display_name').value.trim();
        const email = document.getElementById('email').value.trim();

        if (!username || !displayName || !email) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin!');
        }
    });
    </script>
</body>

</html>