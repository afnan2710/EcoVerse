<?php
// Database connection
$host = 'localhost';
$dbname = 'g10';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if email is set in POST request
    if (isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => 'Invalid email address.']);
            exit();
        }

        // Check if the email already exists in the database
        $stmt = $conn->prepare('SELECT * FROM subscribers WHERE email = :email');
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['error' => 'This email is already subscribed.']);
            exit();
        }

        // Insert the email into the database
        $stmt = $conn->prepare('INSERT INTO subscribers (email) VALUES (:email)');
        $stmt->execute(['email' => $email]);

        echo json_encode(['success' => 'You have successfully subscribed to our newsletter!']);
    } else {
        echo json_encode(['error' => 'Email is required.']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
