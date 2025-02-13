<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

function calculate_total_cart_value($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['qty'];
    }
    return $total;
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $_SESSION['cartTotal'] = calculate_total_cart_value($_SESSION['cart']);
} else {
    $_SESSION['cartTotal'] = 0;
}

$discount = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_coupon'])) {
    $coupon_code = $_POST['coupon'];

    if (!empty($coupon_code)) {
        include 'connect.php'; 
        $stmt = $conn->prepare("SELECT * FROM coupons WHERE code = ? AND expiry_date >= CURDATE()");
        $stmt->bind_param('s', $coupon_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $coupon = $result->fetch_assoc();

            if ($coupon['usage_limit'] > 0) {
                $discount_percentage = $coupon['discount_percentage'];
                $max_discount_amount = $coupon['max_discount_amount'];

                $discount = ($discount_percentage / 100) * $_SESSION['cartTotal'];

                if ($discount > $max_discount_amount) {
                    $discount = $max_discount_amount;
                }
                $stmt = $conn->prepare("UPDATE coupons SET usage_limit = usage_limit - 1 WHERE coupon_id = ?");
                $stmt->bind_param('i', $coupon['coupon_id']);
                $stmt->execute();

                echo "<script>alert('Coupon applied!');</script>";
            } else {
                echo "<script>alert('Coupon usage limit reached.');</script>";
            }
        } else {
            echo "<script>alert('Invalid or expired coupon.');</script>";
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_order'])) {
    $user_id = $_SESSION['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $card_info = $_POST['card_info'];
    $card_expiry = $_POST['card_expiry'];
    $card_cvc = $_POST['card_cvc'];
    $total = ($_SESSION['cartTotal'] - $discount) + 5;

    include 'connect.php';
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, address, country, total, status) VALUES (?, ?, ?, ?, ?, 'Order Received')");
        $stmt->bind_param("isssd", $user_id, $name, $address, $country, $total);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id; 

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($_SESSION['cart'] as $item) {
                $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['qty'], $item['price']);
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting order item: " . $stmt->error);
                }
            }

            $conn->commit();
            unset($_SESSION['cart']);
            unset($_SESSION['cartTotal']);

            echo "<script>alert('Order confirmed. Get your order receipt from profile management section.'); window.location.href='profile.php';</script>";
            exit();
        } else {
            throw new Exception("Error inserting order details: " . $stmt->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: {$e->getMessage()}');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Details</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    <style>
        .font-pop {
            font-family: 'Poppins', sans-serif;
        }

        .custom-hr {
            width: 1288px;
            height: 1px;
            background: #1E1E1E;
            border: none;
            margin: 0;
        }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body class="w-[full] h-[full] font-pop bg-no-repeat bg-cover bg-left" style="background-image: url(backdrop-green-leaves.jpg);">
    <header class="md:container md:mx-auto">
        <div class="join flex justify-center mt-5 gap-5">
            <a href="landing-page.php"><button class="btn btn-outline btn-info">Popular</button></a>
            <a href="Indoor.php"><button class="btn btn-outline btn-success">Indoor</button></a>
            <a href="outdoor.php"><button class="btn btn-outline btn-warning">Outdoor</button></a>
        </div>
    </header>
    <main class="h-screen flex items-center justify-center">
        <div class="flex gap-20">
            <div class="card w-[500px] bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Order Details</h2>
                    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-5 mt-5">
                                    <div class="avatar">
                                        <div class="w-12 mask mask-squircle">
                                            <img src="<?= htmlspecialchars($item['product_image']) ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <p><?= htmlspecialchars($item['name']) ?></p>
                                        <p>Qty: <span><?= htmlspecialchars($item['qty']) ?></span></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-bold text-xl">$<?= number_format($item['price'] * $item['qty'], 2) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Your cart is empty.</p>
                    <?php endif; ?>

                    <div class="flex justify-between mt-5">
                        <div>
                            <p>Subtotal</p>
                        </div>
                        <div>
                            <p class="font-bold text-xl">$<?= number_format($_SESSION['cartTotal'], 2) ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-400">Shipping <br>Within 3-5 working days</p>
                        </div>
                        <div>
                            <p class="font-bold text-xl">$5.00</p>
                        </div>
                    </div>

                    <!-- Coupon Section -->
                    <form method="POST" action="checkout.php">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-bold text-xl">Apply Coupon</p>
                            </div>
                            <div class="join">
                                <input type="text" placeholder="Enter coupon" name="coupon" class="input input-bordered join-item" />
                                <button type="submit" name="apply_coupon" class="btn btn-success join-item">Apply</button>
                            </div>
                        </div>

                        <?php if ($discount > 0): ?>
                            <div class="flex justify-between mt-5">
                                <div>
                                    <p class="font-bold text-xl text-green-600">Discount Applied</p>
                                </div>
                                <div>
                                    <p class="font-bold text-xl text-green-600">- $<?= number_format($discount, 2) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr>
                        <div class="flex justify-between">
                            <div>
                                <p>Total</p>
                            </div>
                            <div>
                                <p class="font-bold text-xl">$<?= number_format($_SESSION['cartTotal'] - $discount + 5, 2) ?></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Shipping Address Form -->
            <div class="card w-[500px] bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Shipping Address</h2>
                    <form method="POST" action="checkout.php">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text">Full Name</span>
                            </label>
                            <input type="text" name="name" placeholder="Enter your full name" class="input input-bordered w-full" required />
                        </div>
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text">Shipping Address</span>
                            </label>
                            <input type="text" name="address" placeholder="Enter your shipping address" class="input input-bordered w-full" required />
                        </div>
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text">Country</span>
                            </label>
                            <input type="text" name="country" placeholder="Enter your country" class="input input-bordered w-full" required />
                        </div>
                        <div class="form-control w-full mt-5">
                            <label class="label">
                                <span class="label-text">Card Information</span>
                            </label>
                            <input type="text" name="card_info" placeholder="Card Number" class="input input-bordered w-full" required />
                        </div>
                        <div class="flex justify-between mt-2">
                            <div class="form-control w-[200px]">
                                <label class="label">
                                    <span class="label-text">Expiry Date</span>
                                </label>
                                <input type="text" name="card_expiry" placeholder="MM/YY" class="input input-bordered" required />
                            </div>
                            <div class="form-control w-[200px]">
                                <label class="label">
                                    <span class="label-text">CVC</span>
                                </label>
                                <input type="text" name="card_cvc" placeholder="CVC" class="input input-bordered" required />
                            </div>
                        </div>
                        <button type="submit" name="confirm_order" class="btn btn-success btn-block mt-5">Confirm Order</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>