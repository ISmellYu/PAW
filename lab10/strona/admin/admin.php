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

// Główna funkcja panelu administracyjnego
// Zarządza wyświetlaniem odpowiednich widoków w zależności od akcji
function PanelAdministracyjny() {
    $status = SprawdzLogowanie();
    
    if ($status === true) {
        $wynik = '<div class="admin-panel">';
        $wynik .= '<h1>Panel Administracyjny</h1>';
        $wynik .= '<div class="admin-options">';
        $wynik .= '<a href="admin.php?action=logout" class="btn-logout">Wyloguj</a>';
        $wynik .= '</div>';
        
        if(isset($_GET['success'])) {
            $wynik .= '<div class="success">Operacja zakończona pomyślnie!</div>';
        }
        
        if(isset($_GET['action'])) {
            switch($_GET['action']) {
                case 'edit':
                    $wynik .= EdytujPodstrone();
                    break;
                case 'add':
                    $wynik .= DodajNowaPodstrone();
                    break;
                case 'delete':
                    UsunPodstrone();
                    break;
                case 'logout':
                    Wyloguj();
                    break;
                default:
                    $wynik .= ListaPodstron();
            }
        } else {
            $wynik .= ListaPodstron();
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
