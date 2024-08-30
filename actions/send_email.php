<?php
include '../includes/config.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $week = $_POST['week'];
    $content = $_POST['content'];
    $pdfData = $_POST['pdfData'];
    $imageData = $_POST['imageData'];

    // Convert base64 PDF and Image data to files
    $pdfPath = "../uploads/pdf/Lounasbuffet_week_${week}.pdf";
    $imagePath = "../uploads/images/Lounasbuffet_week_${week}.jpg";

    // Decode the base64 data and save it as files
    file_put_contents($pdfPath, base64_decode(preg_replace('#^data:application/pdf;base64,#i', '', $pdfData)));
    file_put_contents($imagePath, base64_decode(preg_replace('#^data:image/jpeg;base64,#i', '', $imageData)));

    // Email details
    $from = "info@brandly.fi";
    $to = "info@graphicsurface.com";
    $subject = "Lounasbuffet_week_${week}";
    $message = "Please find the attached menu for week ${week}.\n\nContent:\n" . strip_tags($content);

    // Headers
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"_1_\"\r\n";

    // Prepare email body
    $emailBody = "--_1_\r\n";
    $emailBody .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
    $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $emailBody .= $message . "\r\n\r\n";

    // Attach PDF
    $pdfContent = chunk_split(base64_encode(file_get_contents($pdfPath)));
    $emailBody .= "--_1_\r\n";
    $emailBody .= "Content-Type: application/pdf; name=\"Lounasbuffet_week_${week}.pdf\"\r\n";
    $emailBody .= "Content-Transfer-Encoding: base64\r\n";
    $emailBody .= "Content-Disposition: attachment; filename=\"Lounasbuffet_week_${week}.pdf\"\r\n\r\n";
    $emailBody .= $pdfContent . "\r\n\r\n";

    // Attach Image
    $imageContent = chunk_split(base64_encode(file_get_contents($imagePath)));
    $emailBody .= "--_1_\r\n";
    $emailBody .= "Content-Type: image/jpeg; name=\"Lounasbuffet_week_${week}.jpg\"\r\n";
    $emailBody .= "Content-Transfer-Encoding: base64\r\n";
    $emailBody .= "Content-Disposition: attachment; filename=\"Lounasbuffet_week_${week}.jpg\"\r\n\r\n";
    $emailBody .= $imageContent . "\r\n\r\n";

    // End the email
    $emailBody .= "--_1_--";

    // Send the email
    if (mail($to, $subject, $emailBody, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Email sent successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
    }
}
?>
