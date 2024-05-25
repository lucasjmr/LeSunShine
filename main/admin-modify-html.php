<?php
session_start();

function error_page($message)
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    $errorPage = str_replace('history.back()', "location.href='user-gestion-html.php'", $errorPage);
    return $errorPage;
}

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != "admin")
{
    header("Location: user-gestion-html.php");
}

if (isset($_GET['user']))
{
    $userToModify = htmlspecialchars($_GET['user']);
    if (!file_exists("../data/users/$userToModify.sunshine"))
    {
        echo error_page("Cet utilisateur n'existe pas.");
        exit();
    }

    // Get every info about user and put them in variables.
    $userFile = fopen("../data/users/$userToModify.sunshine", "rb");
    if (!$userFile)
    {
        exit("Something went wrong while trying to open user file");
    }

    $content = fread($userFile, filesize("../data/users/$userToModify.sunshine"));
    if (!fclose($userFile))
    {
        exit("Something went wrong while trying to close user file");
    }

    $array = explode("\r\n", $content);

    // Get variables
    $pseudo = $array[0];
    $signup_date = $array[1];
    $gender = $array[2];
    $birthdate = $array[3];
    $message = $array[4];
    $last_name = $array[5];
    $first_name = $array[6];
    $home_adress = $array[7];
    $email = $array[8];
    $rank = $array[9];
    $exp_date = $array[10];
    $block = $array[11];
}
else
{
    header("Location: dashboard-html.php");
}
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="admin-modify.css">
    <link rel="icon" href="../media/logo.png">
    <title>Modifier profil</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">LeSunShine</p>
        </div>
        <div id="header-container-right">
            <div class="button" onclick="location.href='user-gestion-html.php'">
                Gestion utilisateurs
            </div>
        </div>
    </header>
    <div id="lower">
        <div id="form-box">
            <h1>Modifier profil de <?= $pseudo ?></h1>
            <form action="admin-modify.php" method="POST">

                <div class="form-group">
                    <label for="signup_date">Date d'inscription</label>
                    <input type="text" id="signup_date" name="signup_date" minlength="10" maxlength="10" value="<?= $signup_date ?>" required>
                </div>

                <div class="form-group">
                    <label for="gender">Genre</label>
                    <input type="text" id="gender" name="gender" minlength="3" maxlength="10" value="<?= $gender ?>" required>
                </div>

                <div class="form-group">
                    <label for="birthdate">Date de naissance</label>
                    <input type="text" id="birthdate" name="birthdate" minlength="10" maxlength="10" value="<?= $birthdate ?>" required>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <input type="text" id="message" name="message" minlength="3" maxlength="64" value="<?= $message ?>" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Nom de famille</label>
                    <input type="text" id="last_name" name="last_name" minlength="2" maxlength="32" value="<?= $last_name ?>" required>
                </div>

                <div class="form-group">
                    <label for="first_name">Prénom</label>
                    <input type="text" id="first_name" name="first_name" minlength="2" maxlength="32" value="<?= $first_name ?>" required>
                </div>

                <div class="form-group">
                    <label for="home_adress">Adresse</label>
                    <input type="text" id="home_adress" name="home_adress" minlength="12" maxlength="64" value="<?= $home_adress ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" minlength="8" maxlength="64" value="<?= $email ?>" required>
                </div>

                <div class="form-group">
                    <label for="rank">Rang</label>
                    <input type="text" id="rank" name="rank" minlength="3" maxlength="12" value="<?= $rank ?>" required>
                </div>

                <div class="form-group">
                    <label for="exp_date">Date d'expiration</label>
                    <input type="text" id="exp_date" name="exp_date" minlength="10" maxlength="10" value="<?= $exp_date ?>" required>
                </div>

                <input type="hidden" name="block" value="<?= $block ?>">

                <div class="form-group submit-group">
                    <input type="submit" name="submit" value="Modifier">
                </div>
            </form>
        </div>
    </div>
</body>

</html>