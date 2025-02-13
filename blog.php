<?php
session_start();
include 'connect.php';

// Fetch Consultant Blogs from Database
$blogs_result = $conn->query("SELECT blogs.*, consultant.consultant_name FROM blogs JOIN consultant ON blogs.consultant_id = consultant.consultant_id ORDER BY blogs.created_at DESC");

if (!$blogs_result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog and Articles | EcoVerse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        /* Smooth image hover effect */
        .blog-card:hover img {
            transform: scale(1.05);
            filter: brightness(90%);
        }

        .blog-card:hover {
            transform: translateY(-10px);
        }

        .blog-card img {
            transition: transform 0.5s ease, filter 0.3s ease;
        }

        .blog-card {
            transition: transform 0.3s ease;
        }

        /* Footer hover effects */
        footer a {
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #9ae6b4;
        }
    </style>
</head>

<nav class="bg-green-800 p-6">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Left side with logo -->
        <div class="flex items-left">
            <img src="https://img.freepik.com/free-vector/environmental-concept-paper-style-with-earth_23-2148411497.jpg" alt="Ecoverse Logo" class="w-20 h-20"> <!-- Replace with actual logo path -->
        </div>

        <!-- Center with Ecoverse Brand -->
        <div class="flex-1 text-left">
            <a href="index.php" class="text-white font-bold text-4xl">Ecoverse</a>
        </div>

        <!-- Navigation Links on the right -->
        <ul class="flex space-x-6">
            <li><a href="index.php" class="text-white hover:text-green-300 text-lg">Home</a></li>
            <li><a href="about_us.html" class="link link-hover text-green-100">About us</a></li>
            <li><a href="contact.html" class="link link-hover text-green-100">Contact</a></li>
            <a href="jobs.html" class="link link-hover text-green-100">Jobs</a>
        </ul>
    </div>
</nav>

<!-- Blog Section -->
<div class="container mx-auto mt-12 px-4">
    <h1 class="text-5xl font-bold text-center mb-12 text-green-700">Blog and Articles</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
        <?php if ($blogs_result->num_rows > 0): ?>
            <?php while ($blog = $blogs_result->fetch_assoc()): ?>
                <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 blog-card">
                    <img src="<?= htmlspecialchars($blog['image_url']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($blog['title']) ?></h2>
                        <p class="text-gray-500 text-sm mb-4">By <?= htmlspecialchars($blog['consultant_name']) ?> on <?= date("F j, Y", strtotime($blog['created_at'])) ?></p>
                        <p class="text-gray-700 leading-relaxed mb-4"><?= substr(htmlspecialchars($blog['content']), 0, 160) ?>...</p>
                        <a href="<?= !empty($blog['link']) ? htmlspecialchars($blog['link']) : 'blog-post.php?id=' . $blog['id'] ?>" class="inline-block mt-4 text-green-600 font-semibold hover:underline">Read more</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-gray-600">No blog posts found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="mt-12 p-4 bg-gray-800 text-white text-center">
    <p class="text-sm">&copy; 2024 Ecoverse. All rights reserved.</p>
    <div class="flex justify-center space-x-6 mt-4">
        <a href="https://www.facebook.com/shafinahmed.shafin.7" class="text-green-300 hover:text-green-500">Facebook</a>
        <a href="https://www.instagram.com/krishanuabir/" class="text-green-300 hover:text-green-500">Instagram</a>
        <a href="https://x.com/i/flow/login?redirect_after_login=%2Fafnanshahriar27" class="text-green-300 hover:text-green-500">Twitter</a>
    </div>
</footer>
</body>

</html>
