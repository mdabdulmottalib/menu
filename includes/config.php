<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_pass');
define('DB_NAME', 'your_db_name');

// Base URL
define('BASE_URL', 'http://yourdomain.com/');

// Include functions
include_once 'functions.php';

// Autoload classes
spl_autoload_register(function ($class_name) {
    include '../classes/' . $class_name . '.php';
});

// Start session
session_start();
?>
