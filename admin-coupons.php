<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_coupon'])) {
    $code = $_POST['code'];
    $discount_percentage = $_POST['discount_percentage'];
    $max_discount_amount = $_POST['max_discount_amount'];
    $expiry_date = $_POST['expiry_date'];
    $usage_limit = $_POST['usage_limit'];

    $stmt = $conn->prepare("INSERT INTO coupons (code, discount_percentage, max_discount_amount, expiry_date, usage_limit) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sddsi", $code, $discount_percentage, $max_discount_amount, $expiry_date, $usage_limit);
    $stmt->execute();
    $stmt->close();
    echo "<p class='alert alert-success'>Coupon added successfully!</p>";
}

if (isset($_GET['delete'])) {
    $coupon_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM coupons WHERE coupon_id = ?");
    $stmt->bind_param("s", $coupon_id);
    $stmt->execute();
    $stmt->close();

    header('Location: admin-coupons.php');
    exit();
}

$result = $conn->query("SELECT * FROM coupons ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coupons</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'clifford': '#da373d',
                        'plant-primary': '#E76F51',
                        'plant-primary-bg': 'rgba(231, 111, 81, 0.10)',
                    }
                }
            }
        };
    </script>
    <style>
        .coupon-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .coupon-card {
            background: #f4f4f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .coupon-form {
            width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .remaining-time {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body class="font-pop">

<?php include 'admin-header.php'; ?>

<div class="container mx-auto px-4 mt-5">

    <!-- Display Existing Coupons -->
    <div class="coupon-container mt-10 ml-10">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="coupon-card">
                <h3 class="text-2xl font-bold"><?= htmlspecialchars($row['code']) ?></h3>
                <p>Discount: <?= htmlspecialchars($row['discount_percentage']) ?>%</p>
                <p>Max Discount: $<?= htmlspecialchars($row['max_discount_amount']) ?></p>
                <p>Usage Limit: <?= htmlspecialchars($row['usage_limit']) ?></p>
                <p>Expires: <?= htmlspecialchars($row['expiry_date']) ?></p>
                <p>Remaining Time: <span class="remaining-time" data-expiry="<?= htmlspecialchars($row['expiry_date']) ?>"></span></p>
                <a href="admin-coupons.php?delete=<?= htmlspecialchars($row['coupon_id']) ?>" class="btn btn-error mt-3">Delete</a>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Add New Coupon Form -->
    <div class="coupon-form">
        <h2 class="text-4xl mb-5">Add New Coupon</h2>
        <form action="admin-coupons.php" method="post">
            <div class="mb-3">
                <label for="code" class="block">Coupon Code</label>
                <input type="text" name="code" id="code" class="input input-bordered w-full" required>
            </div>
            <div class="mb-3">
                <label for="discount_percentage" class="block">Discount Percentage</label>
                <input type="number" step="0.01" name="discount_percentage" id="discount_percentage" class="input input-bordered w-full" required>
            </div>
            <div class="mb-3">
                <label for="max_discount_amount" class="block">Max Discount Amount</label>
                <input type="number" step="0.01" name="max_discount_amount" id="max_discount_amount" class="input input-bordered w-full">
            </div>
            <div class="mb-3">
                <label for="expiry_date" class="block">Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date" class="input input-bordered w-full" required>
            </div>
            <div class="mb-3">
                <label for="usage_limit" class="block">Usage Limit</label>
                <input type="number" name="usage_limit" id="usage_limit" class="input input-bordered w-full">
            </div>
            <button type="submit" name="create_coupon" class="btn btn-primary w-full">Create Coupon</button>
        </form>
    </div>
</div>

<script>
    const timers = document.querySelectorAll('.remaining-time');
    timers.forEach(timer => {
        const expiryDate = new Date(timer.getAttribute('data-expiry')).getTime();
        setInterval(() => {
            const now = new Date().getTime();
            const timeRemaining = expiryDate - now;
            if (timeRemaining < 0) {
                timer.textContent = 'Expired';
            } else {
                const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
                timer.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }
        }, 1000);
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>
