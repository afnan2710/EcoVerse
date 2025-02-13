<?php
include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecoverse Advertisements</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        body {
            font-family: 'Poppins', sans-serif;
        }
        .advertisement-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .advertisement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .bg-gradient {
            background: linear-gradient(135deg, #4CAF50, #6BCF80);
        }
    </style>
</head>
<body class="bg-green-50 text-gray-800">

    <!-- Header -->
    <header class="bg-gradient text-white py-16 text-center">
        <h1 class="text-5xl font-extrabold">Best Deals!!!</h1>
        <p class="mt-4 text-lg opacity-90">Explore our latest offers and eco-friendly products!</p>
    </header>

    <!-- Advertisements Section -->
    <section class="py-16 px-6">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-4xl font-bold text-center text-green-700">Current Promotions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-10">
                <!-- Advertisement 1 -->
                <div class="advertisement-card">
                    <h3 class="text-2xl font-semibold mb-2">50% Off on Rare Trees!</h3>
                    <p>Discover our unique selection of rare trees available at half price. Limited time offer!</p>
                    <a href="#" class="mt-4 inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Shop Now</a>
                </div>
                <!-- Advertisement 2 -->
                <div class="advertisement-card">
                    <h3 class="text-2xl font-semibold mb-2">Free Shipping on Orders Over $50</h3>
                    <p>Enjoy free shipping for all orders over $50. Get your favorite plants delivered to your door!</p>
                    <a href="#" class="mt-4 inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Learn More</a>
                </div>
                <!-- Advertisement 3 -->
                <div class="advertisement-card">
                    <h3 class="text-2xl font-semibold mb-2">Join Our Tree Planting Event</h3>
                    <p>Be part of our community tree planting event and contribute to a greener planet. Sign up today!</p>
                    <a href="#" class="mt-4 inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Register Now</a>
                </div>
                <!-- Advertisement 4 -->
                <div class="advertisement-card">
                    <h3 class="text-2xl font-semibold mb-2">Buy One, Get One Free on Selected Plants</h3>
                    <p>Take advantage of our BOGO offer on selected plants. Perfect time to expand your garden!</p>
                    <a href="#" class="mt-4 inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Grab the Offer</a>
                </div>
                <!-- Advertisement 5 -->
                <div class="advertisement-card">
                    <h3 class="text-2xl font-semibold mb-2">Exclusive Discounts for Members</h3>
                    <p>Join our membership program for exclusive discounts and early access to new arrivals!</p>
                    <a href="#" class="mt-4 inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Become a Member</a>
                </div>
                <!-- Advertisement 6 -->
                <div class="advertisement-card">
                    <h3 class="text-2xl font-semibold mb-2">Seasonal Sales on Gardening Supplies</h3>
                    <p>Stock up on gardening supplies during our seasonal sales. Quality products at great prices!</p>
                    <a href="#" class="mt-4 inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">View Supplies</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-700 text-white py-8 text-center">
        <p class="text-sm">&copy; 2024 Ecoverse. All rights reserved.</p>
        <div class="flex justify-center space-x-6 mt-4">
            <a href="https://www.facebook.com/shafinahmed.shafin.7" class="text-green-300 hover:text-green-500">Facebook</a>
            <a href="https://www.instagram.com/krishanuabir/" class="text-green-300 hover:text-green-500">Instagram</a>
            <a href="https://x.com/afnanshahriar27" class="text-green-300 hover:text-green-500">Twitter</a>
        </div>
    </footer>

</body>
</html>