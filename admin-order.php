<?php
include 'connect.php';

session_start();

// Search functionality
$search_order_id = '';
if (isset($_GET['search'])) {
    $search_order_id = $_GET['search'];
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status, $order_id);

    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Order status updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update order status.";
    }

    $update_stmt->close();
}

// Fetch orders with product details
$query = "
    SELECT orders.id AS order_id, orders.name AS customer_name, orders.address, orders.total, orders.status, orders.created_at,
           products.name AS product_name, products.image_url, order_items.quantity, order_items.price
    FROM orders
    JOIN order_items ON orders.id = order_items.order_id
    JOIN products ON order_items.product_id = products.product_id";

if (!empty($search_order_id)) {
    $query .= " WHERE orders.id = ?";
}

$query .= " ORDER BY orders.created_at DESC";
$select_order = $conn->prepare($query);

if (!empty($search_order_id)) {
    $select_order->bind_param("i", $search_order_id);
}

$select_order->execute();
$result = $select_order->get_result();
?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Order List</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                        'plant-primary': '#E76F51',
                        'plant-primary-bg': 'rgba(231, 111, 81, 0.10)',
                    }
                }
            }
        }
    </script>
</head>
<body>
    <?php include 'admin-header.php'; ?>

    <section class="accounts">
        <h3 class="font-pop text-center text-5xl font-bold text-green-950 mt-10">Orders</h3>

        <!-- Search by Order ID -->
        <form method="GET" class="text-center mt-6">
            <input type="text" name="search" placeholder="Search Order by ID" class="border p-2 rounded" value="<?= htmlspecialchars($search_order_id); ?>">
            <button type="submit" class="bg-green-500 text-white p-2 rounded ml-2">Search</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 m-10 justify-center">
        <?php
        if ($result->num_rows > 0) {
            $last_order_id = null;

            while ($fetch_order = $result->fetch_assoc()) {
                // Open a new card for each new order
                if ($last_order_id !== $fetch_order['order_id']) {
                    if ($last_order_id !== null) {
                        // Close the last card div
                        echo '</div></form>';
                    }
                    $last_order_id = $fetch_order['order_id'];
                    ?>
                    <!-- New form and card for each order -->
                    <form method="POST" class="card bg-gray-100 shadow-lg rounded-lg p-5 w-full md:w-11/12 lg:w-10/12 transition-transform transform hover:scale-105 mx-auto">
                        <div class="text-center">
                            <p class="font-bold text-2xl pb-2 text-green-800">Order ID: <span class="text-lg"><?= $fetch_order['order_id']; ?></span></p>
                            <p class="text-lg mb-3">Username: <span class="font-semibold"><?= $fetch_order['customer_name']; ?></span></p>
                            <p class="text-lg mb-3">Address: <span class="font-semibold"><?= $fetch_order['address']; ?></span></p>
                            <p class="text-lg">Total Amount: <span class="font-semibold">$<?= number_format($fetch_order['total'], 2); ?></span></p>

                            <!-- Order Status Dropdown -->
                            <input type="hidden" name="order_id" value="<?= $fetch_order['order_id']; ?>">
                            <label for="status" class="block text-lg font-medium text-gray-700 mt-4">Change Order Status:</label>
                            <select name="status" class="form-select mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                <option value="Order Received" <?= ($fetch_order['status'] == 'Order Received') ? 'selected' : ''; ?>>Order Received</option>
                                <option value="Processing" <?= ($fetch_order['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                                <option value="Shipped" <?= ($fetch_order['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                                <option value="Delivered" <?= ($fetch_order['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                <option value="Cancelled" <?= ($fetch_order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="bg-green-500 text-white mt-4 p-2 rounded hover:bg-green-600 transition duration-200">Update Status</button>
                        </div>

                        <div class="order-items mt-4">
                            <h4 class="font-bold text-lg">Products:</h4>
                <?php
                }
                ?>
                <!-- List of products inside the order card -->
                <div class="flex items-center mt-2">
                    <img src="<?= $fetch_order['image_url']; ?>" alt="Product Image" class="w-16 h-16 object-cover rounded mr-4">
                    <div>
                        <p class="text-md"><?= $fetch_order['product_name']; ?></p>
                        <p class="text-sm">Quantity: <?= $fetch_order['quantity']; ?></p>
                        <p class="text-sm">Price: $<?= number_format($fetch_order['price'], 2); ?></p>
                    </div>
                </div>
                <?php
            }
            // Close the last card div and form
            echo '</div></form>';
        } else {
            echo '<p class="empty">No orders found!</p>';
        }
        $select_order->close();
        ?>
    </div>

</html>
