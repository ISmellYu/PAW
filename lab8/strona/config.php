<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';
$login = 'admin';
$pass = 'admin';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
if (!$conn) {
    echo '<b>przerwane połączenie </b>';
}
?>
