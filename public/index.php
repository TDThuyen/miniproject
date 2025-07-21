<?php

require_once '../app/Controllers/AuthController.php';
require_once '../app/Controllers/PostController.php';

$base_path = dirname($_SERVER['SCRIPT_NAME']);
if ($base_path === '/' || $base_path === '\\') {
    $base_path = '';
}

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
$request = str_replace($base_path, '', $request_uri);

if (empty($request) || $request === '/') {
    $request = '/login';
}

$authController = new AuthController();

session_start(); // Bắt đầu session để hàm check có thể truy cập
$authController->checkRememberMe();

$postController = new PostController();

$request_uri = explode('?', $_SERVER['REQUEST_URI'])[0];
$request_path = str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $request_uri);
if ($request_path === '/' || $request_path === '') {
    $request_path = '/dashboard'; // Trang mặc định sau khi đăng nhập
}

if (preg_match('/^\/posts\/(\d+)$/', $request_path, $matches)) {
    $postId = $matches[1];
    $postController->show($postId);
} else if (preg_match('/^\/my-posts\/edit\/(\d+)$/', $request_path, $matches)) {
    $postId = $matches[1];
    $postController->edit($postId);
} else {
    switch ($request_path) {
        case '/register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->register();
            } else {
                $authController->showRegistrationForm();
            }
            break;

        case '/login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            } else {
                $authController->showLoginForm();
            }
            break;

        case '/logout':
            $authController->logout();
            break;

        case '/dashboard':
            $postController->index();
            break;

        case '/my-posts':
            $postController->showMyPosts();
            break;

        case '/my-posts/create':
            $postController->showCreateForm();
            break;

        case '/my-posts/store':
            $postController->store();
            break;

        case '/my-posts/delete':
            $postController->delete();
            break;

        case '/my-posts/update':
            $postController->update();
            break;

        default:
            http_response_code(404);
            echo "404 Page Not Found";
            break;
    }
}
