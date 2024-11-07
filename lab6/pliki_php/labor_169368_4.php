<?php
 $nr_indeksu = '169368';
 $nrGrupy = '4';
 echo 'Łukasz Szostak ' . $nr_indeksu . ' grupa: ' . $nrGrupy . ' <br /><br />';


echo 'zad 1<br />';
include 'include.php';
echo '<br />';
require_once 'include.php';
echo '<br /><br />';


echo 'Warunki <br />';
$przykladowa_liczba = 10;
if ($przykladowa_liczba % 3 == 0) {
    echo 'Liczba jest podzielna przez 3 <br />';
} elseif ($przykladowa_liczba % 3 == 2) {
    echo 'Liczba po dzieleniu ma reszte 2 <br />';
} else {
    echo 'Liczba po dzieleniu ma reszte 1 lub 0 <br />';
}

$gwiazdki = 5;
switch ($gwiazdki) {
    case 7:
        echo '7 gwiazdek <br />';
        break;
    case 6:
        echo '6 gwiazdek <br />';
        break;
    case 5:
        echo '5 gwiazdek <br />';
        break;
    case 4:
        echo '4 gwiazdki <br />';
        break;
    case 3:
        echo '3 gwiazdki <br />';
        break;
    case 2:
        echo '2 gwiazdki <br />';
        break;
    case 1:
        echo '1 gwiazdka <br />';
        break;
    default:
        echo 'Jakas inna gwiazdka <br />';
}

echo 'Odliczanie od 1 do 10 <br />';
$i = 1;
while ($i <= 10) {
    echo 'Odliaczanie aktualne: : ' . $i . '<br />';
    $i++;
}

echo 'Odliczanie od 1 do 10: <br />';
for ($j = 1; $j <= 10; $j++) {
    echo 'Odliaczanie aktualne: ' . $j . '<br />';
}


echo 'Tu sprawdzamy czy mamy ustawione 'haslo' w get <br />';
if (isset($_GET['haslo'])) {
    echo 'Twoje haslo to, ' . $_GET['haslo'] . '! <br />';
} else {
    echo 'Podaj ponownie swoje haslo <br />';
}

echo 'Tu sprawdzamy czy mamy ustawione 'haslo' w post  <br />';
echo '<form method="POST">
        <input type="text" name="haslo" placeholder="Podaj haslo">
        <input type="submit" value="send">
      </form>';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['haslo'])) {
    echo 'Haslo:, ' . $_POST['haslo'] . '! <br />';
}

session_start();
$_SESSION['liczba_palcow'] = 10;
echo 'zmienna z sesji $_SESSION: liczba_palcow = ' . $_SESSION['liczba_palcow'] . '<br />';
?>