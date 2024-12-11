<?php
// Konfiguracja połączenia z bazą danych
$dbhost = 'localhost';    // Adres serwera bazy danych
$dbuser = 'root';        // Nazwa użytkownika bazy danych
$dbpass = '';            // Hasło do bazy danych
$baza = 'moja_strona';   // Nazwa bazy danych

// Dane logowania do panelu administracyjnego
$login = 'admin';        // Login administratora
$pass = 'admin';         // Hasło administratora

// Nawiązanie połączenia z bazą danych
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

// Sprawdzenie czy połączenie zostało ustanowione
if (!$conn) {
    echo '<b>przerwane połączenie </b>';
}
?>
