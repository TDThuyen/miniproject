<?php

require_once 'C:\xampp\htdocs\miniproject\app\Models\User.php';

class AuthController
{

    public function showRegistrationForm()
    {
        require_once 'C:\xampp\htdocs\miniproject\app\Views\auth\register.php';
    }

    public function register()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $display_name = $_POST['display_name'];

            $base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
            if ($base_path === '/' || $base_path === '\\') {
                $base_path = '';
            }

            if (empty($username) || empty($email) || empty($password) || empty($display_name)) {
                $_SESSION['error_message'] = "Vui lòng điền đầy đủ thông tin.";
                header('Location: ' . $base_path . '/register');
                exit;
            }

            if ($password !== $confirm_password) {
                $_SESSION['error_message'] = "Mật khẩu xác nhận không khớp.";
                header('Location: ' . $base_path . '/register');
                exit;
            }

            if ($userModel->findByUsernameOrEmail($username, $email)) {
                $_SESSION['error_message'] = "Tên đăng nhập hoặc email đã tồn tại.";
                header('Location: ' . $base_path . '/register');
                exit;
            }

            $data = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'display_name' => $display_name
            ];

            if ($userModel->register($data)) {
                $_SESSION['success_message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                header('Location: ' . $base_path . '/login');
                exit;
            } else {
                $_SESSION['error_message'] = "Đã có lỗi xảy ra. Vui lòng thử lại.";
                header('Location: ' . $base_path . '/register');
                exit;
            }
        }
    }

    public function showLoginForm()
    {
        require_once 'C:\xampp\htdocs\miniproject\app\Views\auth\login.php';
    }

    public function login()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $userModel->attemptLogin($username, $password);

            $base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
            if ($base_path === '/' || $base_path === '\\') {
                $base_path = '';
            }

            if ($user) {
                $this->establishUserSession($user);

                if (!empty($_POST['remember'])) {
                    $this->handleRememberMe($user['id']);
                }

                header('Location: ' . $base_path . '/dashboard');
                exit;
            } else {
                $_SESSION['error_message'] = "Tên đăng nhập hoặc mật khẩu không đúng.";
                header('Location: ' . $base_path . '/login');
                exit;
            }
        }
    }

    public function logout()
    {
        session_start();

        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $userModel = new User();
            $userModel->clearRememberToken($token);
            setcookie('remember_me', '', time() - 3600, '/'); // Xóa cookie
        }

        session_unset();
        session_destroy();

        $base_path = dirname($_SERVER['SCRIPT_NAME']);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }
        header('Location: ' . $base_path . '/login');
        exit;
    }

    /**
     * Kiểm tra cookie "remember_me" và tự động đăng nhập nếu hợp lệ.
     */
    public function checkRememberMe()
    {
        // Nếu đã đăng nhập bằng session rồi thì thôi
        if (isset($_SESSION['user_id'])) {
            return;
        }

        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $userModel = new User();
            $user = $userModel->findUserByRememberToken($token);

            if ($user) {
                // Đăng nhập thành công bằng token
                $this->establishUserSession($user);
                // Tạo lại token mới để tăng bảo mật (token rotation)
                $this->handleRememberMe($user['id']);
            }
        }
    }

    /**
     * Thiết lập session cho người dùng.
     */
    private function establishUserSession($user)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['display_name'] = $user['display_name'];
    }

    /**
     * Tạo và lưu token "remember_me".
     */
    private function handleRememberMe($userId)
    {
        $userModel = new User();
        // Xóa token cũ nếu có để đảm bảo mỗi user chỉ có 1 token
        if (isset($_COOKIE['remember_me'])) {
            $userModel->clearRememberToken($_COOKIE['remember_me']);
        }

        // Tạo token mới
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30); // 30 ngày

        // Lưu token vào DB
        $userModel->storeRememberToken($userId, $token, $expires_at);

        // Đặt cookie cho trình duyệt
        setcookie('remember_me', $token, time() + 60 * 60 * 24 * 30, '/');
    }
}
