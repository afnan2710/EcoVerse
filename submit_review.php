<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $rating = (int)$_POST['rating'];
    $comment = $conn->real_escape_string($_POST['comment']);

    $query = "INSERT INTO reviews (name, email, contact, rating, comment) 
              VALUES ('$name', '$email', '$contact', '$rating', '$comment')";

    if ($conn->query($query)) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

header("Location: index.php");
?>
