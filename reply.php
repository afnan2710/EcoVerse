<?php
session_start();

if (!isset($_SESSION['consultant_id'])) {
    header("Location: consultant-login.php");
    exit;
}

include 'connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chat_id = $conn->real_escape_string($_POST['chat_id']);
    $reply_message = $conn->real_escape_string($_POST['reply_message']);
    $consultant_id = $_SESSION['consultant_id'];

    if (!empty($chat_id) && !empty($reply_message)) {
        $sql_insert_reply = "INSERT INTO chat_replies (chat_id, replied_by, reply_message) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql_insert_reply);
        $stmt->bind_param("iis", $chat_id, $replied_by, $reply_message);

        if ($stmt->execute()) {
            $sql_update_status = "UPDATE chat_user SET chat_status = 'resolved' WHERE chat_id = ?";
            $stmt_update = $conn->prepare($sql_update_status);
            $stmt_update->bind_param("i", $chat_id);
            $stmt_update->execute();
            $stmt_update->close();

            $_SESSION['message'] = "Reply sent successfully!";
            header("Location: consultant-dashboard.php");
        } else {
            $_SESSION['error'] = "Failed to send reply. Please try again.";
            header("Location: consultant-dashboard.php");
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Please provide a reply message.";
        header("Location: consultant-dashboard.php");
    }
}
$conn->close();
?>
