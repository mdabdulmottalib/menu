<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure you have Composer installed with PHPMailer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $week = $_POST['week'];
    $content = $_POST['content'];
    $selectedOption = $_POST['selectedOption'];
    $pdfData = $_POST['pdfData'];
    $imageData = $_POST['imageData'];

    // Decode base64 pdf data
    $pdf = base64_decode(explode(',', $pdfData)[1]);
    $image = base64_decode(explode(',', $imageData)[1]);

    // Set file names
    $pdfFilename = "Lounasbuffet_week_{$week}.pdf";
    $imageFilename = "Lounasbuffet_week_{$week}.jpg";

    // Save PDF and image to server
    file_put_contents($pdfFilename, $pdf);
    file_put_contents($imageFilename, $image);

    // Send Email with PDF attachment
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@brandly.fi';
        $mail->Password = '!Brandlyonparas2024!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('info@brandly.fi', 'Brandly');
        $mail->addAddress('info@graphicsurface.com');

        // Attachments
        $mail->addStringAttachment($pdf, $pdfFilename, 'base64', 'application/pdf');

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Weekly Menu - Week $week";
        $mail->Body = "Please find attached the weekly menu.";

        $mail->send();
        $emailStatus = 'Email sent successfully';

    } catch (Exception $e) {
        $emailStatus = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Post image to Facebook and Instagram using Graph API
    // Note: Make sure to replace 'your-access-token' with your actual token
    $accessToken = 'EAAD8QomIkDUBO08KKcJmBbG7U9ba9zkeZCGNciTE7h8sD28Wnfsc0bXZAFQSmVCSYt1NnfbcuU9BJAVNdU4n3pkC7xf3MltIhTdSg3WVa6nxZA2GH3pZCrYceZBGjdueWIZB03Sv5ZCDJKF980VZC1QQ5oI1rQAhKWuZBnRK3VwK1ynZBMLcEZAExFXMwTeLzWeHat3htWqfADBvgZDZD';
    $fbUrl = 'https://graph.facebook.com/v10.0/me/photos';
    $igUrl = 'https://graph.instagram.com/v10.0/me/media';

    $postData = [
        'access_token' => $accessToken,
        'caption' => "Weekly Menu - Week $week",
        'source' => new CURLFile($imageFilename, 'image/jpeg')
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fbUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $fbResponse = curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $igUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $igResponse = curl_exec($ch);
    curl_close($ch);

    // Save to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO menus (week, content, pdf_filename, image_filename) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $week, $content, $pdfFilename, $imageFilename);

    if ($stmt->execute()) {
        $dbStatus = 'Data saved to database successfully';
    } else {
        $dbStatus = 'Error saving data to database';
    }

    $stmt->close();
    $conn->close();

    // Respond back to the client
    echo json_encode([
        'status' => 'success',
        'message' => "Operation completed: $emailStatus, FB Response: $fbResponse, IG Response: $igResponse, DB: $dbStatus"
    ]);
}
?>
