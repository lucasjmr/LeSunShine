<?php
session_start();
if (!isset($_SESSION['pseudo'])) // if user not connected, bring back to connection
{
    header("Location: sign-in-html.php");
    exit();
}
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="modify.css">
    <link rel="icon" href="../media/logo.png">
    <title>Modifier Mot de Passe</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">LeSunShine</p>
        </div>
        <div id="header-container-right">
            <div class="button" onclick="location.href='profil-html.php'">
                Profil
            </div>
        </div>
    </header>
    <div id="lower">
        <div class="box">
            <h1>Modifier mot de passe</h1>
            <form action="modify.php" method="POST">
                <input type="password" name="password" minlength="3" maxlength="16" placeholder="Nouveau mot de passe">
                <input type="submit" name="submit1" value="Modifier">
            </form>
        </div>
        <div class="box">
            <h1>Modifier message personnalisé</h1>
            <form action="modify.php" method="POST">
                <input type="text" name="custom_message" minlength="3" maxlength="64" placeholder="Message personnalisé">
                <input type="submit" name="submit2" value="Modifier">
            </form>
        </div>
    </div>
</body>

</html>