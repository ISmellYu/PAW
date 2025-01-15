<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
    <link rel="stylesheet" href="../css/admin.css" />
</head>
<body>
<?php
require_once('../config.php');
session_start();

// Funkcja sprawdzająca poprawność logowania
// Zwraca true jeśli zalogowano pomyślnie, string z błędem w przypadku niepowodzenia
function SprawdzLogowanie() {
    global $login, $pass;
    
    if (isset($_POST['x1_submit'])) {
        if ($_POST['login_email'] == $login && $_POST['login_pass'] == $pass) {
            $_SESSION['zalogowany'] = true;
            return true;
        } else {
            return "Błędny login lub hasło!";
        }
    }
    return isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] === true;
}

// Generuje formularz logowania do panelu administracyjnego
// Parametr $error wyświetla komunikat o błędzie, jeśli występuje
function FormularzLogowania($error = "") {
    $wynik = "";
    if ($error) {
        $wynik .= "<div class='error'>$error</div>";
    }
    
    $wynik .= "
    <div class='logowanie'>
        <h1 class='heading'>Panel CMS:</h1>
        <div class='logowanie'>
            <form method='post' name='LoginForm' enctype='multipart/form-data' action='" . $_SERVER['REQUEST_URI'] . "'>
                <table class='logowanie'>
                    <tr><td class='log4_t'>[Email]</td><td><input type='text' name='login_email' class='logowanie' /></td></tr>
                    <tr><td class='log4_t'>[Hasło]</td><td><input type='password' name='login_pass' class='logowanie' /></td></tr>
                    <tr><td>&nbsp;</td><td><input type='submit' name='x1_submit' class='logowanie' value='Zaloguj' /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ";
    return $wynik;
}

// Wyświetla listę wszystkich podstron w formie kartek
// Zawiera przyciski do edycji, usuwania oraz dodawania nowych podstron
function ListaPodstron() {
    global $conn;
    
    $query = "SELECT * FROM page_list ORDER BY id ASC";
    $result = mysqli_query($conn, $query);
    
    $wynik = '<div class="panel-admin">';
    $wynik .= '<h2>Lista Podstron</h2>';
    
    // Przycisk dodawania nowej podstrony
    $wynik .= '<div class="admin-actions">';
    $wynik .= '<a href="admin.php?action=add" class="btn-add">+ Dodaj Nową Podstronę</a>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="page-grid">';
    
    while($row = mysqli_fetch_array($result)) {
        $wynik .= '<div class="page-card">';
        $wynik .= '<div class="page-card-header">';
        $wynik .= '<h3>' . htmlspecialchars($row['page_title']) . '</h3>';
        $wynik .= '<span class="page-id">ID: ' . $row['id'] . '</span>';
        $wynik .= '</div>';
        
        $wynik .= '<div class="page-content">';
        $wynik .= '<p>' . mb_substr(strip_tags($row['page_content']), 0, 150) . '...</p>';
        $wynik .= '</div>';
        
        $wynik .= '<div class="page-status ' . ($row['status'] == 1 ? 'active' : 'inactive') . '">';
        $wynik .= '<span class="status-dot"></span>';
        $wynik .= '<span class="status-text">' . ($row['status'] == 1 ? 'Aktywna' : 'Nieaktywna') . '</span>';
        $wynik .= '</div>';
        
        $wynik .= '<div class="page-actions">';
        $wynik .= '<a href="admin.php?action=edit&id=' . $row['id'] . '" class="btn-edit">Edytuj</a>';
        $wynik .= '<a href="admin.php?action=delete&id=' . $row['id'] . '" class="btn-delete" onclick="return confirm(\'Czy na pewno chcesz usunąć tę podstronę?\')">Usuń</a>';
        $wynik .= '</div>';
        
        $wynik .= '</div>';
    }
    
    $wynik .= '</div>';
    $wynik .= '</div>';
    
    return $wynik;
}

