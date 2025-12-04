<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'computer_parts_db');

function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            die("Грешка при връзка с базата данни: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (Exception $e) {
        die("Грешка при връзка: " . $e->getMessage());
    }
}

function sanitizeInput($data, $conn) {
    if ($conn) {
        return mysqli_real_escape_string($conn, htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8'));
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>

