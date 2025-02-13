<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.html');
    exit();
}

include 'connect.php';
require_once('fpdf/fpdf.php');

$user_id = $_SESSION['id'];

// Fetch user details
$user_sql = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch order details
$order_sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = 'Passwords do not match!';
    } else {
        if (!empty($new_password)) {
            $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
            $update_sql = "UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ?, gender = ?, address = ?, password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssssssi", $firstname, $lastname, $email, $phone, $gender, $address, $new_password_hashed, $user_id);
        } else {
            $update_sql = "UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ?, gender = ?, address = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssssi", $firstname, $lastname, $email, $phone, $gender, $address, $user_id);
        }
        if ($update_stmt->execute()) {
            $message = 'Profile updated successfully!';
        } else {
            $error = 'Profile update failed. Please try again.';
        }
    }
}

// Function to generate PDF receipt
if (isset($_GET['download_receipt'])) {
    $order_id = $_GET['download_receipt'];

    // Fetch the order details
    $receipt_sql = "SELECT o.*, u.firstname, u.lastname, u.phone 
                    FROM orders o 
                    JOIN users u ON o.user_id = u.id 
                    WHERE o.id = ?";
    $receipt_stmt = $conn->prepare($receipt_sql);
    $receipt_stmt->bind_param("i", $order_id);
    $receipt_stmt->execute();
    $receipt_result = $receipt_stmt->get_result();
    $order_data = $receipt_result->fetch_assoc();

    // Fetch order items
    $items_sql = "SELECT oi.*, p.name, p.image_url 
                  FROM order_items oi 
                  JOIN products p ON oi.product_id = p.product_id 
                  WHERE oi.order_id = ?";
    $items_stmt = $conn->prepare($items_sql);
    $items_stmt->bind_param("i", $order_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();

    // Generate the PDF receipt
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Order Receipt');
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Customer Name: ' . $order_data['firstname'] . ' ' . $order_data['lastname']);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Phone: ' . $order_data['phone']);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Address: ' . $order_data['address']);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Total: $' . number_format($order_data['total'], 2));
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Order Date: ' . $order_data['created_at']);
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Order Items:');
    $pdf->Ln();

    while ($item = $items_result->fetch_assoc()) {
        $pdf->Cell(40, 10, $item['name'] . ' - ' . $item['quantity'] . ' x $' . number_format($item['price'], 2));
        $pdf->Ln();
    }

    $pdf->Output('D', 'receipt_' . $order_id . '.pdf');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .font-pop {
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin: 20px auto;
            max-width: 800px;
        }
    </style>
</head>
<body class="font-pop">
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto mt-10 ml-10">
        <?php if ($message): ?>
            <div class="alert alert-success">
                <div>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error">
                <div>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            </div>
        <?php endif; ?>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h2 class="text-2xl font-bold mb-4">Update Profile</h2>
                <form action="profile.php" method="POST" class="space-y-4">
                    <!-- Form fields -->
                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <label for="firstname" class="block">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="input input-bordered w-full" value="<?= htmlspecialchars($user['firstname']) ?>" required>
                        </div>
                        <div class="w-1/2">
                            <label for="lastname" class="block">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="input input-bordered w-full" value="<?= htmlspecialchars($user['lastname']) ?>" required>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <label for="email" class="block">Email</label>
                            <input type="email" id="email" name="email" class="input input-bordered w-full" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="w-1/2">
                            <label for="phone" class="block">Phone</label>
                            <input type="text" id="phone" name="phone" class="input input-bordered w-full" value="<?= htmlspecialchars($user['phone']) ?>" required>
                        </div>
                    </div>

                    <div>
                        <label for="gender" class="block">Gender</label>
                        <select id="gender" name="gender" class="select select-bordered w-full" required>
                            <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="address" class="block">Address</label>
                        <input type="text" id="address" name="address" class="input input-bordered w-full" value="<?= htmlspecialchars($user['address']) ?>" required>
                    </div>

                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <label for="new_password" class="block">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="input input-bordered w-full">
                        </div>
                        <div class="w-1/2">
                            <label for="confirm_password" class="block">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="input input-bordered w-full">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Update Profile</button>
                </form>
            </div>

            <!-- Order History Section -->
            <div>
                <h2 class="text-2xl font-bold mb-4">Order History</h2>
                <?php if ($order_result->num_rows > 0): ?>
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Receipt</th>
                                <th>Ordered Products</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $order_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                                    <td>$<?= htmlspecialchars(number_format($order['total'], 2)) ?></td>
                                    <td><?= htmlspecialchars($order['status']) ?></td> <!-- Added order status here -->
                                    <td>
                                        <a href="profile.php?download_receipt=<?= $order['id'] ?>" class="btn btn-sm btn-secondary">Download</a>
                                    </td>
                                    <td>
                                        <ul>
                                            <?php
                                            $items_sql = "SELECT p.name, p.image_url, oi.quantity 
                                                        FROM order_items oi 
                                                        JOIN products p ON oi.product_id = p.product_id 
                                                        WHERE oi.order_id = ?";
                                            $items_stmt = $conn->prepare($items_sql);
                                            $items_stmt->bind_param("i", $order['id']);
                                            $items_stmt->execute();
                                            $items_result = $items_stmt->get_result();
                                            while ($item = $items_result->fetch_assoc()) {
                                                echo '<li>';
                                                echo '<img src="' . htmlspecialchars($item['image_url']) . '" alt="' . htmlspecialchars($item['name']) . '" class="w-10 h-10 inline-block mr-2">';
                                                echo htmlspecialchars($item['name']) . ' (' . htmlspecialchars($item['quantity']) . ')';
                                                echo '</li>';
                                            }
                                            ?>
                                        </ul>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
