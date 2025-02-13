<?php
session_start();

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chat_id = $_POST['chat_id'];
    $reply_message = $_POST['reply_message'];
    $replied_by = $_SESSION['replied_by'];

    if (empty($chat_id) || empty($reply_message)) {
        echo json_encode(['success' => false, 'error' => 'Chat ID and reply message are required.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO chat_replies (chat_id, reply_message, replied_by) VALUES (?, ?, ?)");
    $stmt->bind_param('isi', $chat_id, $reply_message, $replied_by);
    
    if ($stmt->execute()) {
        $update_stmt = $conn->prepare("UPDATE chat_user SET chat_status = 'resolved' WHERE chat_id = ?");
        $update_stmt->bind_param('i', $chat_id);
        $update_stmt->execute();
        $update_stmt->close();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $stmt->error]);
    }
    
    $stmt->close();
}
$conn->close();
?>
