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
    <link rel="stylesheet" type="text/css" href="pricing.css">
    <link rel="icon" href="../media/logo.png">
    <title>Abonnements</title>
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
        <div class="box" onclick="location.href='rank-process.php?rank=bronze'">
            <h1>Bronze</h1>
            <p>Rang de base sans avantage. Montez en rang pour débloquer toutes les fonctionnalités</p>
            <p class="price-tag">Prix : gratuit</p>
            <span>
                <?php
                if ($_SESSION['rank'] == 'bronze')
                {
                    echo "Votre abonnement actuel";
                }
                ?>
            </span>
        </div>
        <div class="box" onclick="location.href='rank-process.php?rank=silver'">
            <h1>Silver</h1>
            <p>Vous débloquez l'accès complet à toutes les fonctionnalités pour une durée d'un mois.</p>
            <p class="price-tag">Prix : 5€</p>
            <span>
                <?php
                if ($_SESSION['rank'] == 'silver')
                {
                    echo "Votre abonnement actuel";
                }
                ?>
            </span>
        </div>
        <div class="box" onclick="location.href='rank-process.php?rank=gold'">
            <h1>Gold</h1>
            <p>Vous débloquez l'accès complet à toutes les fonctionnalités pour une durée d'un an.</p>
            <p class="price-tag">Prix : 50€</p>
            <span>
                <?php
                if ($_SESSION['rank'] == 'gold')
                {
                    echo "Votre abonnement actuel";
                }
                ?>
            </span>
        </div>
        <div class="box" onclick="location.href='rank-process.php?rank=platinum'">
            <h1>Platinum</h1>
            <p>Vous débloquez l'accès complet à toutes les fonctionnalités pour une durée illimitée !</p>
            <p class="price-tag">Prix : 150€</p>
            <span>
                <?php
                if ($_SESSION['rank'] == 'platinum')
                {
                    echo "Votre abonnement actuel";
                }
                ?>
            </span>
        </div>
    </div>
</body>

</html>