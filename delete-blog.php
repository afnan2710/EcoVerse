<?php
session_start();
require_once 'connect.php';

if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];

    $query = "SELECT image_url FROM blogs WHERE blog_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();

    if (!$blog) {
        echo "Blog post not found!";
        exit;
    }

    // Delete the image file if it exists
    if (file_exists($blog['image_url'])) {
        unlink($blog['image_url']);
    }

    // Now, delete the blog post from the database
    $deleteQuery = "DELETE FROM blogs WHERE blog_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $blog_id);

    if ($stmt->execute()) {
        echo "Blog post deleted successfully!";
        header("Location: consultant-blog.php");
        exit;
    } else {
        echo "Failed to delete the blog post!";
    }
} else {
    echo "Invalid request!";
    exit;
}
?>
