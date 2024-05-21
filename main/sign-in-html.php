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
if (!is_dir("../data/conversations"))
{
    mkdir("../data/conversations");
}
if (!file_exists("../data/logins.sunshine"))
{
    $userfile = fopen("../data/logins.sunshine", "w");
    if(!$userfile)
    {
        exit('A problem occured while creating logins file');
    }
    if (!fclose($userfile))
    {
        exit("Something went wrong while trying to close logins file");
    }
}
if (!file_exists("../data/reports.sunshine"))
{
    $userfile = fopen("../data/reports.sunshine", "w");
    if(!$userfile)
    {
        exit('A problem occured while creating report file');
    }
    if (!fclose($userfile))
    {
        exit("Something went wrong while trying to close report file");
    }
}

?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="sign-in-up.css">
    <link rel="icon" href="../media/logo.png">
    <title>Connexion</title>
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
            <p id="form-title">Connexion</p>
            <form action="sign-in.php" method="POST">
                <input type="text" name="pseudo" minlength="3" maxlength="16" placeholder="Pseudo">
                <input type="password" name="password" minlength="3" maxlength="16" placeholder="Mot De Passe">
                <input type="submit" name="submit" value="Valider">
            </form>
            <p id="no-account">Pas de compte ? <a href="sign-up-html.php">S'inscrire</p></a>
        </div>
    </div>
</body>

</html>