// Generuje formularz edycji wybranej podstrony
// Pobiera dane podstrony na podstawie ID z parametru GET
function EdytujPodstrone() {
    global $conn;
    
    if(isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $query = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        
        $wynik = '<div class="panel-admin">';
        $wynik .= '<h2>Edytuj Podstronę</h2>';
        $wynik .= '<form method="post" action="admin.php?action=update" class="edit-form">';
        $wynik .= '<input type="hidden" name="id" value="' . $id . '">';
        
        $wynik .= '<div class="form-group">';
        $wynik .= '<label for="page_title">Tytuł podstrony:</label>';
        $wynik .= '<input type="text" name="page_title" id="page_title" value="' . htmlspecialchars($row['page_title']) . '" class="form-control" required>';
        $wynik .= '</div>';
        
        $wynik .= '<div class="form-group">';
        $wynik .= '<label for="page_content">Treść podstrony:</label>';
        $wynik .= '<textarea name="page_content" id="page_content" class="form-control" rows="10" required>' . htmlspecialchars($row['page_content']) . '</textarea>';
        $wynik .= '</div>';
        
        $wynik .= '<div class="form-group checkbox-group">';
        $wynik .= '<label class="checkbox-label">';
        $wynik .= '<input type="checkbox" name="status" value="1" ' . ($row['status'] == 1 ? 'checked' : '') . '>';
        $wynik .= '<span>Strona aktywna</span>';
        $wynik .= '</label>';
        $wynik .= '</div>';
        
        $wynik .= '<div class="form-buttons">';
        $wynik .= '<input type="submit" name="update" value="Zapisz zmiany" class="btn-save">';
        $wynik .= '<a href="admin.php" class="btn-cancel">Anuluj</a>';
        $wynik .= '</div>';
        
        $wynik .= '</form>';
        $wynik .= '</div>';
        
        return $wynik;
    }
    return '';
}

// Usuwa podstronę o określonym ID
// Po usunięciu przekierowuje z powrotem do panelu
function UsunPodstrone() {
    global $conn;
    
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";
        mysqli_query($conn, $query);
        header("Location: admin.php");
        exit();
    }
}

// Wyświetla formularz dodawania nowej podstrony
// Obsługuje również zapisywanie nowej podstrony do bazy
function DodajNowaPodstrone() {
    global $conn;
    
    if(isset($_POST['add'])) {
        $page_title = mysqli_real_escape_string($conn, $_POST['page_title']);
        $page_content = mysqli_real_escape_string($conn, $_POST['page_content']);
        $status = isset($_POST['status']) ? 1 : 0;
        
        $query = "INSERT INTO page_list (page_title, page_content, status) 
                 VALUES ('$page_title', '$page_content', $status)";
        
        if(mysqli_query($conn, $query)) {
            header("Location: admin.php?success=1");
            exit();
        } else {
            return '<div class="error">Wystąpił błąd podczas dodawania strony.</div>';
        }
    }
    
    $wynik = '<div class="panel-admin">';
    $wynik .= '<h2>Dodaj Nową Podstronę</h2>';
    $wynik .= '<form method="post" action="admin.php?action=add" class="edit-form">';
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="page_title">Tytuł podstrony:</label>';
    $wynik .= '<input type="text" name="page_title" id="page_title" class="form-control" required>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="page_content">Treść podstrony:</label>';
    $wynik .= '<textarea name="page_content" id="page_content" class="form-control" rows="10" required></textarea>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-group checkbox-group">';
    $wynik .= '<label class="checkbox-label">';
    $wynik .= '<input type="checkbox" name="status" value="1" checked>';
    $wynik .= '<span>Strona aktywna</span>';
    $wynik .= '</label>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-buttons">';
    $wynik .= '<input type="submit" name="add" value="Dodaj podstronę" class="btn-save">';
    $wynik .= '<a href="admin.php" class="btn-cancel">Anuluj</a>';
    $wynik .= '</div>';
    
    $wynik .= '</form>';
    $wynik .= '</div>';
    
    return $wynik;
}

