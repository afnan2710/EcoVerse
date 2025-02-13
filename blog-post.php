<?php
session_start();

// Mockup data for blog posts
$blogs = [
    [
        'id' => 1,
        'title' => 'The History of Ancient Trees',
        'content' => 'Discover the fascinating history of ancient trees, their resilience, and their role in our ecosystem. Ancient trees are living witnesses of history, with some species living for thousands of years. They provide habitat, support biodiversity, and are essential to the balance of our ecosystems...',
        'image' => 'https://c.files.bbci.co.uk/B332/production/_125747854_ancienttrees.jpg', // Updated image URL
        'date' => 'October 1, 2024'
    ],
    [
        'id' => 2,
        'title' => 'Top 10 Tree Care Tips for Beginners',
        'content' => 'Caring for your trees is essential for their longevity. Here are 10 tips to ensure they thrive: 1) Water your trees adequately. 2) Prune regularly to promote healthy growth. 3) Mulch to retain moisture. 4) Protect from pests and diseases. 5) Fertilize as needed. 6) Choose the right tree for your location. 7) Monitor for signs of stress. 😎 Remove competing vegetation. 9) Consult professionals for advice. 10) Enjoy the process!',
        'image' => 'tree-care.jpg', // Assuming this is a local file
        'date' => 'September 25, 2024'
    ],
    [
        'id' => 3,
        'title' => 'Success Stories from Our Customers',
        'content' => 'Our customers share their success stories on growing rare and beautiful trees purchased from EcoVerse. Many have transformed their gardens into stunning landscapes, while others have made significant contributions to local ecology by planting native species...',
        'image' => 'success-story.jpg', // Assuming this is a local file
        'date' => 'September 18, 2024'
    ]
];

// Get the blog post ID from the URL
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$selectedBlog = null;

// Find the blog post by ID
foreach ($blogs as $blog) {
    if ($blog['id'] === $postId) {
        $selectedBlog = $blog;
        break;
    }
}

if (!$selectedBlog) {
    // If no blog post is found, redirect or show an error
    header("Location: blog.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($selectedBlog['title']); ?> | EcoVerse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gradient-to-b from-gray-300 to-green-100">
    <!-- Include navbar -->
    <?php include 'navbar.php'; ?>
    <!-- Blog Post Section -->
    <div class="container mx-auto mt-8">
        <h1 class="text-4xl font-bold text-center mb-4 text-gray-800"><?php echo htmlspecialchars($selectedBlog['title']); ?></h1>
        <p class="text-gray-600 text-center mb-4"><?php echo $selectedBlog['date']; ?></p>
        <img src="<?php echo $selectedBlog['image']; ?>" alt="<?php echo htmlspecialchars($selectedBlog['title']); ?>" class="w-full h-64 object-cover mb-6 rounded-lg shadow-md">
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <p class="text-gray-700 leading-loose"><?php echo nl2br($selectedBlog['content']); ?></p>
        </div>
        <a href="blog.php" class="block text-center text-blue-500 mt-8 hover:underline">Back to Blog</a>
    </div>

    <!-- Footer -->
    <footer class="mt-12 p-4 bg-gray-800 text-white text-center">
        &copy; 202