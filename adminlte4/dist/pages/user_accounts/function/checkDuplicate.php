<?php
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // 1. Sanitize the input (CRUCIAL!)
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);

        if ($username === false || $username === null) {
            echo 'error'; // Indicate invalid input
            exit; // Stop execution
        }

        // 2. Prepared statement (already in place - good!)
        $sql = "SELECT COUNT(*) FROM tbl_user WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);

        $userExists = $stmt->fetchColumn();

        // 3. Output the result (no sensitive data in output)
        echo ($userExists > 0) ? 'exists' : 'available';

    } catch (PDOException $e) {
        // 4. Log the error (for debugging)
        error_log("Database Error: " . $e->getMessage());

        // 5. Provide a generic error message (for security)
        echo 'error';
    }
} else {
    // 6. Handle invalid requests
    echo 'error';
}
?>