// Wyświetla listę wszystkich kategorii w formie drzewa
function PokazKategorie() {
    global $conn;
    
    $wynik = '<div class="panel-admin">';
    $wynik .= '<h2>Lista Kategorii</h2>';
    
    // Przycisk dodawania nowej kategorii
    $wynik .= '<div class="admin-actions">';
    $wynik .= '<a href="admin.php?action=addcategory" class="btn-add">+ Dodaj Nową Kategorię</a>';
    $wynik .= '</div>';
    
    // Pobierz kategorie główne (matka = 0)
    $query = "SELECT * FROM categories WHERE matka = 0 ORDER BY nazwa ASC";
    $result = mysqli_query($conn, $query);
    
    $wynik .= '<div class="categories-tree">';
    
    while($row = mysqli_fetch_array($result)) {
        $wynik .= '<div class="category-item">';
        $wynik .= '<div class="category-header">';
        $wynik .= '<h3>' . htmlspecialchars($row['nazwa']) . '</h3>';
        $wynik .= '<div class="category-actions">';
        $wynik .= '<a href="admin.php?action=editcategory&id=' . $row['id'] . '" class="btn-edit">Edytuj</a>';
        $wynik .= '<a href="admin.php?action=deletecategory&id=' . $row['id'] . '" class="btn-delete" onclick="return confirm(\'Czy na pewno chcesz usunąć tę kategorię?\')">Usuń</a>';
        $wynik .= '</div>';
        $wynik .= '</div>';
        
        // Pobierz podkategorie
        $query_sub = "SELECT * FROM categories WHERE matka = " . $row['id'] . " ORDER BY nazwa ASC";
        $result_sub = mysqli_query($conn, $query_sub);
        
        if(mysqli_num_rows($result_sub) > 0) {
            $wynik .= '<div class="subcategories">';
            while($sub_row = mysqli_fetch_array($result_sub)) {
                $wynik .= '<div class="subcategory-item">';
                $wynik .= '<div class="subcategory-header">';
                $wynik .= '<h4>' . htmlspecialchars($sub_row['nazwa']) . '</h4>';
                $wynik .= '<div class="category-actions">';
                $wynik .= '<a href="admin.php?action=editcategory&id=' . $sub_row['id'] . '" class="btn-edit">Edytuj</a>';
                $wynik .= '<a href="admin.php?action=deletecategory&id=' . $sub_row['id'] . '" class="btn-delete" onclick="return confirm(\'Czy na pewno chcesz usunąć tę podkategorię?\')">Usuń</a>';
                $wynik .= '</div>';
                $wynik .= '</div>';
                $wynik .= '</div>';
            }
            $wynik .= '</div>';
        }
        
        $wynik .= '</div>';
    }
    
    $wynik .= '</div>';
    $wynik .= '</div>';
    
    return $wynik;
}

