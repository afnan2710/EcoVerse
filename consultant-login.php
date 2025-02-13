<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $consultant_email = $_POST['consultant_email'];
    $consultant_password = $_POST['consultant_password'];
    
    include 'connect.php';

    $stmt = $conn->prepare("SELECT consultant_id, consultant_name, consultant_password FROM consultant WHERE consultant_email = ?");
    $stmt->bind_param("s", $consultant_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($consultant_id, $consultant_name, $hashed_password);
        $stmt->fetch();

        if (password_verify($consultant_password, $hashed_password)) {
            session_regenerate_id(true);

            $_SESSION['consultant_id'] = $consultant_id;
            $_SESSION['consultant_name'] = $consultant_name;
            header("Location: consultant-dashboard.php");
            exit; 
        } else {
            echo '<h2>Invalid Password.</h2>';
        }
    } else {
        echo '<h2>No consultant found with that email address.</h2>';
    }
    $stmt->close();
    $conn->close();
}
?>
