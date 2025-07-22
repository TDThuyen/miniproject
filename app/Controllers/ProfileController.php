<?php
require_once 'C:\xampp\htdocs\miniproject\app\Models\User.php';

class ProfileController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base_path . '/login');
            exit;
        }

        $userModel = new User();
        $userData = $userModel->findById($_SESSION['user_id']);

        if (!$userData) {
            $_SESSION['error_message'] = 'Không tìm thấy thông tin người dùng.';
            header('Location: ' . $base_path . '/dashboard');
            exit;
        }

        require_once 'C:\xampp\htdocs\miniproject\app\Views\profile\index.php';
    }

    public function edit()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base_path . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile();
            return;
        }

        $userModel = new User();
        $userData = $userModel->findById($_SESSION['user_id']);

        require_once 'C:\xampp\htdocs\miniproject\app\Views\profile\edit.php';
    }

    public function changePassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base_path . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updatePassword();
            return;
        }

        require_once 'C:\xampp\htdocs\miniproject\app\Views\profile\change-password.php';
    }

    private function updateProfile()
    {
        $base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }

        $username = trim($_POST['username'] ?? '');
        $display_name = trim($_POST['display_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($username) || empty($display_name) || empty($email)) {
            $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin.';
            header('Location: ' . $base_path . '/profile/edit');
            exit;
        }

        $userModel = new User();

        // Kiểm tra username đã tồn tại chưa (trừ user hiện tại)
        if ($userModel->isUsernameExistsExcept($_SESSION['user_id'], $username)) {
            $_SESSION['error_message'] = 'Tên đăng nhập đã được sử dụng.';
            header('Location: ' . $base_path . '/profile/edit');
            exit;
        }

        $data = [
            'username' => $username,
            'display_name' => $display_name,
            'email' => $email
        ];

        if ($userModel->updateProfile($_SESSION['user_id'], $data)) {
            $_SESSION['username'] = $username;
            $_SESSION['display_name'] = $display_name;
            $_SESSION['email'] = $email;
            $_SESSION['success_message'] = 'Cập nhật thông tin thành công!';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật thông tin.';
        }

        header('Location: ' . $base_path . '/profile');
        exit;
    }

    private function updatePassword()
    {
        $base_path = dirname($_SERVER['SCRIPT_NAME'], 1);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin.';
            header('Location: ' . $base_path . '/profile/change-password');
            exit;
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['error_message'] = 'Mật khẩu mới không khớp.';
            header('Location: ' . $base_path . '/profile/change-password');
            exit;
        }

        if (strlen($new_password) < 6) {
            $_SESSION['error_message'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
            header('Location: ' . $base_path . '/profile/change-password');
            exit;
        }

        $userModel = new User();

        // Kiểm tra mật khẩu hiện tại
        if (!$userModel->verifyCurrentPassword($_SESSION['user_id'], $current_password)) {
            $_SESSION['error_message'] = 'Mật khẩu hiện tại không đúng.';
            header('Location: ' . $base_path . '/profile/change-password');
            exit;
        }

        if ($userModel->updatePassword($_SESSION['user_id'], $new_password)) {
            $_SESSION['success_message'] = 'Đổi mật khẩu thành công!';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi đổi mật khẩu.';
        }

        header('Location: ' . $base_path . '/profile');
        exit;
    }
}
