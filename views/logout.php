<?php
include '../includes/config.php';
$user = new User();
$user->logout();
header('Location: ' . BASE_URL . 'views/login.php');
?>
