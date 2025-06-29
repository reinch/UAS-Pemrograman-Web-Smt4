<?php
session_start();
include_once('../../includes/config.php');

// Function to sanitize and validate input data
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add'])) {
    try {
        // Validate required fields
        if (
            empty($_POST['complete_name']) || 
            empty($_POST['designation']) || 
            empty($_POST['user_type']) || 
            empty($_POST['username']) || 
            empty($_POST['password']) || 
            empty($_FILES['profile_image']['name'])
        ) {
            throw new Exception("Please fill in all required fields.");
        }

        // Database connection
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // Sanitize and validate inputs
        $completeName = sanitize_input($_POST['complete_name']);
        $designation = sanitize_input($_POST['designation']);
        $userType = sanitize_input($_POST['user_type']);
        $username = sanitize_input($_POST['username']);
        $password = password_hash(sanitize_input($_POST['password']), PASSWORD_DEFAULT); // Secure password hashing

        // Check if username already exists
        $sql_check = "SELECT COUNT(*) FROM tbl_user WHERE username = :username";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute([':username' => $username]);
        if ($stmt_check->fetchColumn() > 0) {
            throw new Exception("Username already exists. Please choose a different username.");
        }

        // Handle secure profile image upload
        $targetDirectory = "../uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));

        // Generate a unique image filename: complete_name_uniqueID.extension
        $safeName = preg_replace('/[^A-Za-z0-9]/', '_', $completeName); // Remove special chars
        $uniqueID = uniqid();
        $imageName = "{$safeName}_{$uniqueID}.{$imageFileType}";
        $targetFile = $targetDirectory . $imageName;

        // Validate image file
        $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check === false || !in_array($check['mime'], $validMimeTypes)) {
            throw new Exception("Invalid image file format.");
        }

        // Check file size (max 2MB)
        if ($_FILES["profile_image"]["size"] > 2000000) {
            throw new Exception("File size exceeds the 2MB limit.");
        }

        // Restrict executable file uploads
        if (preg_match('/\.(php|html|htm|js|exe|sh)$/i', $_FILES["profile_image"]["name"])) {
            throw new Exception("Invalid file type.");
        }

        // Ensure upload directory is safe
        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0755, true)) {
            throw new Exception("Failed to create upload directory.");
        }

        // Move uploaded file
        if (!move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            throw new Exception("Error uploading the image.");
        }

        // Insert new user into database
        $sql = "INSERT INTO tbl_user (complete_name, designation, user_type, username, password, profile_image) 
                VALUES (:complete_name, :designation, :user_type, :username, :password, :profile_image)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':complete_name' => $completeName,
            ':designation' => $designation,
            ':user_type' => $userType,
            ':username' => $username,
            ':password' => $password,
            ':profile_image' => $imageName
        ]);

        // Log activity
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $details = "Added new user: $completeName";
            $sql_log = "INSERT INTO tbl_activity_log (user_id, details, date_time) VALUES (:user_id, :details, NOW())";
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->execute([
                ':user_id' => $user_id,
                ':details' => $details
            ]);
        }

        $_SESSION['success'] = 'User added successfully.';
    } catch (PDOException $e) {
        $_SESSION['error'] = ($e->getCode() == 23000) ? "Username already exists." : "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'Invalid request.';
}

// Redirect back to users list
header('Location: ../index.php');
exit();