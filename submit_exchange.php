<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['exchange_name']);
    $email = $conn->real_escape_string($_POST['exchange_email']);
    $contact = $conn->real_escape_string($_POST['exchange_contact']);
    $plant_details = $conn->real_escape_string($_POST['plant_details']);
    $exchange_type = $conn->real_escape_string($_POST['exchange_type']);
    $selling_price = $exchange_type === 'money' ? $conn->real_escape_string($_POST['selling_price']) : null;

    // Handle image upload
    $image_url = '';
    if (isset($_FILES['plant_image']) && $_FILES['plant_image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['plant_image']['name'];
        $image_tmp = $_FILES['plant_image']['tmp_name'];
        $image_url = 'uploads/' . $image_name;
        move_uploaded_file($image_tmp, $image_url);
    }

    $query = "INSERT INTO plant_exchange (name, email, contact, image_url, plant_details, exchange_type, selling_price) 
              VALUES ('$name', '$email', '$contact', '$image_url', '$plant_details', '$exchange_type', '$selling_price')";
    
    if ($conn->query($query)) {
        echo "Exchange request submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

header("Location: index.php");
?>