// Formularz dodawania/edycji kategorii
function FormularzKategorii($id = null) {
    global $conn;
    
    $nazwa = '';
    $matka = 0;
    $tytul = 'Dodaj Nową Kategorię';
    $akcja = 'addcategory';
    $przycisk = 'Dodaj kategorię';
    
    if($id) {
        $query = "SELECT * FROM categories WHERE id = " . intval($id) . " LIMIT 1";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        
        if($row) {
            $nazwa = $row['nazwa'];
            $matka = $row['matka'];
            $tytul = 'Edytuj Kategorię';
            $akcja = 'editcategory';
            $przycisk = 'Zapisz zmiany';
        }
    }
    
    $wynik = '<div class="panel-admin">';
    $wynik .= '<h2>' . $tytul . '</h2>';
    $wynik .= '<form method="post" action="admin.php?action=' . $akcja . ($id ? '&id=' . $id : '') . '" class="edit-form">';
    
    if($id) {
        $wynik .= '<input type="hidden" name="id" value="' . $id . '">';
    }
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="nazwa">Nazwa kategorii:</label>';
    $wynik .= '<input type="text" name="nazwa" id="nazwa" value="' . htmlspecialchars($nazwa) . '" class="form-control" required>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="matka">Kategoria nadrzędna:</label>';
    $wynik .= '<select name="matka" id="matka" class="form-control">';
    $wynik .= '<option value="0"' . ($matka == 0 ? ' selected' : '') . '>Brak (kategoria główna)</option>';
    
    // Pobierz listę kategorii głównych
    $query = "SELECT * FROM categories WHERE matka = 0 ORDER BY nazwa ASC";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result)) {
        if($id != $row['id']) { // Nie pokazuj aktualnej kategorii jako możliwej matki
            $wynik .= '<option value="' . $row['id'] . '"' . ($matka == $row['id'] ? ' selected' : '') . '>' 
                   . htmlspecialchars($row['nazwa']) . '</option>';
        }
    }
    
    $wynik .= '</select>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-buttons">';
    $wynik .= '<input type="submit" name="submit" value="' . $przycisk . '" class="btn-save">';
    $wynik .= '<a href="admin.php?action=categories" class="btn-cancel">Anuluj</a>';
    $wynik .= '</div>';
    
    $wynik .= '</form>';
    $wynik .= '</div>';
    
    return $wynik;
}

// Dodawanie nowej kategorii
function DodajKategorie() {
    global $conn;
    
    if(isset($_POST['submit'])) {
        $nazwa = mysqli_real_escape_string($conn, $_POST['nazwa']);
        $matka = intval($_POST['matka']);
        
        $query = "INSERT INTO categories (nazwa, matka) VALUES ('$nazwa', $matka)";
        
        if(mysqli_query($conn, $query)) {
            header("Location: admin.php?action=categories&success=1");
            exit();
        }
    }
    
    return FormularzKategorii();
}

// Edycja kategorii
function EdytujKategorie() {
    global $conn;
    
    if(!isset($_GET['id'])) {
        return '';
    }
    
    $id = intval($_GET['id']);
    
    if(isset($_POST['submit'])) {
        $nazwa = mysqli_real_escape_string($conn, $_POST['nazwa']);
        $matka = intval($_POST['matka']);
        
        $query = "UPDATE categories SET nazwa = '$nazwa', matka = $matka WHERE id = $id LIMIT 1";
        
        if(mysqli_query($conn, $query)) {
            header("Location: admin.php?action=categories&success=1");
            exit();
        }
    }
    
    return FormularzKategorii($id);
}

// Usuwanie kategorii
function UsunKategorie() {
    global $conn;
    
    if(isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        // Najpierw aktualizuj podkategorie (ustaw matka = 0)
        $query = "UPDATE categories SET matka = 0 WHERE matka = $id";
        mysqli_query($conn, $query);
        
        // Następnie usuń kategorię
        $query = "DELETE FROM categories WHERE id = $id LIMIT 1";
        mysqli_query($conn, $query);
        
        header("Location: admin.php?action=categories&success=1");
        exit();
    }
}

