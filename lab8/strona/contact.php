<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
    <link rel="stylesheet" href="css/admin.css" />
</head>
<body>

<?php
require_once('config.php');

function PokazKontakt() {
    $wynik = '
    <div class="contact-form">
        <h2>Formularz kontaktowy</h2>
        <form method="post" action="' . $_SERVER['REQUEST_URI'] . '" class="form-container">
            <div class="form-group">
                <label for="nadawca">Twój email:</label>
                <input type="email" 
                       name="nadawca" 
                       id="nadawca" 
                       class="form-control" 
                       required 
                       placeholder="jan.kowalski@example.com">
            </div>
            
            <div class="form-group">
                <label for="temat">Temat wiadomości:</label>
                <input type="text" 
                       name="temat" 
                       id="temat" 
                       class="form-control" 
                       required 
                       placeholder="Wpisz temat wiadomości">
            </div>
            
            <div class="form-group">
                <label for="tresc">Treść wiadomości:</label>
                <textarea name="tresc" 
                          id="tresc" 
                          class="form-control" 
                          rows="6" 
                          required 
                          placeholder="Wpisz treść swojej wiadomości"></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" name="wyslij" class="btn-submit">
                    Wyślij wiadomość
                </button>
            </div>
        </form>
    </div>';
    
    return $wynik;
}

function WyslijMailKontakt() {
    if (isset($_POST['wyslij'])) {
        // Pobieranie i czyszczenie danych
        $nadawca = filter_var($_POST['nadawca'], FILTER_SANITIZE_EMAIL);
        $odbiorca = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $temat = htmlspecialchars($_POST['temat']);
        $tresc = htmlspecialchars($_POST['tresc']);
        
        // Walidacja emaila
        if (!filter_var($nadawca, FILTER_VALIDATE_EMAIL)) {
            return '<div class="alert error">Podany adres email jest nieprawidłowy.</div>';
        }
        
        // Przygotowanie nagłówków
        $naglowki = "From: $nadawca\r\n";
        $naglowki .= "Reply-To: $nadawca\r\n";
        $naglowki .= "MIME-Version: 1.0\r\n";
        $naglowki .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        // Formatowanie treści
        $wiadomosc = "<html><body>";
        $wiadomosc .= "<h2>Nowa wiadomość z formularza kontaktowego</h2>";
        $wiadomosc .= "<p><strong>Od:</strong> $nadawca</p>";
        $wiadomosc .= "<p><strong>Temat:</strong> $temat</p>";
        $wiadomosc .= "<p><strong>Treść:</strong><br>" . nl2br($tresc) . "</p>";
        $wiadomosc .= "</body></html>";
        
        // Wysyłanie maila
        if (mail($odbiorca, $temat, $wiadomosc, $naglowki)) {
            return '<div class="alert success">Wiadomość została wysłana pomyślnie!</div>';
        } else {
            return '<div class="alert error">Wystąpił błąd podczas wysyłania wiadomości.</div>';
        }
    }
    return '';
}

function PrzypomnijHaslo() {
    global $pass, $login;
    $wynik = '';
    
    if (isset($_POST['przypomnij_haslo'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        // Sprawdzenie czy podany email zgadza się z loginem z config.php
        if ($email === $login) {
            // Wysyłanie maila z hasłem
            $temat = "Przypomnienie hasła";
            $tresc = "Twoje hasło to: " . $pass;
            $naglowki = "From: admin@example.com\r\n";
            $naglowki .= "MIME-Version: 1.0\r\n";
            $naglowki .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            // Formatowanie HTML
            $wiadomosc = "<html><body>";
            $wiadomosc .= "<h2>Przypomnienie hasła</h2>";
            $wiadomosc .= "<p>Twoje hasło do panelu administracyjnego to: <strong>" . $pass . "</strong></p>";
            $wiadomosc .= "<p>Ze względów bezpieczeństwa zalecamy zmianę hasła po zalogowaniu.</p>";
            $wiadomosc .= "</body></html>";
            
            if (mail($email, $temat, $wiadomosc, $naglowki)) {
                $wynik .= '<div class="alert success">Hasło zostało wysłane na podany adres email.</div>';
            } else {
                $wynik .= '<div class="alert error">Wystąpił błąd podczas wysyłania hasła.</div>';
            }
        } else {
            $wynik .= '<div class="alert error">Podany adres email jest nieprawidłowy.</div>';
        }
    }
    
    $wynik .= '
    <div class="password-reset-form">
        <h2>Przypomnij hasło</h2>
        <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
            <div class="form-group">
                <label for="email">Podaj swój email administracyjny:</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       class="form-control" 
                       required 
                       placeholder="Wprowadź swój email">
            </div>
            
            <div class="form-group">
                <button type="submit" 
                        name="przypomnij_haslo" 
                        class="btn-submit">
                    Przypomnij hasło
                </button>
            </div>
        </form>
    </div>';
    
    return $wynik;
}
echo WyslijMailKontakt();
echo PokazKontakt();

?>
