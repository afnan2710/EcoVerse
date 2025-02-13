<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    include 'connect.php';

    $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_password FROM admin WHERE admin_email = ?");
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $admin_name, $hashed_password);
        $stmt->fetch();

        if (password_verify($admin_password, $hashed_password)) {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_name'] = $admin_name;
            header("Location: admin-register.php");
            exit; 
        } else {
            echo '<h2>'."Invalid Password.".'</h2>';
        }
    } else {
        echo '<h2>'."No admin found with that email address.".'</h2>';
    }
    $stmt->close();
    $conn->close();
}
?>