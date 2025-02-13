<?php
include 'connect.php';

$chat_id = $_GET['chat_id'];

$query = "SELECT * FROM chat_replies WHERE chat_id = '$chat_id' ORDER BY created_at ASC";
$result = $conn->query($query);

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'message' => $row['message'],
        'sender' => $row['sender']
    ];
}

echo json_encode(['messages' => $messages]);
?>
