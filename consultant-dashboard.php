<?php
session_start();

if (!isset($_SESSION['consultant_id'])) {
    header("Location: consultant-login.php");
    exit;
}

include 'connect.php';

// Fetch unresolved chats
$unresolved_result = $conn->query("SELECT * FROM chat_user WHERE chat_status = 'pending' ORDER BY created_at DESC");

// Fetch all chats
$all_chats_result = $conn->query("SELECT * FROM chat_user ORDER BY created_at DESC");

if (!$all_chats_result) {
    die("Query failed: " . $conn->error);
}

// Add Event Backend Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_details = $_POST['event_details'];
    $event_date = $_POST['event_date'];
    $meeting_link = $_POST['meeting_link'];

    // Validate fields
    if (empty($event_name) || empty($event_details) || empty($event_date) || empty($meeting_link)) {
        $error_message = "Please fill out all fields.";
    } else {
        // Insert event into database
        $stmt = $conn->prepare("INSERT INTO events (event_name, event_details, event_date, meeting_link) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $event_name, $event_details, $event_date, $meeting_link);
        if ($stmt->execute()) {
            $success_message = "Event added successfully!";
        } else {
            $error_message = "Error adding event: " . $stmt->error;
        }
    }
}

// Fetch all events
$events_result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");

if (!$events_result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultant Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e5f4e3;
            color: #333;
        }

        h1, h2 {
            color: #2c3e50;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .card {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .reply-btn, .resolve-btn {
            background-color: #28a745;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .reply-btn:hover, .resolve-btn:hover {
            background-color: #218838;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            float: right;
            margin-bottom: 20px;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
            font-size: 0.9em;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007d3c;
            color: white;
        }

        /* Event Form Styles */
        form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        form label {
            font-weight: bold;
            color: #2c3e50;
        }

        form input, form textarea {
            width: 97%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><a href="consultant-dashboard.php">Consultant Dashboard</a></h1>
        
        <form action="consultant-logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>

        <h3 align="center"><a href="consultant-blog.php">Write Blog</a></h3>

        <!-- Unresolved Chats Section -->
        <h2>Unresolved Chats</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Action</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $unresolved_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['user_email']) ?></td>
                <td><?= htmlspecialchars($row['user_phone']) ?></td>
                <td><?= htmlspecialchars($row['user_message']) ?></td>
                <td>
                    <button class="reply-btn" data-chat-id="<?= htmlspecialchars($row['chat_id']) ?>">Reply</button>
                </td>
                <td>
                    <button class="resolve-btn" data-chat-id="<?= htmlspecialchars($row['chat_id']) ?>">Mark as Resolved</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Upcoming Events Section -->
        <h2>Upcoming Events</h2>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Details</th>
                <th>Date</th>
                <th>Meeting Link</th>
            </tr>
            <?php while ($row = $events_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['event_name']) ?></td>
                <td><?= htmlspecialchars($row['event_details']) ?></td>
                <td><?= htmlspecialchars($row['event_date']) ?></td>
                <td><a href="<?= htmlspecialchars($row['meeting_link']) ?>" target="_blank">Join</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Add Event Section -->
        <h2>Add Event</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert error"><?= $error_message ?></div>
        <?php elseif (isset($success_message)): ?>
            <div class="alert success"><?= $success_message ?></div>
        <?php endif; ?>
        <form method="POST" action="consultant-dashboard.php">
            <label for="event_name">Event Name:</label><br>
            <input type="text" id="event_name" name="event_name" required><br><br>

            <label for="event_details">Event Details:</label><br>
            <textarea id="event_details" name="event_details" rows="4" required></textarea><br><br>

            <label for="event_date">Event Date:</label><br>
            <input type="date" id="event_date" name="event_date" required><br><br>

            <label for="meeting_link">Meeting Link:</label><br>
            <input type="url" id="meeting_link" name="meeting_link" required><br><br>

            <button type="submit" name="add_event">Add Event</button>
        </form>

    </div>

    <script>
        document.querySelectorAll('.resolve-btn').forEach(button => {
            button.addEventListener('click', () => {
                const chatId = button.dataset.chatId;
                fetch('resolve_chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ chat_id: chatId })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Chat resolved successfully.');
                        window.location.reload();
                    } else {
                        alert('Failed to resolve chat.');
                    }
                });
            });
        });
    </script>
</body>
</html>
