<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        align-items: center;
    }

    .auth-container {
        max-width: 420px;
        margin: 0 auto;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
    }

    .auth-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem 2rem;
        text-align: center;
    }

    .auth-logo {
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

    .form-floating {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 1rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(102, 126, 234, 0.02);
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 20px rgba(102, 126, 234, 0.15);
        background: rgba(102, 126, 234, 0.05);
    }

    .form-floating>label {
        color: #667eea;
        font-weight: 500;
    }

    .form-check {
        margin-bottom: 1.5rem;
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .form-check-label {
        color: #6c757d;
        font-weight: 500;
    }

    .btn-auth {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        width: 100%;
    }

    .btn-auth:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .auth-footer {
        background: rgba(102, 126, 234, 0.05);
        padding: 1.5rem 2rem;
        text-align: center;
        border-top: 1px solid rgba(102, 126, 234, 0.1);
    }

    .auth-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .auth-link:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .alert-modern {
        border-radius: 15px;
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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

    .welcome-text {
        opacity: 0;
        animation: fadeInUp 1s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
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

    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <h2 class="mb-1 welcome-text">Chào Mừng Trở Lại!</h2>
                    <p class="mb-0 opacity-75 welcome-text" style="animation-delay: 0.2s;">Đăng nhập để tiếp tục</p>
                </div>

                <div class="card-body p-4">
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger alert-modern"><i class="fas fa-exclamation-circle me-2"></i>' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']);
                    }
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success alert-modern"><i class="fas fa-check-circle me-2"></i>' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']);
                    }
                    ?>

                    <form action="<?= $base_path ?>/login" method="POST">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Tên đăng nhập" required>
                            <label for="username"><i class="fas fa-user me-2"></i>Tên đăng nhập</label>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Mật khẩu" required>
                            <label for="password"><i class="fas fa-lock me-2"></i>Mật khẩu</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                <i class="fas fa-heart me-2" style="color: #e74c3c;"></i>Ghi nhớ đăng nhập
                            </label>
                        </div>

                        <button type="submit" class="btn btn-auth">
                            <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập
                        </button>
                    </form>
                </div>

                <div class="auth-footer">
                    Chưa có tài khoản?
                    <a href="<?= $base_path ?>/register" class="auth-link">
                        Đăng ký ngay <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Add loading animation on form submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const submitBtn = document.querySelector('.btn-auth');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
        submitBtn.disabled = true;
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
    </script>
</body>

</html>