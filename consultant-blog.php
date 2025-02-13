<?php
session_start();

if (!isset($_SESSION['consultant_id'])) {
    header("Location: consultant-login.php");
    exit;
}

include 'connect.php';

// Initialize variables for messages
$error_message = '';
$success_message = '';

// Check if the form was submitted and the file was uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['blog_image'])) {
    $uploadDir = 'uploads/';  // Folder where files will be uploaded

    // Ensure the uploads folder exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Create the uploads folder if it doesn't exist
    }

    // Generate a unique name for the file to avoid conflicts
    $fileName = 'blog_' . uniqid() . '.' . pathinfo($_FILES['blog_image']['name'], PATHINFO_EXTENSION);
    $filePath = $uploadDir . $fileName;

    // Check if the file was successfully moved to the target location
    if (move_uploaded_file($_FILES['blog_image']['tmp_name'], $filePath)) {
        echo "File uploaded successfully!";
        // You can now insert the $filePath into your database along with the blog details
    } else {
        echo "Failed to upload the file.";
    }
}

// Handle Blog Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_blog'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $link = trim($_POST['link']);
    
    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (!in_array($file_ext, $allowed)) {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        } elseif ($file_size > 2 * 1024 * 1024) { // 2MB limit
            $error_message = "File size exceeds the 2MB limit.";
        } else {
            $new_file_name = uniqid('blog_', true) . '.' . $file_ext;
            $upload_path = 'uploads/' . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $image_url = $upload_path;
            } else {
                $error_message = "Failed to upload the image.";
            }
        }
    } else {
        $error_message = "Please upload an image for the blog post.";
    }
    
    // Validate Fields
    if (empty($title) || empty($content) || empty($image_url)) {
        if (empty($title) || empty($content)) {
            $error_message = "Please fill out all required fields.";
        }
        // Image upload errors are already handled
    }
    
    // Insert into Database
    if (empty($error_message)) {
        $consultant_id = $_SESSION['consultant_id'];
        $stmt = $conn->prepare("INSERT INTO blogs (consultant_id, title, content, image_url, link) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('issss', $consultant_id, $title, $content, $image_url, $link);
        
        if ($stmt->execute()) {
            $success_message = "Blog post added successfully!";
        } else {
            $error_message = "Error adding blog post: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

// Fetch Existing Blogs
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
    <title>Consultant Blog Management | EcoVerse</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        h1, h2 {
            color: #2c3e50;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            float: right;
            margin-bottom: 20px;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        /* Form Styles */
        .blog-form {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .blog-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .blog-form input[type="text"],
        .blog-form input[type="url"],
        .blog-form textarea,
        .blog-form input[type="file"] {
            width: 97%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .blog-form button {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .blog-form button:hover {
            background-color: #218838;
        }

        /* Messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Blogs Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.9em;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #007d3c;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        /* Action Buttons */
        .edit-btn, .delete-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-size: 0.9em;
        }

        .edit-btn {
            background-color: #17a2b8;
            margin-right: 5px;
        }

        .edit-btn:hover {
            background-color: #138496;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .logout-btn {
                float: none;
                width: 100%;
                margin-bottom: 20px;
            }

            table, thead, tbody, th, td, tr { 
                display: block; 
            }

            th, td {
                padding: 10px;
                text-align: right;
                position: relative;
            }

            th::before, td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }

            th:last-child, td:last-child {
                border-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Consultant Blog Management</h1>
        
        <form action="consultant-dashboard.php" method="post">
            <button type="submit" class="logout-btn">Dashboard</button>
        </form>

        <!-- Blog Submission Form -->
        <div class="blog-form">
            <h2>Add New Blog Post</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert error"><?= htmlspecialchars($error_message) ?></div>
            <?php elseif (!empty($success_message)): ?>
                <div class="alert success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>

            <form action="consultant-blog.php" method="POST" enctype="multipart/form-data">
                <label for="title">Blog Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="8" required></textarea>

                <label for="image">Featured Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <label for="link">External Link (optional):</label>
                <input type="url" id="link" name="link" placeholder="https://example.com">

                <button type="submit" name="submit_blog">Publish Blog</button>
            </form>
        </div>

        <!-- Existing Blogs Section -->
        <h2>Existing Blog Posts</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($blogs_result->num_rows > 0): ?>
                    <?php while ($blog = $blogs_result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Title"><?= htmlspecialchars($blog['title']) ?></td>
                            <td data-label="Author"><?= htmlspecialchars($blog['consultant_name']) ?></td>
                            <td data-label="Date Created"><?= htmlspecialchars(date("F j, Y, g:i a", strtotime($blog['created_at']))) ?></td>
                            <td data-label="Actions">
                                <a href="edit-blog.php?id=<?= $blog['blog_id'] ?>" class="edit-btn">Edit</a>
                                <a href="delete-blog.php?id=<?= $blog['blog_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this blog post?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No blog posts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
