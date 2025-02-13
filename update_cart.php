<?php
session_start();

$product_id = htmlspecialchars($_POST['product_id']);
$action = htmlspecialchars($_POST['action']);

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['product_id'] == $product_id) {
            if ($action == 'increase') {
                $_SESSION['cart'][$index]['qty'] += 1;
            } elseif ($action == 'decrease') {
                if ($_SESSION['cart'][$index]['qty'] > 1) {
                    $_SESSION['cart'][$index]['qty'] -= 1;
                }
            } elseif ($action == 'remove') {
                array_splice($_SESSION['cart'], $index, 1);
            }
            break;
        }
    }
}

header("Location: cart.php");
exit();
?>