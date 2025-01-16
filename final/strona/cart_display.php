<?php
// Rozpocznij sesję jeśli nie jest aktywna
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("config.php");

// Funkcja obliczająca sumę całkowitą koszyka
// Uwzględnia cenę netto i VAT dla każdego produktu
function calculateTotal() {
    global $conn;
    $total = 0;
    
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $query = "SELECT cena_netto, podatek_vat FROM products WHERE id = $product_id";
            $result = mysqli_query($conn, $query);
            if ($product = mysqli_fetch_assoc($result)) {
                $total += ($product['cena_netto'] * (1 + $product['podatek_vat'])) * $quantity;
            }
        }
    }
    return $total;
}

// Inicjalizacja tablicy produktów w koszyku
$cart_items = array();

// Pobranie szczegółów wszystkich produktów w koszyku
if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $ids_string = implode(',', $product_ids);
    $query = "SELECT * FROM products WHERE id IN ($ids_string)";
    $result = mysqli_query($conn, $query);
    while ($product = mysqli_fetch_assoc($result)) {
        $cart_items[$product['id']] = $product;
    }
}
?>

<div class="cart-summary">
    <h2>Koszyk</h2>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Koszyk jest pusty</p>
    <?php else: ?>
        <div class="cart-items">
            <?php foreach ($_SESSION['cart'] as $product_id => $quantity): 
                if (isset($cart_items[$product_id])):
                    $product = $cart_items[$product_id];
                    $price = $product['cena_netto'] * (1 + $product['podatek_vat']);
                    $subtotal = $price * $quantity;
            ?>
                <div class="cart-item">
                    <h3><?php echo htmlspecialchars($product['tytul']); ?></h3>
                    <div class="cart-item-details">
                        <span class="cart-item-price"><?php echo number_format($price, 2); ?> zł</span>
                        <input type="number" 
                               class="cart-quantity" 
                               value="<?php echo $quantity; ?>" 
                               min="1" 
                               max="<?php echo $product['ilosc']; ?>"
                               data-product-id="<?php echo $product_id; ?>">
                        <span class="cart-item-subtotal"><?php echo number_format($subtotal, 2); ?> zł</span>
                        <button class="remove-from-cart" data-product-id="<?php echo $product_id; ?>">Usuń</button>
                    </div>
                </div>
            <?php 
                endif;
            endforeach; ?>
        </div>
        <div class="cart-total">
            <strong>Razem: <?php echo number_format(calculateTotal(), 2); ?> zł</strong>
        </div>
    <?php endif; ?>
</div>
