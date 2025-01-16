<?php
// Funkcja wyświetlająca zawartość podstrony na podstawie jej ID
function PokazPodstrone($id)
{
    global $conn;  // Używamy globalnego połączenia z bazą danych
    
    // Zabezpieczenie przed atakiem SQL Injection
    // Oczyszczamy parametr ID przed użyciem w zapytaniu
    $id_clear = htmlspecialchars($id);

    // Zapytanie pobierające treść podstrony z bazy danych
    // LIMIT 1 zapewnia pobranie tylko jednego rekordu
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

    // Sprawdzenie czy podstrona istnieje
    if (empty($row['id']))
    {
        // Jeśli nie znaleziono podstrony, zwracamy komunikat błędu
        $web = '[nie_znaleziono_strony]';
    }
    else
    {
        // Jeśli znaleziono podstronę, zwracamy jej zawartość
        $web = $row['page_content'];
    }

    return $web;
}
?>
