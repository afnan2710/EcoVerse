<?php
include 'connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$chat_id = $data['chat_id'];

if (!$chat_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid chat ID']);
    exit;
}

$update_query = $conn->prepare("UPDATE chat_user SET chat_status = 'resolved' WHERE chat_id = ?");
$update_query->bind_param('i', $chat_id);

if ($update_query->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update chat status']);
}

$update_query->close();
$conn->close();
?>
