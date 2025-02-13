<?php

include 'connect.php';

$searchQuery = '';
$filterQuery = ''; // Initialize filter query

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['search']);
}

if (isset($_GET['filter']) && !empty($_GET['filter'])) {
    $filterQuery = $conn->real_escape_string($_GET['filter']);
}

// Modify the query to filter products by product type (if filter is set)
$query = "SELECT * FROM products WHERE name LIKE '%$searchQuery%'";

if (!empty($filterQuery)) {
    $query .= " AND product_type = '$filterQuery'";
}

$query .= " ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$events_result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");

if (!$events_result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoVerse</title>
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
        .cards-container {
            margin: 0 50px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        .card {
            width: calc(33.333% - 20px);
            box-sizing: border-box;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-20px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .card img {
            width: 100%;
        }
        #chatButton {
            cursor: pointer;
        }
        #chatForm {
            width: 300px;
        }
        #chatForm input, #chatForm textarea {
            font-size: 14px;
        }
        .event-ticker-container {
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px 0;
            overflow: hidden;
            position: relative;
        }

        .event-ticker {
            display: flex;
            white-space: nowrap;
            animation: scroll 20s linear infinite;
        }

        .event-item {
            display: inline-block;
            padding: 0 30px;
            font-size: 18px;
            color: #333;
            font-weight: 500;
        }

        @keyframes scroll {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        #goTopButton {
            font-size: 24px;
            cursor: pointer;
            transition: opacity 0.3s ease-in-out;
            width: 50px;
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            border-radius: 50%;
            background-color: #3b82f6;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease-in-out;
        }

        #goTopButton:hover {
            background-color: #1e3a8a;
        }
        .upload-container {
            position: relative;
        }

        .upload-input {
            display: block;
            padding: 10px;
            border: 2px dashed #4a90e2; /* Change to a color of your choice */
            background-color: #f7f7f7; /* Light background */
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .upload-input:focus {
            outline: none;
            border-color: #007bff; /* Change to a focus color */
        }

        .file-selected {
            margin-top: 5px;
            color: #666; /* Color for the file name */
            font-size: 14px;
        }

    </style>
</head>
<?php include 'navbar.php'; ?>
<?php include 'slider.php'; ?>

<body class="font-pop">
    <header class="md:container md:mx-auto">
        <div class="join flex justify-center my-4 gap-5">
            <a href="plant_exchange.php" class="btn btn-outline btn-error mx-2">Plant Exchange</a>
            <a href="blog.php" class="btn btn-outline btn-success mx-2">Blog and Article</a>
            <a href="advertisement.php"><button class="btn btn-outline btn-info">Best Deals</button></a>
            <a href="eco-packaging.php"><button class="btn btn-outline btn-success mx-2">ECO Friendly Packaging</button></a>
            <a href="success_story.php" class="btn btn-outline btn-gray mx-2">Customer Success Stories</a>
        </div>
    </header>

    <!-- Event Ticker -->
    <div class="event-ticker-container">
        <div class="event-ticker">
            <?php while ($row = $events_result->fetch_assoc()): ?>
                <div class="event-item">
                    <strong><?= htmlspecialchars($row['event_name']) ?>:</strong>
                    <?= htmlspecialchars($row['event_details']) ?> |
                    Date: <?= htmlspecialchars($row['event_date']) ?> |
                    <a href="<?= htmlspecialchars($row['meeting_link']) ?>" target="_blank">Join</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Newsletter Subscription Section -->
    <section style="background: linear-gradient(135deg, #2e7d32, #a5d6a7);" class="text-black py-8 mt-8"><div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Get the Latest News and Info Right in Your Inbox</h2>
                <p class="mb-4">
                    By subscribing, you will receive stories illustrating the power of trees, the latest news and updates, and how we can make a positive impact together.
                </p>
                <form action="subscribe_success.php" method="POST" class="flex flex-col sm:flex-row items-center gap-4 justify-center">
                    <input list="email-suggestions" name="email" type="email" placeholder="Write Your Email Address" required
                        class="p-3 w-48 sm:w-auto sm:flex-grow text-gray-800 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <datalist id="email-suggestions">
                        <option value="user@example.com">
                        <option value="user2@example.com">
                    </datalist>
                    <button type="submit" class="px-6 py-3 bg-blue-500 text-white font-semibold rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        SUBSCRIBE
                    </button>
                </form>
            </div>
        </section>

    <!-- Filter and Search Section -->
    <div class="container mx-auto mt-5">
        <form method="GET" action="index.php" class="flex justify-center items-center gap-4">
            <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search for products..." class="input input-bordered w-full max-w-xs">
            <select name="filter" class="select select-bordered">
                <option value="">All Categories</option>
                <option value="Indoor" <?= ($filterQuery === 'Indoor') ? 'selected' : '' ?>>Indoor</option>
                <option value="Outdoor" <?= ($filterQuery === 'Outdoor') ? 'selected' : '' ?>>Outdoor</option>
                <option value="Medicinal" <?= ($filterQuery === 'Medicinal') ? 'selected' : '' ?>>Medicinal</option>
                <option value="Fertilizer" <?= ($filterQuery === 'Fertilizer') ? 'selected' : '' ?>>Fertilizer</option>
                <option value="Tools" <?= ($filterQuery === 'Tools') ? 'selected' : '' ?>>Tools</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <!-- Product Cards Section -->
    <main>
        <div class="cards-container grid grid-cols-3 mt-10 ml-[100px]">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card w-96 bg-base-100 shadow-xl mt-5 mb-10">
                    <figure>
                        <img src="<?= htmlspecialchars($row['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                    </figure>
                    <div class="card-body">
                        <h2 class="card-title"><?= htmlspecialchars($row['name']) ?></h2>
                        <p><?= $row['description'] ?></p>
                        <div class="card-actions flex items-center justify-between mt-5">
                            <p class="text-2xl"><strong>$<?= htmlspecialchars($row['price']) ?></strong></p>
                            <div class="mr-4">
                                <form action="add_to_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['product_id']) ?>">
                                    <input type="hidden" name="name" value="<?= htmlspecialchars($row['name']) ?>">
                                    <input type="hidden" name="image_url" value="<?= htmlspecialchars($row['image_url']) ?>">
                                    <input type="hidden" name="price" value="<?= htmlspecialchars($row['price']) ?>">
                                    <input type="number" name="qty" placeholder="qty" class="input input-bordered input-sm w-full max-w-xs mr-3" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                                    <button type="submit" class="btn btn-info">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

<!-- Review and Plant Exchange Forms Section -->
<section class="bg-gray-100 py-10 mt-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Review Form -->
            <div class="w-full md:w-1/2">
                <h2 class="text-3xl font-bold mb-6 text-center">Leave a Review</h2>
                <form action="submit_review.php" method="POST" class="p-5 bg-white rounded-lg shadow-lg">
                    <div class="mb-4">
                        <label for="name" class="block text-sm mb-2">Your Name</label>
                        <input type="text" name="name" id="name" class="input input-bordered w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm mb-2">Email Address</label>
                        <input type="email" name="email" id="email" class="input input-bordered w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="contact" class="block text-sm mb-2">Contact Number</label>
                        <input type="text" name="contact" id="contact" class="input input-bordered w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="rating" class="block text-sm mb-2">Rating</label>
                        <div class="rating flex items-center">
                            <input type="radio" name="rating" value="1" class="mask mask-star" required>
                            <input type="radio" name="rating" value="2" class="mask mask-star" required>
                            <input type="radio" name="rating" value="3" class="mask mask-star" required>
                            <input type="radio" name="rating" value="4" class="mask mask-star" required>
                            <input type="radio" name="rating" value="5" class="mask mask-star" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block text-sm mb-2">Comment</label>
                        <textarea name="comment" id="comment" class="textarea textarea-bordered w-full" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Submit Review</button>
                </form>
            </div>

            <!-- Plant Exchange Form -->
            <div class="w-full md:w-1/2">
                <h2 class="text-3xl font-bold mb-6 text-center">Want to Exchange your Plant?</h2>
                <form action="submit_exchange.php" method="POST" class="p-5 bg-white rounded-lg shadow-lg" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="exchange_name" class="block text-sm mb-2">Your Name</label>
                        <input type="text" name="exchange_name" id="exchange_name" class="input input-bordered w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="exchange_email" class="block text-sm mb-2">Email Address</label>
                        <input type="email" name="exchange_email" id="exchange_email" class="input input-bordered w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="exchange_contact" class="block text-sm mb-2">Contact Number</label>
                        <input type="text" name="exchange_contact" id="exchange_contact" class="input input-bordered w-full" required>
                    </div>
                    <div class="mb-4 upload-container">
                        <label for="plant_image" class="block text-sm mb-2">Upload Plant Image</label>
                        <div class="file-upload-container">
                            <input type="file" name="plant_image" id="plant_image" class="file-upload-input" required>
                            <label for="plant_image" class="file-upload-label">Choose file</label>
                            <button type="button" class="apply-button">Upload</button>
                        </div>
                        <span class="file-selected">No file chosen</span>
                    </div>
                    <div class="mb-4">
                        <label for="plant_details" class="block text-sm mb-2">Plant Details</label>
                        <textarea name="plant_details" id="plant_details" class="textarea textarea-bordered w-full" rows="2" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="exchange_type" class="block text-sm mb-2">Exchange Type</label>
                        <select name="exchange_type" id="exchange_type" class="select select-bordered w-full" required onchange="togglePriceField()">
                            <option value="without money">Without Money</option>
                            <option value="money">With Money</option>
                        </select>
                    </div>
                    <div id="selling_price_field" class="mb-4 hidden">
                        <label for="selling_price" class="block text-sm mb-2">Selling Price</label>
                        <input type="number" name="selling_price" id="selling_price" class="input input-bordered w-full">
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Submit Exchange Request</button>
                </form>
            </div>
        </div>
    </div>
</section>


    <script>
        function togglePriceField() {
            const exchangeType = document.getElementById('exchange_type').value;
            const priceField = document.getElementById('selling_price_field');
            if (exchangeType === 'money') {
                priceField.classList.remove('hidden');
            } else {
                priceField.classList.add('hidden');
            }
        }
    </script>

    <!-- Chat Button -->
<button id="chatButton" class="fixed bottom-5 right-5 bg-green-500 text-white p-3 rounded-full shadow-lg">
    Live Chat
</button>

<!-- Chat Modal Form -->
<div id="chatForm" class="fixed bottom-20 right-5 bg-white p-5 rounded-lg shadow-lg hidden">
    <button id="closeChat" class="absolute top-1 right-2 text-red-500 font-bold">X</button>
    <h2 id="chatHeading" class="text-lg font-semibold mb-3">Ask a Question</h2>
    
    <form id="chatFormSubmit" action="submit_chat.php" method="POST">
        <div id="initialChatForm">
            <label for="user_name" class="block text-sm mb-1">Your Name</label>
            <input type="text" name="user_name" id="user_name" class="w-full mb-3 p-2 border rounded" required>

            <label for="user_ehmail" class="block text-sm mb-1">Email Address</label>
            <input type="email" name="user_email" id="user_email" class="w-full mb-3 p-2 border rounded" required>

            <label for="user_phone" class="block text-sm mb-1">Contact Number</label>
            <input type="text" name="user_phone" id="user_phone" class="w-full mb-3 p-2 border rounded">

            <label for="user_message" class="block text-sm mb-1">Your Message</label>
            <textarea name="user_message" id="user_message" class="w-full mb-3 p-2 border rounded" required></textarea>

            <button type="submit" class="bg-green-500 text-white p-2 rounded">Send</button>
        </div>
    </form>

    <!-- Chat Window After First Message -->
    <div id="liveChatWindow" class="hidden">
        <h3 class="text-md font-semibold">Chat</h3>
        <div id="chatMessages" class="h-60 overflow-y-scroll border rounded mb-3 p-3 bg-gray-100"></div>
        <textarea id="userReply" class="w-full p-2 border rounded mb-2" rows="2" placeholder="Type your message..."></textarea>
        <button id="sendReplyBtn" class="bg-green-500 text-white p-2 rounded">Send Reply</button>
    </div>
</div>

<button id="goTopButton" class="fixed bottom-20 right-5 bg-blue-500 text-white p-3 rounded-full shadow-lg hidden">^</button>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    const chatButton = document.getElementById('chatButton');
    const chatForm = document.getElementById('chatForm');
    const chatFormSubmit = document.getElementById('chatFormSubmit');
    const liveChatWindow = document.getElementById('liveChatWindow');
    const chatMessages = document.getElementById('chatMessages');
    const sendReplyBtn = document.getElementById('sendReplyBtn');
    const userReply = document.getElementById('userReply');

    chatButton.addEventListener('click', () => {
        chatForm.classList.toggle('hidden');
    });

    document.getElementById('closeChat').addEventListener('click', () => {
        chatForm.classList.add('hidden');
    });

    chatFormSubmit.addEventListener('submit', function(event) {
        event.preventDefault();
        document.getElementById('initialChatForm').classList.add('hidden');
        liveChatWindow.classList.remove('hidden');
        chatMessages.innerHTML += '<div class="p-2 bg-green-200 rounded mb-2">You: ' + document.getElementById('user_message').value + '</div>';
    });

    sendReplyBtn.addEventListener('click', () => {
        const replyMessage = userReply.value;
        if (replyMessage.trim()) {
            chatMessages.innerHTML += '<div class="p-2 bg-gray-300 rounded mb-2">Consultant: ' + replyMessage + '</div>';
            userReply.value = '';
        }
    });

    document.getElementById('chatFormSubmit').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('submit_chat.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error); // Display any error messages
            } else {
                document.getElementById('initialChatForm').classList.add('hidden');
                document.getElementById('liveChatWindow').classList.remove('hidden');
                alert('Message sent successfully.'); // Or update the chat window
            }
        })
        .catch(error => console.error('Error:', error));
    });


    const goTopButton = document.getElementById('goTopButton');

    // Show or hide the button depending on the scroll position
    window.onscroll = function() {
        // If user scrolls more than 300px down, show the button
        if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
            goTopButton.classList.remove('hidden');
        } else {
            goTopButton.classList.add('hidden'); // Keep it hidden if less than 300px scrolled
        }
    };

    // Scroll to top when the button is clicked
    goTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    document.getElementById('plant_image').addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
        document.querySelector('.file-selected').textContent = fileName;
    });


</script>
<?php include 'footer.php'; ?>

</body>
</html>
