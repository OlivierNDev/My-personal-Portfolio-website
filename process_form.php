<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $number = $_POST['Number'];
    $service = $_POST['Service'];
    $message = $_POST['Message'];
    
    // File upload handling
    $file_attached = false;
    if(isset($_FILES['File']) && $_FILES['File']['error'] == UPLOAD_ERR_OK) {
        $file_attached = true;
        $file_name = $_FILES['File']['name'];
        $file_tmp = $_FILES['File']['tmp_name'];
        $file_type = $_FILES['File']['type'];
        $file_size = $_FILES['File']['size'];
    }

    $to = "olivier.niyo250@gmail.com";
    $subject = "New Contact Form Submission: $service";
    $headers = "From: $email";
    
    $email_body = "Name: $name\n".
                  "Email: $email\n".
                  "Phone: $number\n".
                  "Service: $service\n".
                  "Message:\n$message\n";
    
    // For HTML emails:
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    // Boundary for multipart
    $boundary = md5(time());
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: $email\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    
    // Message body
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $email_body."\r\n";
    
    // Attach file
    if($file_attached) {
        $file_content = file_get_contents($file_tmp);
        $file_content = chunk_split(base64_encode($file_content));
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= $file_content."\r\n";
    }
    
    $body .= "--$boundary--";
    
    // Send email
    if(mail($to, $subject, $body, $headers)) {
        echo "Thank you for your message!";
    } else {
        echo "There was a problem sending your message.";
    }
}
?>