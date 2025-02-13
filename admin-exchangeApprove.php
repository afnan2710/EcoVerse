<?php
include('connect.php');

if (isset($_POST['action'])) {
    $exchange_id = $_POST['exchange_id'];
    
    if ($_POST['action'] == 'approve') {
        $query = "UPDATE plant_exchange SET status = 'approved' WHERE exchange_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $exchange_id);
        $stmt->execute();
        echo "<script>alert('Request approved!');</script>";
    } elseif ($_POST['action'] == 'deny') {
        $query = "DELETE FROM plant_exchange WHERE exchange_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $exchange_id);
        $stmt->execute();
        echo "<script>alert('Request denied and deleted.');</script>";
    }
}

// Handle Delete requests for approved exchanges (only if marked as completed)
if (isset($_POST['delete']) && $_POST['status'] == 'completed') {
    $exchange_id = $_POST['exchange_id'];
    $query = "DELETE FROM plant_exchange WHERE exchange_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $exchange_id);
    $stmt->execute();
    echo "<script>alert('Exchange successfully deleted.');</script>";
} elseif (isset($_POST['delete'])) {
    echo "<script>alert('Only completed exchanges can be deleted.');</script>";
}

// Update exchange status to completed
if (isset($_POST['mark_completed'])) {
    $exchange_id = $_POST['exchange_id'];
    $query = "UPDATE plant_exchange SET status = 'completed' WHERE exchange_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $exchange_id);
    $stmt->execute();
    echo "<script>alert('Exchange marked as completed.');</script>";
}

$pending_query = "SELECT * FROM plant_exchange WHERE status = 'pending'";
$pending_result = $conn->query($pending_query);

$approved_query = "SELECT * FROM plant_exchange WHERE status = 'approved' OR status = 'completed'";
$approved_result = $conn->query($approved_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Approve Plant Exchange Requests</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            font-size: 2.2em;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
        }

        th {
            background-color: #34495e;
            color: white;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        td img {
            border-radius: 8px;
            max-width: 100px;
            height: auto;
        }

        .btn-approve, .btn-deny, .btn-delete, .btn-complete {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 0.9em;
            font-weight: bold;
        }

        .btn-approve {
            background-color: #27ae60;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-approve:hover {
            background-color: #2ecc71;
        }

        .btn-deny {
            background-color: #e74c3c;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-deny:hover {
            background-color: #c0392b;
        }

        .btn-complete {
            background-color: #3498db;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-complete:hover {
            background-color: #2980b9;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        p {
            text-align: center;
            font-size: 1.2em;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
<?php include 'admin-header.php' ?>
<div class="container">
    <h1>Pending Plant Exchange Requests</h1>
    
    <?php if ($pending_result->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Exchange ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Plant Details</th>
                    <th>Exchange Type</th>
                    <th>Selling Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $pending_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['exchange_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td><?php echo htmlspecialchars($row['plant_details']); ?></td>
                        <td><?php echo htmlspecialchars($row['exchange_type']); ?></td>
                        <td><?php echo $row['exchange_type'] == 'money' ? "$" . $row['selling_price'] : "N/A"; ?></td>
                        <td>
                            <?php if ($row['image_url']) { ?>
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Plant Image">
                            <?php } else { ?>
                                No image
                            <?php } ?>
                        </td>
                        <td>
                            <form method="POST" action="admin-exchangeApprove.php">
                                <input type="hidden" name="exchange_id" value="<?php echo $row['exchange_id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn-approve">Approve</button>
                                <button type="submit" name="action" value="deny" class="btn-deny">Deny</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No pending requests.</p>
    <?php } ?>

    <h1>Approved Plant Exchange Posts</h1>

    <?php if ($approved_result->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Exchange ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Plant Details</th>
                    <th>Exchange Type</th>
                    <th>Selling Price</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $approved_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['exchange_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td><?php echo htmlspecialchars($row['plant_details']); ?></td>
                        <td><?php echo htmlspecialchars($row['exchange_type']); ?></td>
                        <td><?php echo $row['exchange_type'] == 'money' ? "$" . $row['selling_price'] : "N/A"; ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['image_url']) { ?>
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Plant Image">
                            <?php } else { ?>
                                No image
                            <?php } ?>
                        </td>
                        <td>
                            <form method="POST" action="admin-exchangeApprove.php">
                                <input type="hidden" name="exchange_id" value="<?php echo $row['exchange_id']; ?>">
                                <?php if ($row['status'] == 'approved') { ?>
                                    <button type="submit" name="mark_completed" value="complete" class="btn-complete">Mark as Completed</button>
                                <?php } ?>
                                <?php if ($row['status'] == 'completed') { ?>
                                    <button type="submit" name="delete" value="delete" class="btn-delete">Delete</button>
                                <?php } ?>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No approved exchange posts.</p>
    <?php } ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
