<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $week = $_POST['week'];
    $content = $_POST['content'];
    $selectedOption = $_POST['selectedOption'];
    $weekText = "VIIKKO $week | " . trim(explode('|', $selectedOption)[1]);

    // Database connection
    $conn = new mysqli('127.0.0.1', 'u584505499_weekly_menu', 'B0U2c@+g6', 'u584505499_weekly_menu');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the week already exists
    $stmt = $conn->prepare("SELECT id FROM weekly_menus WHERE week = ?");
    $stmt->bind_param("s", $weekText);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 'exists', 'message' => 'This week\'s menu already exists. Do you want to update it?']);
        $stmt->close();
        exit();
    }

    $stmt->close();

    // Save data into the database
    $stmt = $conn->prepare("INSERT INTO weekly_menus (week, content, created_date, deleted) VALUES (?, ?, NOW(), 0)");
    $stmt->bind_param("ss", $weekText, $content);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save data to the database.']);
        $stmt->close();
        exit();
    }

    $stmt->close();

    // Get the PDF data from the request
    $pdfData = $_POST['pdfData'];
    $pdfData = str_replace('data:application/pdf;base64,', '', $pdfData);
    $pdfData = base64_decode($pdfData);

    $pdfName = "Lounasbuffet_week_$week.pdf";
    file_put_contents($pdfName, $pdfData);

    // Set up PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@brandly.fi';
        $mail->Password   = '!Brandlyonparas2024!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('info@brandly.fi', 'Brandly');
        $mail->addAddress('info@graphicsurface.com');

        // Attachments
        $mail->addAttachment($pdfName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Lounasbuffet - $weekText";
        $mail->Body    = "Please find the attached menu for $weekText.";

        // Send the email
        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Email sent successfully!']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }

    unlink($pdfName);

} else {
    echo "Invalid request.";
}

?>
