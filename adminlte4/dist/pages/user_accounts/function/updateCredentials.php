<?php
session_start();
include_once('../../includes/config.php');

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_credentials'])) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // Validate and sanitize inputs
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $new_username = sanitize_input($_POST['new_username']);
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $updated_by = $_SESSION['user_id'] ?? null; // Logged-in admin/user

        if (!$user_id || empty($new_username) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error'] = 'Please fill out all fields.';
            header('location: ../index.php');
            exit();
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = 'Passwords do not match.';
            header('location: ../index.php');
            exit();
        }

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update username and password
        $stmt = $conn->prepare("UPDATE tbl_user SET username = :username, password = :password WHERE user_id = :user_id");
        $stmt->bindParam(':username', $new_username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Username and password updated successfully.';

            // Log the update in tbl_activity_log
            $log_details = "Updated credentials for user #$user_id - New Username: $new_username";

            $stmt_log = $conn->prepare("INSERT INTO tbl_activity_log (user_id, details, date_time) VALUES (:user_id, :details, NOW())");
            $stmt_log->bindParam(':user_id', $updated_by, PDO::PARAM_INT);
            $stmt_log->bindParam(':details', $log_details, PDO::PARAM_STR);
            $stmt_log->execute();
        } else {
            $_SESSION['error'] = 'Failed to update username and password.';
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Log error
        $_SESSION['error'] = 'Database error occurred. Please try again later.';
    }
} else {
    $_SESSION['error'] = 'Invalid request.';
}

header('location: ../index.php');
exit();
?>
