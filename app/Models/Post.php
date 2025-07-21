<?php
// filepath: c:\xampp\htdocs\miniproject\app\Models\Post.php

require_once  'C:\xampp\htdocs\miniproject\core\Database.php';

class Post
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Lấy tất cả bài viết của một người dùng cụ thể.
     */
    public function findByUserId($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo một bài viết mới.
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['content']
        ]);
    }

    /**
     * Xóa một bài viết.
     * Cực kỳ quan trọng: Phải kiểm tra cả post_id và user_id để đảm bảo người dùng không thể xóa bài của người khác.
     */
    public function delete($postId, $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        return $stmt->execute([$postId, $userId]);
    }

    /**
     * Tìm một bài viết bằng ID của nó.
     * Chúng ta sẽ JOIN với bảng users để lấy tên tác giả.
     */
    public function findById($postId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, u.display_name 
             FROM posts p 
             JOIN users u ON p.user_id = u.id 
             WHERE p.id = ?"
        );
        $stmt->execute([$postId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật một bài viết.
     * Cực kỳ quan trọng: Phải kiểm tra cả post_id và user_id để đảm bảo người dùng không thể sửa bài của người khác.
     */
    public function update($postId, $userId, $data)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $postId,
            $userId
        ]);
    }
}
