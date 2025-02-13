<?php
include 'connect.php';

$result = $conn->query("SELECT * FROM coupons WHERE expiry_date >= CURDATE() ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Offers</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif']
                    },
                    colors: {
                        'primary': '#3b7d1f',
                        'background': '#f5f5f5',
                        'offer-bg': '#fffbe6',
                    },
                    backgroundImage: {
                        'offers-bg': "url('backdrop-green-leaves.jpg')"
                    }
                }
            }
        };
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('backdrop-green-leaves.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .offer-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 40px;
        }

        .offer-card {
            background-color: #c8553d;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            width: 280px;
        }

        .offer-card:hover {
            transform: translateY(-10px);
        }

        .btn-apply {
            background-color: green;
            color: white;
            border-radius: 5px;
            padding: 10px;
            margin-top: 15px;
            transition: background-color 0.3s;
        }

        .btn-apply:hover {
            background-color: darkgreen;
        }

        h1 {
            color: #f9f61f;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .offer-card h2, .offer-card p {
            color: black;
        }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body class="font-poppins bg-background">
    <div class="container mx-auto">
        <h1 class="text-5xl text-center my-10 font-bold">Latest Offers!!!</h1>

        <div class="offer-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="offer-card p-5 bg-offer-bg rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold"><?= htmlspecialchars($row['code']) ?></h2>
                    <p class="text-lg">Discount: <span class="font-semibold"><?= htmlspecialchars($row['discount_percentage']) ?>%</span></p>
                    <p class="text-md">Max Discount: <span class="font-semibold">$<?= htmlspecialchars($row['max_discount_amount']) ?></span></p>
                    <p class="text-sm">Usage Limit: <?= htmlspecialchars($row['usage_limit']) ?> times</p>
                    <p class="text-sm">Expires: <?= htmlspecialchars($row['expiry_date']) ?></p>
                    <p class="text-sm">Time Remaining: <span id="timer-<?= htmlspecialchars($row['coupon_id']) ?>"></span></p>

                    <button class="btn-apply">Apply Coupon</button>

                    <script>
                        (function() {
                            var expiryDate = new Date('<?= htmlspecialchars($row['expiry_date']) ?>').getTime();
                            var timerId = "timer-<?= htmlspecialchars($row['coupon_id']) ?>";
                            var countdown = setInterval(function() {
                                var now = new Date().getTime();
                                var timeLeft = expiryDate - now;

                                var days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                                var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                                if (timeLeft < 0) {
                                    clearInterval(countdown);
                                    document.getElementById(timerId).innerHTML = "Expired";
                                } else {
                                    document.getElementById(timerId).innerHTML = days + "d " + hours + "h "
                                    + minutes + "m " + seconds + "s ";
                                }
                            }, 1000);
                        })();
                    </script>
                </div>

            <?php endwhile; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
