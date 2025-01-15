<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

include("config.php");
include("showpage.php");

// Handle page routing
if($_GET['idp'] == '' || !isset($_GET['idp'])) {
    $page = PokazPodstrone(4); // Default page
} else {
    $id = intval($_GET['idp']);
    $page = PokazPodstrone($id);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Lukasz Szostak" />
    <title>Inżynieria wsteczna</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/script_java_style.css">
</head>
<body>
    <a href="index.php" class="main-title">Inżynieria wsteczna</a>
    
    <!-- Include navigation -->
    <?php include('nav.php'); ?>

    <!-- Main Content -->
    <main class="content">
        <?php echo $page; ?>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <?php
        $nr_indeksu = '169368';
        $nrGrupy = '4';
        echo '<p>Autor: Łukasz Szostak '.$nr_indeksu.' grupa '.$nrGrupy.'</p>';
        ?>
    </footer>
</body>
</html>