// Wyświetla listę wszystkich produktów w formie kartek
function PokazProdukty() {
    global $conn;
    
    $query = "SELECT p.*, c.nazwa as kategoria_nazwa 
              FROM products p 
              LEFT JOIN categories c ON p.kategoria = c.id 
              ORDER BY p.id DESC";
    $result = mysqli_query($conn, $query);
    
    $wynik = '<div class="panel-admin">';
    $wynik .= '<h2>Lista Produktów</h2>';
    
    // Przycisk dodawania nowego produktu
    $wynik .= '<div class="admin-actions">';
    $wynik .= '<a href="admin.php?action=addproduct" class="btn-add">+ Dodaj Nowy Produkt</a>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="product-grid">';
    
    while($row = mysqli_fetch_array($result)) {
        $status = SprawdzStatusProduktu($row);
        
        $wynik .= '<div class="product-card">';
        $wynik .= '<div class="product-image">';
        if($row['zdjecie']) {
            $wynik .= '<img src="' . htmlspecialchars($row['zdjecie']) . '" alt="' . htmlspecialchars($row['tytul']) . '">';
        } else {
            $wynik .= '<div class="no-image">Brak zdjęcia</div>';
        }
        $wynik .= '</div>';
        
        $wynik .= '<div class="product-info">';
        $wynik .= '<h3>' . htmlspecialchars($row['tytul']) . '</h3>';
        $wynik .= '<p class="product-category">Kategoria: ' . htmlspecialchars($row['kategoria_nazwa'] ?? 'Brak') . '</p>';
        $wynik .= '<p class="product-price">Cena: ' . number_format($row['cena_netto'] * (1 + $row['podatek_vat']), 2) . ' zł</p>';
        $wynik .= '<p class="product-stock">Stan magazynowy: ' . $row['ilosc'] . ' szt.</p>';
        
        $wynik .= '<div class="product-status ' . $status['class'] . '">';
        $wynik .= '<span class="status-dot"></span>';
        $wynik .= '<span class="status-text">' . $status['text'] . '</span>';
        $wynik .= '</div>';
        
        $wynik .= '<div class="product-dates">';
        $wynik .= '<small>Utworzono: ' . $row['data_utworzenia'] . '</small><br>';
        $wynik .= '<small>Modyfikacja: ' . $row['data_modyfikacji'] . '</small><br>';
        $wynik .= '<small>Wygasa: ' . $row['data_wygasniecia'] . '</small>';
        $wynik .= '</div>';
        
        $wynik .= '<div class="product-actions">';
        $wynik .= '<a href="admin.php?action=editproduct&id=' . $row['id'] . '" class="btn-edit">Edytuj</a>';
        $wynik .= '<a href="admin.php?action=deleteproduct&id=' . $row['id'] . '" class="btn-delete" onclick="return confirm(\'Czy na pewno chcesz usunąć ten produkt?\')">Usuń</a>';
        $wynik .= '</div>';
        
        $wynik .= '</div>';
        $wynik .= '</div>';
    }
    
    $wynik .= '</div>';
    $wynik .= '</div>';
    
    return $wynik;
}

// Sprawdza status produktu na podstawie jego danych
function SprawdzStatusProduktu($produkt) {
    $dzis = new DateTime();
    $data_wygasniecia = new DateTime($produkt['data_wygasniecia']);
    
    if ($produkt['ilosc'] <= 0) {
        return ['text' => 'Brak w magazynie', 'class' => 'unavailable'];
    }
    
    if ($data_wygasniecia < $dzis) {
        return ['text' => 'Wygasły', 'class' => 'expired'];
    }
    
    if ($produkt['ilosc'] < 5) {
        return ['text' => 'Końcówka', 'class' => 'low-stock'];
    }
    
    return ['text' => 'Dostępny', 'class' => 'available'];
}

