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
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="icon" href="../media/logo.png">
    <title>Dashboard</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">LeSunShine</p>
        </div>
        <div id="header-container-right">
            <div class="button" onclick="location.href='sign-in-html.php'">
                Changer de compte
            </div>
            <?php if ($_SESSION['rank'] == "admin") : ?>
                <div class="button" onclick="location.href='sign-in-html.php'">
                    Panel admin
                </div>
            <?php endif; ?>
        </div>
    </header>

    <div id="lower">
        <div class="box" onclick="location.href='search-html.php'">
            <h1>Recherche</h1>
            <p>Dans cette section vous pourrez rechercher certains profils en fonctions de plusieurs critères comme le pseudo, l'age, le rang, ou encore est-ce que l'utilisateur a renseigné un pseudo. Vous pourrez ensuite contacter la/les personne.s si vous êtes abonné (voir section abonnements).</p>
        </div>
        <div class="box" onclick="location.href='inbox-html.php'">
            <h1>Messages</h1>
            <p>Dans cette section vous pourrez consulter toutes vos conversations avec les utilisateurs du site. Vous pourrez évidemment envoyer des messages, et démarrer de nouvelles conversations avec les personnes ayant visité votre profil</p>
        </div>
        <div class="box" onclick="location.href='pricing-html.php'">
            <h1>Abonnements</h1>
            <p>Dans cette section vous pourrez consulter votre abonnement et consulter les autres offres. Il existe 4 rangs distincts. A vous de faire le bon choix et de choisir le rang qui vous convient.</p>
        </div>
        <div class="box" onclick="location.href='profil-html.php'">
            <h1>Profil</h1>
            <p> Dans cette section, vous pourrez consulter les détails de votre profil renseignés à votre inscription sur le site.<br><br>
                Pour la partie publique, vous pourrez consulter : votre pseudo, votre date d'inscription, votre sexe, votre date de naissance, votre age, le rang de votre abonnement, votre message customisé et vos photos.
                <br><br>
                Et pour la partie privée : votre nom, prénom, adresse postale, email, et la date d'expiration de votre rang.
                <br><br>
                Vous pourrez modifier votre mot de passe ainsi que votre message personnalisé, et ajouter/changer de photo.
            </p>
        </div>
    </div>
</body>

</html>