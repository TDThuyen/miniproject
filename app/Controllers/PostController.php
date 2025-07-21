<?php

require_once 'C:\xampp\htdocs\miniproject\app\Models\Post.php';

class PostController
{

    public function index()
    {
        // Chỉ cần gọi file view của dashboard.
        require_once  'C:\xampp\htdocs\miniproject\app\Views\dashboard\index.php';
    }


    public function showMyPosts()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login'); // Chuyển hướng nếu chưa đăng nhập
            exit;
        }

        $postModel = new Post();
        $posts = $postModel->findByUserId($_SESSION['user_id']);

        require_once 'C:\xampp\htdocs\miniproject\app\Views\my-posts\index.php';
    }

    /**
     * Hiển thị form tạo bài viết mới.
     */
    public function showCreateForm()
    {
        require_once  'C:\xampp\htdocs\miniproject\app\Views\my-posts\create.php';
    }

    /**
     * Lưu bài viết mới vào CSDL.
     */
    public function store()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $base_path = dirname($_SERVER['SCRIPT_NAME']);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);

            if (empty($title) || empty($content)) {
                $_SESSION['error_message'] = 'Tiêu đề và nội dung không được để trống.';
                header('Location: ' . $base_path . '/my-posts/create');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'title' => $title,
                'content' => $content
            ];

            $postModel = new Post();
            if ($postModel->create($data)) {
                $_SESSION['success_message'] = 'Tạo bài viết thành công!';
            } else {
                $_SESSION['error_message'] = 'Đã có lỗi xảy ra. Vui lòng thử lại.';
            }
            header('Location: ' . $base_path . '/my-posts');
            exit;
        }
    }

    /**
     * Xóa bài viết.
     */
    public function delete()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $base_path = dirname($_SERVER['SCRIPT_NAME']);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $postId = $_POST['post_id'];
            $userId = $_SESSION['user_id'];

            $postModel = new Post();
            // Hàm delete trong model sẽ kiểm tra cả userId để đảm bảo bảo mật
            if ($postModel->delete($postId, $userId)) {
                $_SESSION['success_message'] = 'Xóa bài viết thành công!';
            } else {
                $_SESSION['error_message'] = 'Không thể xóa bài viết hoặc bạn không có quyền.';
            }
            header('Location: ' . $base_path . '/my-posts');
            exit;
        }
    }

    /**
     * Hiển thị chi tiết một bài viết.
     */
    public function show($id)
    {
        $postModel = new Post();
        $post = $postModel->findById($id);

        require_once __DIR__ . '/../Views/posts/show.php';
    }


    /**
     * Hiển thị form chỉnh sửa bài viết.
     */
    public function edit($id)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $base_path = dirname($_SERVER['SCRIPT_NAME']);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }

        $postModel = new Post();
        $post = $postModel->findById($id);

        // Đảm bảo người dùng chỉ có thể sửa bài viết của chính họ.
        if (!$post || $post['user_id'] !== $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang này.';
            header('Location: ' . $base_path . '/my-posts');
            exit;
        }

        require_once __DIR__ . '/../Views/my-posts/edit.php';
    }


    public function update()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $base_path = dirname($_SERVER['SCRIPT_NAME']);
        if ($base_path === '/' || $base_path === '\\') {
            $base_path = '';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $postId = $_POST['post_id'];
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);

            if (empty($title) || empty($content)) {
                $_SESSION['error_message'] = 'Tiêu đề và nội dung không được để trống.';
                header('Location: ' . $base_path . '/my-posts/edit/' . $postId);
                exit;
            }

            $data = ['title' => $title, 'content' => $content];
            $postModel = new Post();

            if ($postModel->update($postId, $_SESSION['user_id'], $data)) {
                $_SESSION['success_message'] = 'Cập nhật bài viết thành công!';
            } else {
                $_SESSION['error_message'] = 'Cập nhật thất bại hoặc bạn không có quyền.';
            }
            header('Location: ' . $base_path . '/my-posts');
            exit;
        }
    }
}
