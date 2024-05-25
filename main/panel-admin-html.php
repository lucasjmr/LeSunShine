<?php
session_start();

function error_page($message)
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    $errorPage = str_replace('history.back()', "location.href='dashboard-html.php'", $errorPage);
    return $errorPage;
}

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != "admin")
{
    echo error_page("Vous n'êtes pas administrateur.");
    exit();
}

?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="panel.css">
    <link rel="icon" href="../media/logo.png">
    <title>Panel admin</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">LeSunShine</p>
        </div>
        <div id="header-container-right">
            <div class="button" onclick="location.href='dashboard-html.php'">
                Dashboard
            </div>
        </div>
    </header>
    <div id="lower">
        <div class="box" onclick="location.href='user-gestion-html.php'">
            <h1>GESTION UTILISATEURS</h1>
            <p>Accédez à tous les profils utilisateur et modifiez-les ou bannissez-les</p>
        </div>
        <div class="box" onclick="location.href='message-gestion-html.php'">
            <h1>GESTION MESSAGERIE</h1>
            <p>Gérez les messageries des utilisateurs et réceptionnez les signalements</p>
        </div>
    </div>

</body>

</html>