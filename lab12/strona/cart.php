<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add a product to the cart
function addToCart($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity; // Update quantity if product already in cart
    } else {
        $_SESSION['cart'][$productId] = $quantity; // Add new product to cart
    }
}

// Function to remove a product from the cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]); // Remove product from cart
    }
}

// Function to update the quantity of a product in the cart
function updateCart($productId, $quantity) {
    if ($quantity <= 0) {
        removeFromCart($productId); // Remove product if quantity is 0 or less
    } else {
        $_SESSION['cart'][$productId] = $quantity; // Update quantity
    }
}

// Function to calculate the total value of the cart
function calculateTotal($products) {
    $total = 0;
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        if (isset($products[$productId])) {
            $product = $products[$productId];
            $total += ($product['cena_netto'] * (1 + $product['podatek_vat'])) * $quantity; // Calculate total price including VAT
        }
    }
    return $total;
}

// Handle adding products to the cart
if (isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    addToCart($productId, $quantity);
    header("Location: index.php?idp=products"); // Redirect back to products page
    exit();
}

// Handle removing products from the cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    removeFromCart($productId);
    header("Location: cart.php"); // Redirect back to cart
    exit();
}

// Function to display the cart contents
function displayCart($products) {
    $output = '<h2>Twój Koszyk</h2>';
    if (empty($_SESSION['cart'])) {
        $output .= '<p>Koszyk jest pusty.</p>';
    } else {
        $output .= '<table>';
        $output .= '<tr><th>Produkt</th><th>Ilość</th><th>Cena</th><th>Akcje</th></tr>';
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $products[$productId];
            $output .= '<tr>';
            $output .= '<td>' . htmlspecialchars($product['tytul']) . '</td>';
            $output .= '<td>' . $quantity . '</td>';
            $output .= '<td>' . number_format(($product['cena_netto'] * (1 + $product['podatek_vat'])) * $quantity, 2) . ' zł</td>';
            $output .= '<td><a href="cart.php?action=remove&id=' . $productId . '">Usuń</a></td>';
            $output .= '</tr>';
        }
        $output .= '<tr><td colspan="2">Łącznie:</td><td>' . number_format(calculateTotal($products), 2) . ' zł</td><td></td></tr>';
        $output .= '</table>';
    }
    return $output;
}
?>