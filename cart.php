<?php

function calculate_total_cart_value($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['qty'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <!-- tailwind & daisyui cdn -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- tailwind custom classes -->
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
    <!-- custom styles -->
    <style>
        .font-pop {
            font-family: 'Poppins', sans-serif;
        }
        .cart-container {
            margin: 50px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .cart-actions form {
            display: inline;
        }
    </style>
</head>
<?php include 'navbar.php'; ?>

<body class="w-[full] h-[full] font-pop">
    <header class="md:container md:mx-auto">
        <h1 class="text-3xl font-bold mt-10 text-center text-green-700">Your Shopping Cart</h1>
    </header>

    <main class="cart-container">
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <p><?= htmlspecialchars($item['name']) ?></p>
                        <div>
                            <form action="update_cart.php" method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="btn btn-outline btn-warning">-</button>
                            </form>
                            <span>Quantity: <?= htmlspecialchars($item['qty']) ?></span>
                            <form action="update_cart.php" method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="btn btn-outline btn-success">+</button>
                                
                            </form>
                        </div>
                        <p>Price: $<?= number_format($item['price'], 2) ?></p>
                        <p>Total: $<?= number_format($item['price'] * $item['qty'], 2) ?></p>
                        <form action="update_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="btn btn-outline btn-error">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-10">
                <p>Total Cart Value: $<?= number_format(calculate_total_cart_value($_SESSION['cart']), 2) ?></p>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>