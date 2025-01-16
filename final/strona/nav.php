<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Zwroc elementy nawigacji z bazy danych
function GetNavigationItems() {
    global $conn;
    $query = "SELECT * FROM page_list WHERE status = 1 ORDER BY id ASC";
    $result = mysqli_query($conn, $query);
    
    $nav_items = array();
    while($row = mysqli_fetch_assoc($result)) {
        $nav_items[] = $row;
    }
    return $nav_items;
}

function GetCartItemCount() {
    if (!isset($_SESSION['cart'])) return 0;
    return array_sum($_SESSION['cart']);
}
?>

<nav class="main-nav">
    <div class="nav-container">
        <?php
        $nav_items = GetNavigationItems();
        foreach($nav_items as $item) {
            $active = (isset($_GET['idp']) && $_GET['idp'] == $item['id']) ? 'active' : '';
            echo '<a href="index.php?idp=' . $item['id'] . '" class="nav-btn ' . $active . '">' 
                 . htmlspecialchars($item['page_title']) . '</a>';
        }
        ?>
        <!-- Additional static menu items -->
        <a href="shop.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">
            Sklep
            <?php if (GetCartItemCount() > 0): ?>
                <span class="cart-count"><?php echo GetCartItemCount(); ?></span>
            <?php endif; ?>
        </a>
        <a href="admin/admin.php" class="nav-btn">Panel Admin</a>
    </div>
</nav>