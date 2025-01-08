<?php
// Wyłączenie raportowania niektórych błędów dla lepszej czytelności strony
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Dołączenie plików konfiguracyjnych i funkcji
include("config.php");
include("showpage.php");

// Routing stron - określanie która podstrona ma zostać wyświetlona
if($_GET['idp'] == '' || !isset($_GET['idp'])) {
    // Strona domyślna (ID: 4)
    $page = PokazPodstrone(4);
} elseif($_GET['idp'] == 'assembly') {
    // Podstrona Assembly (ID: 1)
    $page = PokazPodstrone(1);
} elseif($_GET['idp'] == 'dekompilacja') {
    // Podstrona Dekompilacja (ID: 2)
    $page = PokazPodstrone(2);
} elseif($_GET['idp'] == 'narzedzia') {
    // Podstrona Narzędzia (ID: 8)
    $page = PokazPodstrone(8);
} elseif($_GET['idp'] == 'typyplikow') {
    // Podstrona Typy plików (ID: 9)
    $page = PokazPodstrone(9);
} elseif($_GET['idp'] == 'metodyinzynieri') {
    // Podstrona Metody inżynierii wstecznej (ID: 7)
    $page = PokazPodstrone(7);
} elseif($_GET['idp'] == 'kontakt') {
    // Podstrona Kontakt (ID: 6)
    $page = PokazPodstrone(6);
} elseif($_GET['idp'] == 'filmy'){
    // Podstrona Filmy (ID: 3)
    $page = PokazPodstrone(3);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- Metadane strony -->
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Lukasz Szostak" />
    <title>Inżynieria wsteczna</title>
    <!-- Dołączenie arkuszy stylów -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navigation.css">
</head>
<body>
    <!-- Nagłówek strony -->
    <a href="index.php" class="main-title">Inżynieria wsteczna</a>
    
    <!-- Menu nawigacyjne -->
    <table>
        <tr>
            <td><a href="index.php?idp=assembly" class="nav-btn">Assembly</a></td>
            <td><a href="index.php?idp=dekompilacja" class="nav-btn">Dekompilacja</a></td>
            <td><a href="index.php?idp=narzedzia" class="nav-btn">Narzedzia</a></td>
            <td><a href="index.php?idp=typyplikow" class="nav-btn">Typy plikow</a></td>
            <td><a href="index.php?idp=metodyinzynieri" class="nav-btn">Metody inżynierii wstecznej</a></td>
            <td><a href="index.php?idp=filmy" class="nav-btn">Filmy</a></td>
            <td><a href="index.php?idp=kontakt" class="nav-btn">Kontakt</a></td>
            <td><a href="products.php" class="nav-btn">Produkty</a></td>
            <td><a href="cart.php" class="nav-btn">Koszyk</a></td>
        </tr>
    </table>

    <?php
    // Wyświetlenie zawartości wybranej podstrony
    echo $page;
    
    // Informacje o autorze
    $nr_indeksu = '169368';
    $nrGrupy = '4';
    echo 'Autor: Łukasz Szostak '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
    ?>

</body>
</html> 