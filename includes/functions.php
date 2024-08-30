<?php
function connectDB() {
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    return $connection;
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'views/login.php');
        exit();
    }
}
?>
