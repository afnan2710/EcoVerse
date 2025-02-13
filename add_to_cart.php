<?php
session_start();

if (!isset($_SESSION['id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$product_id = htmlspecialchars($_POST['product_id']);
$name = htmlspecialchars($_POST['name']);
$image_url = htmlspecialchars($_POST['image_url']);
$price = htmlspecialchars($_POST['price']);
$qty = intval($_POST['qty']);

$product_found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] == $product_id) {
        $item['qty'] += $qty;
        $product_found = true;
        break;
    }
}

if (!$product_found) {
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'name' => $name,
        'image_url' => $image_url,
        'price' => $price,
        'qty' => $qty
    );
}

header("Location: index.php");
exit();
?>