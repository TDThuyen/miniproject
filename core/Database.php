<?php

class Database
{
    private static $pdo;

    public static function getInstance()
    {
        if (self::$pdo === null) {
            $host = 'localhost';
            $dbname = 'miniproject'; // <-- THAY TÊN CSDL
            $username = 'root';
            $password = '12345678910';

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Lỗi kết nối CSDL: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
