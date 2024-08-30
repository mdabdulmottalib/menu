<?php
include '../includes/config.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $week = $_POST['week'];
    $content = $_POST['content'];
    
    $menu = new Menu();
    
    // Assume generateImageAndPdf is a function that returns paths to the image and PDF
    list($imagePath, $pdfPath) = generateImageAndPdf($content, $week);
    
    if ($menu->saveMenu($week, $content, $imagePath, $pdfPath)) {
        echo json_encode(['status' => 'success', 'message' => 'Menu saved successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error saving menu']);
    }
}
?>
