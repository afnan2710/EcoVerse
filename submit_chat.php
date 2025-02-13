<?php
include 'connect.php';

$user_name = $_POST['user_name'];
$user_email = $_POST['user_email'];
$user_phone = $_POST['user_phone'];
$user_message = $_POST['user_message'];

$query = "INSERT INTO chat_user (user_name, user_email, user_phone, user_message, chat_status) 
          VALUES ('$user_name', '$user_email', '$user_phone', '$user_message', 'pending')";
$conn->query($query);
$chat_id = $conn->insert_id;
echo json_encode(['chat_id' => $chat_id]);
?>
