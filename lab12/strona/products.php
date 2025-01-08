<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
    <link rel="stylesheet" href="css/admin.css" />
</head>
<body>
<?php
include("config.php");
include("cart.php"); // Include the cart functions

// Fetch products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[$row['id']] = $row;
}

// Display products
$output = '<h2>Produkty</h2>';
$output .= '<div class="shop-product-grid">';
foreach ($products as $product) {
    $output .= '<div class="shop-product-card">';
    $output .= '<div class="shop-product-image">';
    if ($product['zdjecie']) {
        $output .= '<img src="' . htmlspecialchars($product['zdjecie']) . '" alt="' . htmlspecialchars($product['tytul']) . '">';
    } else {
        $output .= '<div class="no-image">Brak zdjęcia</div>';
    }
    $output .= '</div>';
    
    $output .= '<div class="shop-product-info">';
    $output .= '<h3>' . htmlspecialchars($product['tytul']) . '</h3>';
    $output .= '<p class="shop-product-price">Cena: ' . number_format($product['cena_netto'] * (1 + $product['podatek_vat']), 2) . ' zł</p>';
    $output .= '<form method="post" action="cart.php" class="add-to-cart-form">';
    $output .= '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
    $output .= '<input type="number" name="quantity" value="1" min="1" required>';
    $output .= '<input type="submit" name="add_to_cart" value="Dodaj do koszyka" class="add-to-cart-button">';
    $output .= '</form>';
    $output .= '</div>';
    $output .= '</div>';
}
$output .= '</div>';

// Display the cart contents
$output .= displayCart($products);

// Display the output
echo $output;
?>
</body>
</html>
