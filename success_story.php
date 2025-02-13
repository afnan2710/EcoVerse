<?php
include 'connect.php';

// Fetch all reviews from the database
$query = "SELECT * FROM reviews ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Success Stories - EcoVerse</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .font-pop {
            font-family: 'Poppins', sans-serif;
        }
        .review-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px; /* Add margin for spacing */
            transition: transform 0.2s;
            flex: 1 1 300px; /* Make cards flexible with a minimum width */
        }
        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            background-color: gray;
        }
        .review-card:hover h3 {
            margin-top: 0;
            color: white;
        }
        .review-card:hover p {
            line-height: 1.6;
            color: white;
        }
        .review-card h3 {
            margin-top: 0;
            color: #2b6cb0;
        }
        .review-card p {
            line-height: 1.6;
            color: #4a5568;
        }
        .rating {
            display: inline-flex;
            align-items: center;
            color: black; /* Star color */
        }
        .rating span {
            font-size: 1.5rem; /* Star size */
            margin-right: 2px; /* Space between stars */
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
        }
        header {
            background-color: grey;
            padding: 20px 0;
            border-bottom: 2px solid #e2e8f0;
        }
    </style>
</head>
<?php include 'navbar.php'; ?>

<body class="font-pop">
    <header>
        <h1 class="text-4xl font-bold text-center my-5">Customer Success Stories</h1>
    </header>

    <main class="container mx-auto mt-10">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="review-card">
                <h3 class="text-xl font-semibold"><?= htmlspecialchars($row['name']) ?></h3>
                <p class="rating">
                    <strong>Rating:</strong> 
                    <?php for ($i = 0; $i < $row['rating']; $i++): ?>
                        <span>★</span>
                    <?php endfor; ?>          
                </p>
                <p><strong>Comment:</strong> <?= nl2br(htmlspecialchars($row['comment'])) ?></p>
                <p><small><em>Posted on <?= htmlspecialchars($row['created_at']) ?></em></small></p>
            </div>
        <?php endwhile; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
