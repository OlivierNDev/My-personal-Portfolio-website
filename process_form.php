<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $number = $_POST['Number'];
    $service = $_POST['Service'];
    $message = $_POST['Message'];

    // File upload handling
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = $_FILES["File"]["name"];
    $target_file = $target_dir . basename($file_name);
    move_uploaded_file($_FILES["File"]["tmp_name"], $target_file);

    $to = "youremail@example.com";
    $subject = "New Portfolio Form Submission";
    $body = "Name: $name\nEmail: $email\nPhone: $number\nService: $service\nMessage:\n$message\nFile: $target_file";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "Something went wrong.";
    }
}
?>
