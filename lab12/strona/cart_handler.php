<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("config.php");

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $response = array('success' => false);

    switch ($action) {
        case 'add':
            $product_id = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);
            
            // Check if product exists and has enough stock
            $query = "SELECT * FROM products WHERE id = $product_id AND ilosc >= $quantity";
            $result = mysqli_query($conn, $query);
            
            if ($product = mysqli_fetch_assoc($result)) {
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] += $quantity;
                } else {
                    $_SESSION['cart'][$product_id] = $quantity;
                }
                $response['success'] = true;
                $response['message'] = 'Produkt dodany do koszyka';
            }
            break;

        case 'update':
            $product_id = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);
            
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id] = $quantity;
                $response['success'] = true;
            } else {
                unset($_SESSION['cart'][$product_id]);
                $response['success'] = true;
            }
            break;

        case 'remove':
            $product_id = intval($_POST['product_id']);
            unset($_SESSION['cart'][$product_id]);
            $response['success'] = true;
            break;
    }

    echo json_encode($response);
    exit;
}
?>