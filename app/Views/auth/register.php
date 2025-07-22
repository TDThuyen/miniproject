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
    <title>Đăng Ký Tài Khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }

        .auth-container {
            max-width: 450px;
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

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 5px;
            background: #e9ecef;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            width: 0%;
        }

        .strength-weak {
            background: #dc3545;
            width: 25%;
        }

        .strength-fair {
            background: #ffc107;
            width: 50%;
        }

        .strength-good {
            background: #17a2b8;
            width: 75%;
        }

        .strength-strong {
            background: #28a745;
            width: 100%;
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
    </style>
</head>

<body>
    <!-- Floating Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 class="mb-1">Tạo Tài Khoản</h2>
                    <p class="mb-0 opacity-75">Tham gia cộng đồng của chúng tôi</p>
                </div>

                <div class="card-body p-4">
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger alert-modern"><i class="fas fa-exclamation-circle me-2"></i>' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']);
                    }
                    ?>

                    <form action="<?= $base_path ?>/register" method="POST" id="registerForm">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Tên đăng nhập" required>
                            <label for="username"><i class="fas fa-user me-2"></i>Tên đăng nhập</label>
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control" id="display_name" name="display_name"
                                placeholder="Tên hiển thị" required>
                            <label for="display_name"><i class="fas fa-id-badge me-2"></i>Tên hiển thị</label>
                        </div>

                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                required>
                            <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Mật khẩu" required oninput="checkPasswordStrength()">
                            <label for="password"><i class="fas fa-lock me-2"></i>Mật khẩu</label>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="strengthBar"></div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="Xác nhận mật khẩu" required oninput="checkPasswordMatch()">
                            <label for="confirm_password"><i class="fas fa-check-double me-2"></i>Xác nhận mật
                                khẩu</label>
                            <small id="passwordMatch" class="form-text"></small>
                        </div>

                        <button type="submit" class="btn btn-auth">
                            <i class="fas fa-user-plus me-2"></i>Đăng Ký
                        </button>
                    </form>
                </div>

                <div class="auth-footer">
                    Đã có tài khoản?
                    <a href="<?= $base_path ?>/login" class="auth-link">
                        Đăng nhập ngay <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');

            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthBar.className = 'password-strength-bar';
            if (strength >= 1) strengthBar.classList.add('strength-weak');
            if (strength >= 2) strengthBar.classList.add('strength-fair');
            if (strength >= 3) strengthBar.classList.add('strength-good');
            if (strength >= 4) strengthBar.classList.add('strength-strong');
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchText = document.getElementById('passwordMatch');

            if (confirmPassword === '') {
                matchText.textContent = '';
                return;
            }

            if (password === confirmPassword) {
                matchText.textContent = '✓ Mật khẩu khớp';
                matchText.className = 'form-text text-success';
            } else {
                matchText.textContent = '✗ Mật khẩu không khớp';
                matchText.className = 'form-text text-danger';
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
            }
        });
    </script>
</body>

</html>