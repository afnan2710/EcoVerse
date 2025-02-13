<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    include 'connect.php';

    $stmt = $conn->prepare("SELECT id, firstname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $firstname, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);

            $_SESSION['id'] = $id;
            $_SESSION['firstname'] = $firstname;
            header("Location: index.php");
            exit; 
        } else {
            echo '<h2>Invalid Password.</h2>';
        }
    } else {
        echo '<h2>No user found with that email address.</h2>';
    }

    $stmt->close();
    $conn->close();
} else {
    echo '<h2>Invalid request method.</h2>';
}
?>
