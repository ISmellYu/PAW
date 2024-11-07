<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

if($_GET['idp'] == '' || !isset($_GET['idp'])) {
    $page = 'html/index.html';
} elseif($_GET['idp'] == 'assembly') {
    $page = 'html/assembly.html';
} elseif($_GET['idp'] == 'dekompilacja') {
    $page = 'html/dekompilacja.html';
} elseif($_GET['idp'] == 'narzedzia') {
    $page = 'html/narzedzia.html';
} elseif($_GET['idp'] == 'typyplikow') {
    $page = 'html/typyplikow.html';
} elseif($_GET['idp'] == 'metodyinzynieri') {
    $page = 'html/metodyinzynieri.html';
} elseif($_GET['idp'] == 'kontakt') {
    $page = 'html/kontakt.html';
} elseif($_GET['idp'] == 'filmy'){
    $page = 'html/filmy.html';
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Lukasz Szostak" />
    <title>Inżynieria wsteczna</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navigation.css">
</head>
<body>
    <a href="index.php" class="main-title">Inżynieria wsteczna</a>
    <table>
        <tr>
            <td><a href="index.php?idp=assembly" class="nav-btn">Assembly</a></td>
            <td><a href="index.php?idp=dekompilacja" class="nav-btn">Dekompilacja</a></td>
            <td><a href="index.php?idp=narzedzia" class="nav-btn">Narzedzia</a></td>
            <td><a href="index.php?idp=typyplikow" class="nav-btn">Typy plikow</a></td>
            <td><a href="index.php?idp=metodyinzynieri" class="nav-btn">Metody inżynierii wstecznej</a></td>
            <td><a href="index.php?idp=filmy" class="nav-btn">Filmy</a></td>
            <td><a href="index.php?idp=kontakt" class="nav-btn">Kontakt</a></td>
        </tr>
    </table>

    <?php
    if(file_exists($page)) {
        include($page);
    } else {
        echo '<div class="section about"><h2>Nie znaleziono strony</h2></div>';
    }
    $nr_indeksu = '169368';
    $nrGrupy = '4';
    echo 'Autor: Łukasz Szostak '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
    ?>

</body>
</html> 