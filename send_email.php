<?php
// Define the recipient email address and other email details
$recipient = "info@graphicsurface.com"; // Change this to the correct recipient address
$subject = "Lounasbuffet_" . $_POST['week'];
$sender = "info@brandly.fi";

// Ensure that the required POST data is set
if (isset($_POST['week'], $_POST['content'], $_POST['selectedOption'], $_POST['pdfData'])) {
    
    // Retrieve the form data
    $week = $_POST['week'];
    $content = $_POST['content'];
    $selectedOption = $_POST['selectedOption'];
    $pdfData = $_POST['pdfData'];
    
    // Decode the base64 data for PDF
    $pdfData = base64_decode(str_replace('data:application/pdf;base64,', '', $pdfData));

    // Define the file name
    $pdfFilename = "Lounasbuffet_{$week}.pdf";

    // Create the email boundary
    $boundary = md5(uniqid(time()));

    // Define the email headers
    $headers = "From: {$sender}\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

    // Start the email body
    $message = "--{$boundary}\r\n";
    $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= "Week: {$week}\n";
    $message .= "Date Range: {$selectedOption}\n\n";
    $message .= "Menu:\n{$content}\r\n\r\n";

    // Attach the PDF file
    $message .= "--{$boundary}\r\n";
    $message .= "Content-Type: application/pdf; name=\"{$pdfFilename}\"\r\n";
    $message .= "Content-Disposition: attachment; filename=\"{$pdfFilename}\"\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $message .= chunk_split(base64_encode($pdfData)) . "\r\n\r\n";

    // End the email
    $message .= "--{$boundary}--";

    // Send the email
    if (mail($recipient, $subject, $message, $headers)) {
        // If the mail was sent successfully
        $response = ['status' => 'success', 'message' => 'Email sent successfully!'];
    } else {
        // If the mail failed to send
        $response = ['status' => 'error', 'message' => 'Failed to send email.'];
    }
} else {
    // If the required POST data is missing
    $response = ['status' => 'error', 'message' => 'Missing required POST data.'];
}

// Return the JSON response
echo json_encode($response);
?>
