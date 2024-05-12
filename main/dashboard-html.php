<?php 
    session_start();
    if (!isset($_SESSION['pseudo'])) // if user not connected, bring back to connection
    {
        header("Location: sign-in-html.php");
    }
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="icon" href="../media/logo.png">
    <title>LeSunShine</title>
</head>

<body>
    <header>
        <img class="logo" src="../media/logo.png" alt="logo">
        <p id="title">LeSunShine</p>
    </header>

    <div id="lower">
        <div class="box" onclick="location.href='search.html'">
            <h1>Recherche</h1>
            <p>Description</p>
        </div>
        <div class="box" onclick="location.href='inbox.html'">
            <h1>Messages</h1>
            <p>Description</p>
        </div>
        <div class="box" onclick="location.href='pricing.html'">
            <h1>Abonnements</h1>
            <p>Description</p>
        </div>
        <div class="box" onclick="location.href='profil.php'">
            <h1>Profil</h1>
            <p> Dans cette section, vous pourrez consulter les détails de votre profil, ce qui inclut la
                partie publique et privée renseignées à votre inscription sur le site.<br><br>
                Cela comprend pour la partie privée : votre pseudo, votre adresse, et la modfication de votre mot de
                passe.<br><br>
                Pour la partie publique, vous pourrez choisir : votre sexe , votre date de naissance, votre profession,
                votre situation amoureuse et familiale, vos informations physiques, et vos photos.
            </p>
        </div>
    </div>
</body>

</html>