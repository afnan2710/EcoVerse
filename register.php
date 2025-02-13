<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $address = $_POST['address'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmpassword = $_POST['confirmpassword'] ?? '';

    if ($password !== $confirmpassword) {
        die("Passwords do not match. Please try again.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $firstname = $conn->real_escape_string($firstname);
    $lastname = $conn->real_escape_string($lastname);
    $email = $conn->real_escape_string($email);
    $phone = $conn->real_escape_string($phone);
    $gender = $conn->real_escape_string($gender);
    $address = $conn->real_escape_string($address);
    $hashed_password = $conn->real_escape_string($hashed_password);

    $sql_check = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        die("Email already registered. Please use a different email.");
    }

    $sql = "INSERT INTO users (firstname, lastname, email, phone, gender, address, password) VALUES ('$firstname', '$lastname', '$email', '$phone', '$gender', '$address', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. Redirecting to login page...";
        header("Refresh: 3; URL=login.html");
        exit(); // Always exit after a header redirect
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