// Formularz dodawania/edycji produktu
function FormularzProduktu($id = null) {
    global $conn;
    
    $tytul = '';
    $cena_netto = '';
    $podatek_vat = 0.23;
    $ilosc = '';
    $kategoria = '';
    $zdjecie = '';
    $data_wygasniecia = '';
    $tytul_formularza = 'Dodaj Nowy Produkt';
    $akcja = 'addproduct';
    $przycisk = 'Dodaj produkt';
    
    if($id) {
        $query = "SELECT * FROM products WHERE id = " . intval($id) . " LIMIT 1";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        
        if($row) {
            $tytul = $row['tytul'];
            $cena_netto = $row['cena_netto'];
            $podatek_vat = $row['podatek_vat'];
            $ilosc = $row['ilosc'];
            $kategoria = $row['kategoria'];
            $zdjecie = $row['zdjecie'];
            $data_wygasniecia = $row['data_wygasniecia'];
            $tytul_formularza = 'Edytuj Produkt';
            $akcja = 'editproduct';
            $przycisk = 'Zapisz zmiany';
        }
    }
    
    $wynik = '<div class="panel-admin">';
    $wynik .= '<h2>' . $tytul_formularza . '</h2>';
    $wynik .= '<form action="admin.php?action=' . $akcja . ($id ? '&id=' . $id : '') . '" method="post" class="admin-form">';
    
    if($id) {
        $wynik .= '<input type="hidden" name="id" value="' . $id . '">';
    }
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="tytul">Tytuł produktu:</label>';
    $wynik .= '<input type="text" name="tytul" id="tytul" value="' . htmlspecialchars($tytul) . '" class="form-control" required>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="cena_netto">Cena netto:</label>';
    $wynik .= '<input type="number" step="0.01" name="cena_netto" id="cena_netto" value="' . $cena_netto . '" class="form-control" required>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="podatek_vat">VAT (jako ułamek, np. 0.23 dla 23%):</label>';
    $wynik .= '<input type="number" step="0.01" name="podatek_vat" id="podatek_vat" value="' . $podatek_vat . '" class="form-control" required>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="ilosc">Ilość w magazynie:</label>';
    $wynik .= '<input type="number" name="ilosc" id="ilosc" value="' . $ilosc . '" class="form-control" required>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="kategoria">Kategoria:</label>';
    $wynik .= '<select name="kategoria" id="kategoria" class="form-control">';
    
    // Pobierz listę kategorii
    $query = "SELECT * FROM categories ORDER BY nazwa ASC";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result)) {
        $wynik .= '<option value="' . $row['id'] . '"' . ($kategoria == $row['id'] ? ' selected' : '') . '>' 
               . htmlspecialchars($row['nazwa']) . '</option>';
    }
    
    $wynik .= '</select>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="zdjecie">Zdjęcie (URL):</label>';
    $wynik .= '<input type="text" name="zdjecie" id="zdjecie" value="' . htmlspecialchars($zdjecie) . '" class="form-control">';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-group">';
    $wynik .= '<label for="data_wygasniecia">Data wygaśnięcia:</label>';
    $wynik .= '<input type="date" name="data_wygasniecia" id="data_wygasniecia" value="' . $data_wygasniecia . '" class="form-control" required>';
    $wynik .= '</div>';
    
    $wynik .= '<div class="form-buttons">';
    $wynik .= '<input type="submit" name="submit" value="' . $przycisk . '" class="btn-save">';
    $wynik .= '<a href="admin.php?action=products" class="btn-cancel">Anuluj</a>';
    $wynik .= '</div>';
    
    $wynik .= '</form>';
    $wynik .= '</div>';
    
    return $wynik;
}

// Dodawanie nowego produktu
function DodajProdukt() {
    global $conn;
    
    if(isset($_POST['submit'])) {
        $tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
        $cena_netto = floatval($_POST['cena_netto']);
        $podatek_vat = floatval($_POST['podatek_vat']);
        $ilosc = intval($_POST['ilosc']);
        $kategoria = intval($_POST['kategoria']);
        $zdjecie = mysqli_real_escape_string($conn, $_POST['zdjecie']);
        $data_wygasniecia = mysqli_real_escape_string($conn, $_POST['data_wygasniecia']);
        $data_utworzenia = date('Y-m-d');
        $data_modyfikacji = date('Y-m-d');
        
        $query = "INSERT INTO products (tytul, cena_netto, podatek_vat, ilosc, kategoria, zdjecie, 
                                      data_utworzenia, data_modyfikacji, data_wygasniecia) 
                 VALUES ('$tytul', $cena_netto, $podatek_vat, $ilosc, $kategoria, '$zdjecie', 
                         '$data_utworzenia', '$data_modyfikacji', '$data_wygasniecia')";
        
        if(mysqli_query($conn, $query)) {
            header("Location: admin.php?action=products&success=1");
            exit();
        }
    }
    
    return FormularzProduktu();
}

