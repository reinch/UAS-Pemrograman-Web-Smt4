<?php
session_start();
include_once('../../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // Get and sanitize user ID
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $deleted_by = $_SESSION['user_id'] ?? null; // Logged-in admin ID

        if (!$id) {
            $_SESSION['error'] = 'Invalid user ID.';
            header('location: ../index.php');
            exit();
        }

        // Fetch user details before deletion (for logging and image removal)
        $stmt_select = $conn->prepare("SELECT username, complete_name, designation, profile_image FROM tbl_user WHERE user_id = :id");
        $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $user = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            header('location: ../index.php');
            exit();
        }

        // Remove profile image from server if it exists
        $target_dir = "../uploads/"; // Adjust if needed
        $profile_image = $user['profile_image'];
        $image_path = $target_dir . $profile_image;

        if (!empty($profile_image) && file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete user from tbl_user
        $stmt_delete = $conn->prepare("DELETE FROM tbl_user WHERE user_id = :id");
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt_delete->execute()) {
            $_SESSION['success'] = 'User deleted successfully.';

            // Log deletion in tbl_activity_log
            $log_details = "Deleted user #$id - Username: {$user['username']}, Name: {$user['complete_name']}, Designation: {$user['designation']}";

            $stmt_log = $conn->prepare("INSERT INTO tbl_activity_log (user_id, details, date_time) VALUES (:user_id, :details, NOW())");
            $stmt_log->bindParam(':user_id', $deleted_by, PDO::PARAM_INT);
            $stmt_log->bindParam(':details', $log_details, PDO::PARAM_STR);
            $stmt_log->execute();
        } else {
            $_SESSION['error'] = 'Something went wrong while deleting the user.';
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Log error
        $_SESSION['error'] = 'Database error occurred. Please try again later.';
    }
} else {
    $_SESSION['error'] = 'Select a user to delete first.';
}

header('location: ../index.php');
exit();
?>
