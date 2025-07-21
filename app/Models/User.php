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
}