// Edycja produktu
function EdytujProdukt() {
    global $conn;
    
    if(isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
            $cena_netto = floatval($_POST['cena_netto']);
            $podatek_vat = floatval($_POST['podatek_vat']);
            $ilosc = intval($_POST['ilosc']);
            $kategoria = intval($_POST['kategoria']);
            $zdjecie = mysqli_real_escape_string($conn, $_POST['zdjecie']);
            $data_wygasniecia = mysqli_real_escape_string($conn, $_POST['data_wygasniecia']);
            $data_modyfikacji = date('Y-m-d H:i:s');
            
            $query = "UPDATE products SET 
                    tytul = '$tytul',
                    cena_netto = $cena_netto,
                    podatek_vat = $podatek_vat,
                    ilosc = $ilosc,
                    kategoria = $kategoria,
                    zdjecie = '$zdjecie',
                    data_modyfikacji = '$data_modyfikacji',
                    data_wygasniecia = '$data_wygasniecia'
                 WHERE id = $id LIMIT 1";
            
            if(mysqli_query($conn, $query)) {
                header("Location: admin.php?action=products&success=1");
                exit();
            }
        }
        
        return FormularzProduktu($id);
    }
    
    header("Location: admin.php?action=products");
    exit();
}

// Usuwanie produktu
function UsunProdukt() {
    global $conn;
    
    if(isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $query = "DELETE FROM products WHERE id = $id LIMIT 1";
        mysqli_query($conn, $query);
        header("Location: admin.php?action=products&success=1");
        exit();
    }
}

// Główna funkcja panelu administracyjnego
// Zarządza wyświetlaniem odpowiednich widoków w zależności od akcji
function PanelAdministracyjny() {
    $status = SprawdzLogowanie();
    
    if ($status === true) {
        $wynik = '<div class="admin-panel">';
        $wynik .= '<h1>Panel Administracyjny</h1>';
        $wynik .= '<div class="admin-options">';
        $wynik .= '<a href="admin.php?action=products" class="btn-menu">Produkty</a>';
        $wynik .= '<a href="admin.php?action=categories" class="btn-menu">Kategorie</a>';
        $wynik .= '<a href="admin.php?action=logout" class="btn-logout">Wyloguj</a>';
        $wynik .= '</div>';
        
        if(isset($_GET['success'])) {
            $wynik .= '<div class="success">Operacja zakończona pomyślnie!</div>';
        }
        
        if(isset($_GET['action'])) {
            switch($_GET['action']) {
                case 'categories':
                    $wynik .= PokazKategorie();
                    break;
                case 'addcategory':
                    $wynik .= DodajKategorie();
                    break;
                case 'editcategory':
                    $wynik .= EdytujKategorie();
                    break;
                case 'products':
                    $wynik .= PokazProdukty();
                    break;
                case 'addproduct':
                    $wynik .= DodajProdukt();
                    break;
                case 'editproduct':
                    $wynik .= EdytujProdukt();
                    break;
                case 'deletecategory':
                    UsunKategorie();
                    break;
                case 'deleteproduct':
                    UsunProdukt();
                    break;
                case 'logout':
                    Wyloguj();
                    break;
                default:
                    $wynik .= PokazProdukty();
            }
        } else {
            $wynik .= PokazProdukty();
        }
        
        $wynik .= '</div>';
        return $wynik;
    } else {
        return FormularzLogowania($status !== false ? $status : "");
    }
}

// Wylogowuje użytkownika i niszczy sesję
function Wyloguj() {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Obsługa aktualizacji danych podstrony
// Wykonywana po wysłaniu formularza edycji
if(isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $page_title = mysqli_real_escape_string($conn, $_POST['page_title']);
    $page_content = mysqli_real_escape_string($conn, $_POST['page_content']);
    $status = isset($_POST['status']) ? 1 : 0;
    
    $query = "UPDATE page_list SET 
              page_title = '$page_title',
              page_content = '$page_content',
              status = $status
              WHERE id = $id LIMIT 1";
    
    mysqli_query($conn, $query);
    header("Location: admin.php");
    exit();
}



echo PanelAdministracyjny();
?>
</body>
</html>
