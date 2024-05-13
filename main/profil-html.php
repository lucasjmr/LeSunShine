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
    <link rel="stylesheet" type="text/css" href="profil.css">
    <link rel="icon" href="../media/logo.png">
    <title>Profil</title>
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
        <div class="box">
            <div class="box_part">
                <h1>Partie publique</h1>
                <p>Pseudo :
                    <?= $_SESSION['pseudo'] ?>
                </p>
                <p>Date d'inscription :
                    <?= $_SESSION['signup_date'] ?>
                </p>
                <p>Genre :
                    <?= $_SESSION['gender'] ?>
                </p>
                <p>Date anniversaire :
                    <?= $_SESSION['birthday'] ?>
                </p>
                <p>Age :
                    <?php
                    $date = new DateTime($_SESSION['birthday']);
                    $now = new DateTime();
                    $interval = $now->diff($date);
                    echo "$interval->y ans";
                    ?>
                </p>
                <p>Message customisé :
                    <?= $_SESSION['custom_message'] ?>
                </p>
            </div>
            <div class="box_part">
                <h1>Partie privée</h1>
                <p>Nom de famille :
                    <?= $_SESSION['last_name'] ?>
                </p>
                <p>Prénom :
                    <?= $_SESSION['first_name'] ?>
                </p>
                <p>Adresse postale :
                    <?= $_SESSION['home_adress'] ?>
                </p>
                <p>Email :
                    <?= $_SESSION['email'] ?>
                </p>
                <p>Abonnement :
                    <?= $_SESSION['rank'] ?>
                </p>
            </div>
        </div>
        <div class="box">
            <h1>Photo</h1>
            <div class="image-container">
                <img src="../data/images/<?php echo $_SESSION['pseudo'] ?>.jpg" alt="Veuillez uploader une image.">
            </div>
            <div id="upload-form" onclick="location.href='upload-html.php'">
                Uploader une image
            </div>
        </div>
    </div>
</body>

</html>