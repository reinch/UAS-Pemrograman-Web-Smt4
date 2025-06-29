<?php
session_start();
require_once "../includes/config.php";

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function login($username, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_user WHERE username = :username LIMIT 1");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['complete_name'] = $user['complete_name'];
                $_SESSION['user_type'] = $user['user_type'];
                session_regenerate_id(true); // Regenerate session ID
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!empty($username) && !empty($password)) {
        $user = new User();
        if ($user->login($username, $password)) {
            header("Location: ../dashboard/index.php");
            exit();
        } else {
            $_SESSION['error'] = "Username atau password salah!";
        }
    } else {
        $_SESSION['error'] = "Harus diisi!";
    }
    header("Location: login.php");
    exit();
}
?>