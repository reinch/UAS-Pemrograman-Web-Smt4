<?php
session_start();
include_once('../../includes/config.php');

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Allowed file types
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$max_file_size = 2 * 1024 * 1024; // 2MB
$target_dir = "../uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_profile_image'])) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // Validate and sanitize inputs
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $profile_image = $_FILES['profile_image'] ?? null;
        $updated_by = $_SESSION['user_id'] ?? null; // Logged-in admin/user

        if (!$user_id || !$profile_image) {
            $_SESSION['error'] = 'Invalid request. Please select a file.';
            header('location: ../index.php');
            exit();
        }

        // Retrieve user name and old image from database
        $stmt = $conn->prepare("SELECT complete_name, profile_image FROM tbl_user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            header('location: ../index.php');
            exit();
        }

        $complete_name = preg_replace('/[^A-Za-z0-9_\-]/', '', $user['complete_name']); // Clean filename
        $file_extension = strtolower(pathinfo($profile_image['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = 'Invalid file format. Only JPG, JPEG, PNG, and GIF are allowed.';
            header('location: ../index.php');
            exit();
        }

        if ($profile_image['size'] > $max_file_size) {
            $_SESSION['error'] = 'File size exceeds 2MB limit.';
            header('location: ../index.php');
            exit();
        }

        // Generate unique file name
        $file_name = $complete_name . "_" . uniqid() . "." . $file_extension;
        $target_file = $target_dir . $file_name;

        // Remove old profile image if exists
        if (!empty($user['profile_image'])) {
            $old_image_path = $target_dir . $user['profile_image'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }

        // Upload new profile image
        if (move_uploaded_file($profile_image["tmp_name"], $target_file)) {
            // Update profile image path in database
            $stmt = $conn->prepare("UPDATE tbl_user SET profile_image = :profile_image WHERE user_id = :user_id");
            $stmt->bindParam(':profile_image', $file_name, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Profile image updated successfully.';

                // Log activity
                $log_details = "Updated profile image for user #$user_id";
                $stmt_log = $conn->prepare("INSERT INTO tbl_activity_log (user_id, details, date_time) VALUES (:user_id, :details, NOW())");
                $stmt_log->bindParam(':user_id', $updated_by, PDO::PARAM_INT);
                $stmt_log->bindParam(':details', $log_details, PDO::PARAM_STR);
                $stmt_log->execute();
            } else {
                $_SESSION['error'] = 'Database update failed.';
            }
        } else {
            $_SESSION['error'] = 'File upload failed.';
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
