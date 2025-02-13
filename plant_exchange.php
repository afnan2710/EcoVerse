<?php
include('connect.php');

$query = "SELECT * FROM plant_exchange WHERE status = 'approved'";
$result = $conn->query($query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Exchange Offers</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: black;
            margin: 0;
            padding: 0;
            color: #2c3e50;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
        }

        h1 {
            text-align: center;
            font-size: 2.8em;
            color: white;
            margin-bottom: 40px;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 2px solid #eaeaea;
        }

        .card-content {
            padding: 20px;
            text-align: center;
        }

        .card-content h2 {
            font-size: 1.8em;
            color: #34495e;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .card-content p {
            color: #7f8c8d;
            font-size: 1.1em;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .price, .exchange-type, .contact {
            display: block;
            margin: 10px 0;
            font-size: 1.2em;
        }

        .exchange-type {
            background-color: #1abc9c;
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 1em;
            font-weight: 500;
            text-transform: capitalize;
            display: inline-block;
        }

        .price {
            font-size: 1.5em;
            color: #e74c3c;
            font-weight: bold;
            margin-top: 15px;
        }

        .contact {
            font-size: 1.1em;
            color: #2980b9;
            font-weight: 500;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .contact i {
            color: #e67e22;
            font-size: 1.4em;
        }

        .no-offers {
            text-align: center;
            font-size: 1.3em;
            color: #95a5a6;
            margin-top: 30px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card-content {
                padding: 15px;
            }

            .card-content h2 {
                font-size: 1.4em;
            }

            .price {
                font-size: 1.2em;
            }

            .exchange-type {
                padding: 5px 12px;
            }
        }

    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <h1>Available Plant Exchange Offers</h1>
    
    <div class="grid-container">
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="card">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Plant Image">
                    <div class="card-content">
                        <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                        <p><?php echo htmlspecialchars($row['plant_details']); ?></p>
                        <span class="exchange-type"><?php echo ucfirst($row['exchange_type']); ?></span>
                        <?php if ($row['exchange_type'] == 'money') { ?>
                            <span class="price">$<?php echo number_format($row['selling_price'], 2); ?></span>
                        <?php } ?>
                        <span class="contact">
                            <i class="fas fa-envelope"></i><?php echo htmlspecialchars($row['email']); ?> |
                            <i class="fas fa-phone-alt"></i><?php echo htmlspecialchars($row['contact']); ?>
                        </span>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="no-offers">No exchange offers available at the moment. Please check back later!</p>
        <?php } ?>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
