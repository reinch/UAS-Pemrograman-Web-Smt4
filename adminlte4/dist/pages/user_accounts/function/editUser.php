<?php
session_start();
include_once('../../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // Get and sanitize inputs
        $id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $user_type = trim($_POST['user_type']);
        $complete_name = trim($_POST['complete_name']);
        $designation = trim($_POST['designation']);
        $updated_by = $_SESSION['user_id'] ?? null; // Get logged-in admin ID

        // Input validation
        if (!$id || empty($user_type) || empty($complete_name) || empty($designation)) {
            $_SESSION['error'] = 'Invalid input. Please check your fields.';
            header('location: ../index.php');
            exit();
        }

        // Check for duplicate complete_name (excluding the current user)
        $stmt_check = $conn->prepare("SELECT user_id FROM tbl_user WHERE complete_name = :complete_name AND user_id != :id");
        $stmt_check->bindParam(':complete_name', $complete_name, PDO::PARAM_STR);
        $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            $_SESSION['error'] = 'A user with the same complete name already exists.';
            header('location: ../index.php');
            exit();
        }

        // Fetch old user details before update
        $stmt_old = $conn->prepare("SELECT user_type, complete_name, designation FROM tbl_user WHERE user_id = :id");
        $stmt_old->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_old->execute();
        $old_data = $stmt_old->fetch(PDO::FETCH_ASSOC);

        // Update user details
        $stmt_update = $conn->prepare("UPDATE tbl_user SET user_type = :user_type, complete_name = :complete_name, designation = :designation WHERE user_id = :id");
        $stmt_update->bindParam(':user_type', $user_type, PDO::PARAM_STR);
        $stmt_update->bindParam(':complete_name', $complete_name, PDO::PARAM_STR);
        $stmt_update->bindParam(':designation', $designation, PDO::PARAM_STR);
        $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt_update->execute()) {
            $_SESSION['success'] = 'User updated successfully.';

            // Log the update in tbl_activity_log
            $log_details = "Updated user #$id - Changes: ";
            if ($old_data['user_type'] !== $user_type) {
                $log_details .= "[User Type: {$old_data['user_type']} → $user_type] ";
            }
            if ($old_data['complete_name'] !== $complete_name) {
                $log_details .= "[Complete Name: {$old_data['complete_name']} → $complete_name] ";
            }
            if ($old_data['designation'] !== $designation) {
                $log_details .= "[Designation: {$old_data['designation']} → $designation]";
            }

            $stmt_log = $conn->prepare("INSERT INTO tbl_activity_log (user_id, details, date_time) VALUES (:user_id, :details, NOW())");
            $stmt_log->bindParam(':user_id', $updated_by, PDO::PARAM_INT);
            $stmt_log->bindParam(':details', $log_details, PDO::PARAM_STR);
            $stmt_log->execute();
        } else {
            $_SESSION['error'] = 'Something went wrong while updating the user.';
        }

    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Log error
        $_SESSION['error'] = 'Database error occurred. Please try again later.';
    }
} else {
    $_SESSION['error'] = 'Select a user to edit first.';
}

header('location: ../index.php');
exit();
?>
