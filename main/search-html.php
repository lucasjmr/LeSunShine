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
    <link rel="stylesheet" type="text/css" href="search.css">
    <link rel="icon" href="../media/logo.png">
    <title>Recherche</title>
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
        <div id="form-box">
            <h1>Rechercher un profil</h1>
            <form action="search-result.php" method="POST">
                <input type="text" name="pseudo" minlength="3" maxlength="16" placeholder="Pseudo">
                <div class="age">
                    <input class="age-input" type="number" name="age_min" min="18" max="122" placeholder="Age minimum">
                    <input class="age-input" type="number" name="age_max" min="18" max="122" placeholder="Age maximum">
                </div>

                <input type="radio" name="gender" id="male" value="Homme">
                <label for="male">Homme</label>
                <input type="radio" name="gender" id="female" value="Femme">
                <label for="female">Femme</label>

                <input type="radio" name="rank" id="bronze" value="bronze">
                <label for="bronze">Bronze rank</label>
                <input type="radio" name="rank" id="silver" value="silver">
                <label for="silver">Silver rank</label>
                <input type="radio" name="rank" id="gold" value="gold">
                <label for="gold">Gold rank</label>
                <input type="radio" name="rank" id="platinum" value="platinum">
                <label for="platinum">Platinum  rank</label>

                <input type="radio" name="photo" id="photo" value="avec">
                <label for="photo">Avec Photo</label>
                <input type="radio" name="photo" id="sans-photo" value="sans">
                <label for="sans-photo">Sans Photo</label>

                <input type="submit" name="submit" value="Rechercher">
            </form>
        </div>
    </div>
</body>

</html>