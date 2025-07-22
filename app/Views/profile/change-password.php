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
    <title>Đổi Mật Khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .password-container {
            max-width: 550px;
            margin-top: 50px;
        }

        .password-card {
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
            padding: 15px 50px 15px 20px;
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

        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            z-index: 10;
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            padding: 2rem;
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
    </style>
</head>

<body>
    <div class="container password-container">
        <div class="password-card">
            <div class="card-header-modern text-center">
                <h2 class="mb-1">
                    <i class="fas fa-key me-3"></i>
                    Đổi Mật Khẩu
                </h2>
                <p class="mb-0 opacity-75">Cập nhật mật khẩu bảo mật của bạn</p>
            </div>

            <div class="card-body p-4">
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-modern">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= $_SESSION['error_message'];
                        unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= $base_path ?>/profile/change-password" id="passwordForm">
                    <div class="mb-4">
                        <label for="current_password" class="form-label form-label-modern">
                            <i class="fas fa-lock me-2"></i>Mật Khẩu Hiện Tại
                        </label>
                        <div class="password-field">
                            <input type="password" class="form-control form-control-modern" id="current_password"
                                name="current_password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="new_password" class="form-label form-label-modern">
                            <i class="fas fa-key me-2"></i>Mật Khẩu Mới
                        </label>
                        <div class="password-field">
                            <input type="password" class="form-control form-control-modern" id="new_password"
                                name="new_password" required oninput="checkPasswordStrength()">
                            <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Mật khẩu phải có ít nhất 6 ký tự
                        </small>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label form-label-modern">
                            <i class="fas fa-check-double me-2"></i>Xác Nhận Mật Khẩu
                        </label>
                        <div class="password-field">
                            <input type="password" class="form-control form-control-modern" id="confirm_password"
                                name="confirm_password" required oninput="checkPasswordMatch()">
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small id="passwordMatch" class="form-text"></small>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="<?= $base_path ?>/profile" class="btn btn-outline-modern">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                        <button type="submit" class="btn btn-modern" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Đổi Mật Khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('new_password').value;
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
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchText = document.getElementById('passwordMatch');

            if (confirmPassword === '') {
                matchText.textContent = '';
                return;
            }

            if (newPassword === confirmPassword) {
                matchText.textContent = '✓ Mật khẩu khớp';
                matchText.className = 'form-text text-success';
            } else {
                matchText.textContent = '✗ Mật khẩu không khớp';
                matchText.className = 'form-text text-danger';
            }
        }

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
            }

            if (newPassword.length < 6) {
                e.preventDefault();
                alert('Mật khẩu phải có ít nhất 6 ký tự!');
            }
        });
    </script>
</body>

</html>