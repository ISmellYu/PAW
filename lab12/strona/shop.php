<?php
session_start();
include("config.php");
include("cart_handler.php");

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Fetch products from database
$query = "SELECT p.*, c.nazwa as kategoria_nazwa 
          FROM products p 
          LEFT JOIN categories c ON p.kategoria = c.id 
          WHERE p.ilosc > 0 
          AND p.data_wygasniecia >= CURDATE()
          ORDER BY p.id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Inżynieria wsteczna</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/shop.css">
    <script>
    $(document).ready(function() {
        // Initialize cart display on page load
        updateCartDisplay();

        // Add to cart form submission
        $('.add-to-cart-form').on('submit', function(e) {
            e.preventDefault(); // Prevent form from submitting normally
            const form = $(this);
            
            $.ajax({
                type: 'POST',
                url: 'cart_handler.php',
                data: {
                    action: 'add',
                    product_id: form.find('[name="product_id"]').val(),
                    quantity: form.find('[name="quantity"]').val()
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        updateCartDisplay();
                        alert('Produkt dodany do koszyka!');
                    }
                }
            });
        });

        // Update quantity
        $(document).on('change', '.cart-quantity', function() {
            const productId = $(this).data('product-id');
            const quantity = $(this).val();
            
            $.ajax({
                type: 'POST',
                url: 'cart_handler.php',
                data: {
                    action: 'update',
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    updateCartDisplay();
                }
            });
        });

        // Remove from cart
        $(document).on('click', '.remove-from-cart', function() {
            const productId = $(this).data('product-id');
            
            $.ajax({
                type: 'POST',
                url: 'cart_handler.php',
                data: {
                    action: 'remove',
                    product_id: productId
                },
                success: function(response) {
                    updateCartDisplay();
                }
            });
        });

        // Function to update cart display
        function updateCartDisplay() {
            $.get('cart_display.php', function(data) {
                $('#cart-container').html(data);
            });
        }
    });
    </script>
</head>
<body>
    <a href="index.php" class="main-title">Inżynieria wsteczna</a>
    <?php include('nav.php'); ?>

    <main class="shop-content">
        <div class="shop-grid">
            <?php while($product = mysqli_fetch_array($result)): ?>
                <div class="shop-product">
                    <div class="shop-product-image">
                        <?php if($product['zdjecie']): ?>
                            <img src="<?php echo htmlspecialchars($product['zdjecie']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['tytul']); ?>">
                        <?php else: ?>
                            <div class="no-image">Brak zdjęcia</div>
                        <?php endif; ?>
                    </div>
                    <div class="shop-product-info">
                        <h3><?php echo htmlspecialchars($product['tytul']); ?></h3>
                        <p class="shop-product-category">
                            Kategoria: <?php echo htmlspecialchars($product['kategoria_nazwa'] ?? 'Brak'); ?>
                        </p>
                        <p class="shop-product-price">
                            <?php echo number_format($product['cena_netto'] * (1 + $product['podatek_vat']), 2); ?> zł
                        </p>
                        <form class="add-to-cart-form">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <div class="quantity-wrapper">
                                <input type="number" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="<?php echo $product['ilosc']; ?>" 
                                       class="quantity-input">
                                <button type="submit" class="add-to-cart-btn">Dodaj do koszyka</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <div id="cart-container" class="cart-container"></div>

    <footer class="site-footer">
        <?php
        $nr_indeksu = '169368';
        $nrGrupy = '4';
        echo '<p>Autor: Łukasz Szostak '.$nr_indeksu.' grupa '.$nrGrupy.'</p>';
        ?>
    </footer>
</body>
</html>