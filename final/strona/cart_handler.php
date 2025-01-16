<?php
// Rozpocznij sesję jeśli nie jest aktywna
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("config.php");

// Inicjalizacja koszyka w sesji jeśli nie istnieje
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Obsługa żądań POST dla operacji na koszyku
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $response = array('success' => false);

    switch ($action) {
        // Dodawanie produktu do koszyka
        case 'add':
            $product_id = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);
            
            // Sprawdzenie czy produkt istnieje i czy jest wystarczająca ilość w magazynie
            $query = "SELECT * FROM products WHERE id = $product_id AND ilosc >= $quantity";
            $result = mysqli_query($conn, $query);
            
            // Jeśli produkt znaleziono, dodaj do koszyka
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

        // Aktualizacja ilości produktu w koszyku
        case 'update':
            $product_id = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);
            
            // Jeśli ilość większa od 0, aktualizuj, w przeciwnym razie usuń
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id] = $quantity;
                $response['success'] = true;
            } else {
                unset($_SESSION['cart'][$product_id]);
                $response['success'] = true;
            }
            break;

        // Usuwanie produktu z koszyka
        case 'remove':
            $product_id = intval($_POST['product_id']);
            unset($_SESSION['cart'][$product_id]);
            $response['success'] = true;
            break;
    }

    // Zwróć odpowiedź w formacie JSON
    echo json_encode($response);
    exit;
}
?>