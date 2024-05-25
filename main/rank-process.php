<?php

session_start();
if (!isset($_SESSION['pseudo'])) // if user not connected, bring back to connection
{
    header("Location: sign-in-html.php");
    exit();
}

function error_page($message)
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    return $errorPage;
}

function update_user_info()
{
    $pseudo = $_SESSION['pseudo'];
    $userFile = fopen("../data/users/$pseudo.sunshine", "wb");
    if (!$userFile)
    {
        exit("Something went wrong while trying to create user file");
    }

    fprintf($userFile, "%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s", $_SESSION['pseudo'], $_SESSION['signup_date'], $_SESSION['gender'], $_SESSION['birthday'], $_SESSION['custom_message'], $_SESSION['last_name'], $_SESSION['first_name'], $_SESSION['home_adress'], $_SESSION['email'], $_SESSION['rank'], $_SESSION['exp_date'], $_SESSION['block']);
    if (!fclose($userFile))
    {
        exit("Something went wrong while trying to close user file");
    }
}

if ($_SESSION['rank'] == "admin")
{
    echo error_page("Vous êtes administrateur.");
    exit();
}

if ($_SESSION['gender'] != "Femme")
{
    $rank = htmlspecialchars($_GET['rank']);
    if ($rank == "silver" || $rank == "gold" || $rank == "platinum")
    {
        // Apply rank upgrade only if rank is lower
        switch ($rank)
        {
            case "silver":
                if ($_SESSION['rank'] == "bronze")
                {
                    $_SESSION['rank'] = "silver";
                    $exp_date = new DateTime();
                    $exp_date->add(new DateInterval('P1M'));
                    $exp_date = $exp_date->format('Y-m-d');
                    $_SESSION['exp_date'] = $exp_date;

                    // Update user info file
                    update_user_info();
                }
                else if ($_SESSION['rank'] == "silver")
                {
                    $_SESSION['rank'] = "silver";
                    $pseudo = $_SESSION['pseudo'];

                    // Get old expiration date
                    $userFile = fopen("../data/users/$pseudo.sunshine", "rb");
                    if (!$userFile)
                    {
                        exit("Something went wrong while trying to open user file");
                    }

                    $content = fread($userFile, filesize("../data/users/$pseudo.sunshine"));
                    $array = explode("\r\n", $content);

                    if (!fclose($userFile))
                    {
                        exit("Something went wrong while trying to close user file");
                    }

                    $lastExpDate = $array[10]; // old exp date is in $array[10];
                    $exp_date = DateTime::createFromFormat('Y-m-d', $lastExpDate);
                    $exp_date->add(new DateInterval('P1M'));
                    $exp_date = $exp_date->format('Y-m-d');
                    $_SESSION['exp_date'] = $exp_date;

                    // Update user info file
                    update_user_info();
                }
                break;
            case "gold":
                if ($_SESSION['rank'] == "bronze")
                {
                    $_SESSION['rank'] = "gold";
                    $exp_date = new DateTime();
                    $exp_date->add(new DateInterval('P1Y'));
                    $exp_date = $exp_date->format('Y-m-d');
                    $_SESSION['exp_date'] = $exp_date;

                    // Update user info file
                    update_user_info();
                }
                else if ($_SESSION['rank'] == "silver" || $_SESSION['rank'] == "gold")
                {
                    $_SESSION['rank'] = "gold";
                    $pseudo = $_SESSION['pseudo'];

                    // Get old expiration date
                    $userFile = fopen("../data/users/$pseudo.sunshine", "rb");
                    if (!$userFile)
                    {
                        exit("Something went wrong while trying to open user file");
                    }

                    $content = fread($userFile, filesize("../data/users/$pseudo.sunshine"));
                    $array = explode("\r\n", $content);

                    if (!fclose($userFile))
                    {
                        exit("Something went wrong while trying to close user file");
                    }

                    $lastExpDate = $array[10]; // old exp date is in $array[10];
                    $exp_date = DateTime::createFromFormat('Y-m-d', $lastExpDate);
                    $exp_date->add(new DateInterval('P1Y'));
                    $exp_date = $exp_date->format('Y-m-d');
                    $_SESSION['exp_date'] = $exp_date;

                    // Update user info file
                    update_user_info();
                }
                break;
            case "platinum":
                if ($_SESSION['rank'] != "platinum")
                {
                    $_SESSION['rank'] = "platinum";
                    $exp_date = new DateTime();
                    $exp_date->add(new DateInterval('P100Y'));
                    $exp_date = $exp_date->format('Y-m-d');
                    $_SESSION['exp_date'] = $exp_date;

                    // Update user info file
                    update_user_info();
                }
                break;
        }
    }
}

header("Location: pricing-html.php");
