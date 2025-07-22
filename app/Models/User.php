<?php

require_once '../core/Database.php';

class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Tìm user bằng username hoặc email (để kiểm tra trùng lặp)
     */
    public function findByUsernameOrEmail($username, $email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        return $stmt->fetch();
    }

    /**
     * Đăng ký
     */
    public function register($data)
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, email, password_hash, display_name) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $hashedPassword,
            $data['display_name']
        ]);
    }

    /**
     * Kiểm tra thông tin đăng nhập
     */
    public function attemptLogin($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']);
            return $user;
        }

        return false;
    }

    public function storeRememberToken($userId, $token, $expiresAt)
    {
        $hashedToken = hash('sha256', $token);
        $stmt = $this->pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $hashedToken, $expiresAt]);
    }

    public function findUserByRememberToken($token)
    {
        $hashedToken = hash('sha256', $token);
        $stmt = $this->pdo->prepare(
            "SELECT u.* FROM users u 
             JOIN remember_tokens rt ON u.id = rt.user_id 
             WHERE rt.token = ? AND rt.expires_at > NOW()"
        );
        $stmt->execute([$hashedToken]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function clearRememberToken($token)
    {
        $hashedToken = hash('sha256', $token);
        $stmt = $this->pdo->prepare("DELETE FROM remember_tokens WHERE token = ?");
        return $stmt->execute([$hashedToken]);
    }

    /**
     * Tìm user bằng ID
     */
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, username, email, display_name, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật thông tin profile
     */
    public function updateProfile($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, display_name = ?, email = ? WHERE id = ?");
        return $stmt->execute([
            $data['username'],
            $data['display_name'],
            $data['email'],
            $id
        ]);
    }

    /**
     * Kiểm tra username đã tồn tại chưa (trừ user hiện tại)
     */
    public function isUsernameExistsExcept($userId, $username)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $userId]);
        return $stmt->fetch() !== false;
    }

    /**
     * Kiểm tra mật khẩu hiện tại
     */
    public function verifyCurrentPassword($userId, $password)
    {
        $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        // Sửa từ $user['password'] thành $user['password_hash']
        return password_verify($password, $user['password_hash']);
    }

    /**
     * Cập nhật mật khẩu
     */
    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $userId]);
    }
}
