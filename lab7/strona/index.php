<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
include("config.php");
include("showpage.php");

if($_GET['idp'] == '' || !isset($_GET['idp'])) {
    $page = PokazPodstrone(4);
} elseif($_GET['idp'] == 'assembly') {
    $page = PokazPodstrone(1);
} elseif($_GET['idp'] == 'dekompilacja') {
    $page = PokazPodstrone(2);
} elseif($_GET['idp'] == 'narzedzia') {
    $page = PokazPodstrone(8);
} elseif($_GET['idp'] == 'typyplikow') {
    $page = PokazPodstrone(9);
} elseif($_GET['idp'] == 'metodyinzynieri') {
    $page = PokazPodstrone(7);
} elseif($_GET['idp'] == 'kontakt') {
    $page = PokazPodstrone(6);
} elseif($_GET['idp'] == 'filmy'){
    $page = PokazPodstrone(3);
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
    echo $page;
    $nr_indeksu = '169368';
    $nrGrupy = '4';
    echo 'Autor: Łukasz Szostak '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
    ?>

</body>
</html> 