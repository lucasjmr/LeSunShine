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

// First : get all pseudos of signed up users
$pseudo_array = array();

$userFile = fopen("../data/logins.sunshine", "rb");
if (!$userFile)
{
    exit("Something went wrong while trying to read user file.");
}

while (!feof($userFile))
{
    $line = rtrim(fgets($userFile)); // Get the line
    $line_parts = explode(" ", $line); // Split email pseudo and password hash and returns it in array

    if (isset($line_parts[1])) // Checks if we didn't get the empty line at the end of our login file
    {
        array_push($pseudo_array, $line_parts[1]);
    }
}

if (!fclose($userFile))
{
    exit("Something went wrong while trying to close user file");
}


$i = 0;
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="user-gestion.css">
    <link rel="icon" href="../media/logo.png">
    <title>Gestion utilisateurs</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">LeSunShine</p>
        </div>
        <div id="header-container-right">
            <div class="button" onclick="location.href='panel-admin-html.php'">
                Panel admin
            </div>
        </div>
    </header>
    <div class="lower">
        <?php if (empty($pseudo_array)) : ?>
            <p id="no-result">Aucun utilisateur</p>
            <?php $i = 1 ?>
        <?php else : ?>
            <?php foreach ($pseudo_array as $elmt) : ?>
                <?php
                // Make sure you don't find yourself
                if ($elmt == $_SESSION['pseudo'])
                {
                    continue;
                }

                $i += 1;

                // Get all the infos with user file
                $userFile = fopen("../data/users/$elmt.sunshine", "rb");
                if (!$userFile)
                {
                    exit("Something went wrong while trying to open user file");
                }

                $content = fread($userFile, filesize("../data/users/$elmt.sunshine"));
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
                $blocked = $array[11];

                // Get age 
                $date = new DateTime($birthdate);
                $now = new DateTime();
                $interval = $now->diff($date);
                $age = $interval->y;

                ?>
                <div class="result">
                    <div class="infos">
                        <div class="content">
                            <div class="public">
                                <p>Pseudo : <?= $pseudo ?></p>
                                <br>
                                <p>Genre : <?= $gender ?></p>
                                <br>
                                <p>Age : <?= $age ?></p>
                                <br>
                                <p>Rank : <?= $rank ?></p>
                                <br>
                                <p>Date d'inscription : <?= $signup_date ?></p>
                                <br>
                                <p>Message : <?= $message ?></p>
                            </div>
                            <div class="private">
                                <p>Nom de famille : <?= $last_name ?></p>
                                <br>
                                <p>Prénom : <?= $first_name ?></p>
                                <br>
                                <p>Adresse postale : <?= $home_adress ?></p>
                                <br>
                                <p>Date de fin de l'abonnement : <?= $exp_date ?></p>
                                <br>
                                <p>Email : <?= $email ?></p>
                                <br>
                                <p>Utilisateurs bloqués : <?= $blocked ?></p>
                            </div>
                        </div>
                        <div class="button-container">
                            <div class="modify" onclick="location.href='admin-modify-html.php?user=<?= $pseudo ?>'">
                                Modifier
                            </div>
                            <div class="ban" onclick="location.href='ban.php?user=<?= $pseudo ?>'">
                                Bannir
                            </div>
                        </div>
                    </div>
                    <div class="profil-image">
                        <img src="<?php
                                    $name = $_SESSION['pseudo'];
                                    if (file_exists("../data/images/$pseudo.jpg"))
                                    {
                                        echo "../data/images/$pseudo.jpg";
                                    }
                                    else
                                    {
                                        echo "../media/default.jpg";
                                    }
                                    ?>" alt="image de profil par default manquante.">
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($i == 0) : ?>
            <p id="no-result">Aucun utilisateur</p>
        <?php endif; ?>
    </div>
</body>

</html>