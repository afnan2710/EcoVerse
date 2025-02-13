<?php
// subscribe_success.php

// Start the session to store the email if needed
session_start();

include "connect.php"; 
$email = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    // Get the email from the form submission
    $email = htmlspecialchars($_POST['email']);
    
    // You can store the email in the session if needed
    $_SESSION['subscribed_email'] = $email;

    // Prepare and bind the SQL statement to insert the email into the database
    $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
    $stmt->bind_param("s", $email); // "s" means the parameter is a string
    
    // Execute the statement
    if ($stmt->execute()) {
        // Subscription successful
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement and the connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to the index page if accessed directly
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Success</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F0F4F8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 0 100px;
            text-align: center;
            /* Removed overflow:hidden */
        }
        .container {
            background-color: lightgrey;
            padding: 100px;
            border-radius: 50px;
            box-shadow: 0 50px 800px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%;
            opacity: 0; /* Initially hidden */
            transform: translateY(50px); /* Start below */
            animation: fadeIn 0.8s forwards, slideUp 0.8s forwards; /* Multiple animations */
            animation-delay: 0.3s; /* Delay before animation starts */
        }
        .title-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo {
            width: 80px; /* Adjust size of the logo */
            height: 80px;
            margin-bottom: 16px; /* Space between logo and text */
            opacity: 0; /* Initially hidden */
            transform: translateY(20px); /* Start below */
            animation: fadeIn 0.5s forwards, slideUp 0.5s forwards; /* Animation for logo */
            animation-delay: 0.4s; /* Delay for logo */
        }
        .title {
            opacity: 0; /* Initially hidden */
            transform: translateY(20px); /* Start below */
            animation: fadeIn 0.5s forwards, slideUp 0.5s forwards; /* Multiple animations */
            animation-delay: 0.5s; /* Delay before title appears */
        }
        .description {
            opacity: 0; /* Initially hidden */
            transform: translateY(20px); /* Start below */
            animation: fadeIn 0.5s forwards, slideUp 0.5s forwards; /* Multiple animations */
            animation-delay: 0.7s; /* Delay before description appears */
        }
        .go-back-button {
            padding: 12px 24px;
            background-color: #2D3748; /* Dark Gray */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 16px;
        }
        .go-back-button:hover {
            background-color: #1A202C; /* Darker Gray */
            transform: translateY(-5px);
        }
        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 50px;
            margin-top: 50px;
        }
        .option-card {
            background-color: #F0F4F8;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            opacity: 0; /* Initially hidden */
            transform: translateY(20px); /* Start below */
            animation: fadeIn 0.5s forwards, slideUp 0.5s forwards; /* Multiple animations */
            animation-delay: 0.8s; /* Delay for the cards to appear */
        }
        .option-card:nth-child(1) { animation-delay: 0.8s; }
        .option-card:nth-child(2) { animation-delay: 1.0s; }
        .option-card:nth-child(3) { animation-delay: 1.2s; }

        .option-card h3 {
            font-size: 1.5rem;
            font-weight: 1000;
            margin-bottom: 16px;
        }
        .option-button {
            padding: 12px 24px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 16px;
        }
        .donate-button { background-color: #48BB78; }
        .donate-button:hover { background-color: #38A169; }
        .network-button { background-color: #4299E1; }
        .network-button:hover { background-color: #3182CE; }
        .partner-button { background-color: #ED8936; }
        .partner-button:hover { background-color: #DD6B20; }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); }
            to { transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title-container">
            <img src="https://media.istockphoto.com/id/1094780808/vector/approval-symbol-check-mark-in-a-circle-drawn-by-hand-vector-green-sign-ok-approval-or.jpg?s=612x612&w=0&k=20&c=0mlB50r769kHmLkVcq_HpdNFGdHIA_Cu_tPqN4IKZbc=" alt="Success Logo" class="logo">
            <h1 class="text-6xl font-extrabold mb-10 text-plant-primary title">SUCCESS!</h1>
        </div>
        <p class="text-lg mb-6 text-gray-1000 description">
            Watch your inbox for the latest Arbor Day newsletter and other tree planting updates — as well as how you can get involved in greening our world.
        </p>
        <form action="index.php" method="GET">
            <button type="submit" class="go-back-button"><-Go Back</button>
        </form>

        <!-- Additional Options Section -->
        <div class="options-grid mt-10">
            <!-- Donate Trees Option -->
            <div class="option-card">
                <h3>Donate Trees</h3>
                <p>Contribute to tree planting efforts and make a global impact by donating trees.</p>
                <form action="donate_trees.php" method="GET">
                    <button type="submit" class="option-button donate-button">Plant Trees Now</button>
                </form>
            </div>
            <!-- Planting Network Option -->
            <div class="option-card">
                <h3>Planting Network</h3>
                <p>Join our global network of tree planters and make a difference in your community.</p>
                <form action="join_network.php" method="GET">
                    <button type="submit" class="option-button network-button">Join Our Network</button>
                </form>
            </div>
            <!-- Corporate Partnership Option -->
            <div class="option-card">
                <h3>Corporate Partnership</h3>
                <p>Partner with us to promote sustainability and make a lasting impact together.</p>
                <form action="become_partner.php" method="GET">
                    <button type="submit" class="option-button partner-button">Become a Partner</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>