<?php
session_start();
require_once 'connect.php';

// Check if a blog ID was provided in the URL (e.g., edit-blog.php?id=1)
if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];

    // Fetch the blog details from the database
    $query = "SELECT * FROM blogs WHERE blog_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();

    if (!$blog) {
        echo "Blog post not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

// If the form is submitted to update the blog post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['blog_image']['name'];

    // Handle the image update
    if ($image) {
        $uploadDir = 'uploads/';
        $imageFilePath = $uploadDir . 'blog_' . uniqid() . '.' . pathinfo($image, PATHINFO_EXTENSION);

        if (!move_uploaded_file($_FILES['blog_image']['tmp_name'], $imageFilePath)) {
            echo "Failed to upload image!";
            exit;
        }

        // Update query with the new image
        $updateQuery = "UPDATE blogs SET title = ?, content = ?, image_url = ? WHERE blog_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $title, $content, $imageFilePath, $blog_id);
    } else {
        // Update query without changing the image
        $updateQuery = "UPDATE blogs SET title = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssi", $title, $content, $blog_id);
    }

    if ($stmt->execute()) {
        echo "Blog post updated successfully!";
        header("Location: consultant-blog.php");
        exit;
    } else {
        echo "Failed to update the blog post!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog Post</title>
    <style>
        body {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ccc;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-top: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], textarea {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
            height: 150px;
        }

        input[type="file"] {
            margin-bottom: 15px;
        }

        img {
            display: block;
            margin: 10px 0;
            max-width: 100%;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Blog Post</h1>
        <h4 align="center"><a href="consultant-blog.php">Go Back</a></h4>

        <form action="edit-blog.php?id=<?php echo $blog['blog_id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo $blog['title']; ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" id="content" required><?php echo $blog['content']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="blog_image">Change Image (optional):</label>
                <input type="file" name="blog_image" id="blog_image">
            </div>

            <div class="form-group">
                <img src="<?php echo $blog['image_url']; ?>" alt="Current Image">
            </div>

            <button type="submit">Update Blog Post</button>
        </form>
    </div>
</body>
</html>

