<?php

// Verify if folders are present, if not, create them
if (!is_dir("../data"))
{
    mkdir("../data");
}
if (!is_dir("../data/images"))
{
    mkdir("../data/images");
}
if (!is_dir("../data/users"))
{
    mkdir("../data/users");
}
if (!is_dir("../data/visitors"))
{
    mkdir("../data/visitors");
}
if (!file_exists("../data/logins.sunshine"))
{
    $userfile = fopen("../data/logins.sunshine", "w");
    if (!$userfile)
    {
        exit('A problem occured while creating logins file');
    }
    if (!fclose($userfile))
    {
        exit("Something went wrong while trying to close logins file");
    }
}

?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="sign-in-up.css">
    <link rel="icon" href="../media/logo.png">
    <title>Inscription</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">LeSunShine</p>
        </div>
    </header>
    <div id="lower">
        <div id="form-box">
            <p id="form-title">Inscription</p>
            <div class="scroll">
                <form action="sign-up.php" method="POST">
                    <input type="text" name="pseudo" minlength="3" maxlength="16" placeholder="Pseudo">
                    <input type="email" name="email" minlength="8" maxlength="64" placeholder="Email">
                    <input type="password" name="password" minlength="3" maxlength="16" placeholder="Mot De Passe">

                    <input type="radio" name="gender" id="male" checked="checked" value="Homme">
                    <label for="male">Homme</label>
                    <input type="radio" name="gender" id="female" value="Femme">
                    <label for="female">Femme</label>

                    <div class="form-row">
                        <label for="birthday">Date de naissance</label>
                        <input type="date" name="birthday" id="birthday">
                    </div>

                    <input type="text" name="last_name" minlength="2" maxlength="32" placeholder="Nom de famille">
                    <input type="text" name="first_name" minlength="2" maxlength="32" placeholder="Prénom">
                    <input type="text" name="home_adress" minlength="12" maxlength="64" placeholder="Adresse postale">
                    <input type="text" name="custom_message" minlength="3" maxlength="64" placeholder="Message personnalisé">

                    <input type="submit" name="submit" value="Valider" id="end_of_scroll">
                </form>
            </div>
            <p id="no-account">Déjà un compte ? <a href="sign-in-html.php">Se connecter</p></a>
        </div>
    </div>
</body>

</html>