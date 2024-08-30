<?php
include '../includes/config.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $week = $_POST['week'];
    $content = $_POST['content'];
    $pdfData = $_POST['pdfData'];
    $imageData = $_POST['imageData'];

    // Sending email logic (e.g., using PHPMailer)
    
    // If the email is sent successfully, update the menu in the database
    $menu = new Menu();
    if ($menu->updateMenuAfterEmail($week)) {
        echo json_encode(['status' => 'success', 'message' => 'Email sent successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error sending email']);
    }
}
?>
