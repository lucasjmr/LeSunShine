<?php
session_start();

if (!isset($_SESSION['pseudo'])) // if user not connected, bring back to connection
{
    header("Location: sign-in-html.php");
    exit();
}

function error_page_user($message) // Special error function : avoid getting message "re-send form"
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    $errorPage = str_replace('history.back()', "location.href='search-html.php'", $errorPage);
    return $errorPage;
}

if (isset($_GET['block']) || !empty($_GET['block']))
{
    $blocked = $_GET['block'];
    $currentUser = $_SESSION['pseudo'];

    // FIRST PART : ADD CURRENT USER TO BLOCKLIST OF BEING BLOCKED USER
    if (!file_exists("../data/users/$blocked.sunshine"))
    {
        echo error_page_user("L'utilisateur n'existe pas");
        exit();
    }

    $blockedUserFile = fopen("../data/users/$blocked.sunshine", "rb");
    if (!$blockedUserFile)
    {
        exit("Something went wrong while trying to open user file");
    }

    $content = fread($blockedUserFile, filesize("../data/users/$blocked.sunshine"));
    $array = explode("\r\n", $content);

    $blockedUsers = $array[11];

    if (!fclose($blockedUserFile))
    {
        exit("Something went wrong while trying to close user file");
    }

    if (!str_contains($blockedUsers, $currentUser)) // If user isn't alrdy blocked
    {
        $blockedUserFile = fopen("../data/users/$blocked.sunshine", "ab");
        if (!$blockedUserFile)
        {
            exit("Something went wrong while trying to open user file");
        }

        fprintf($blockedUserFile, "%s ", $currentUser);

        if (!fclose($blockedUserFile))
        {
            exit("Something went wrong while trying to close user file");
        }
    }
    else
    {
        echo error_page_user("L'utilisateur est déjà bloqué.");
        exit();
    }

    // SECOND PART : ADD BEING BLOCKED USERNAME TO CURRENT USER FILE
    if (!file_exists("../data/users/$currentUser.sunshine"))
    {
        echo error_page_user("L'utilisateur n'existe pas");
        exit();
    }

    $currentUserFile = fopen("../data/users/$currentUser.sunshine", "rb");
    if (!$currentUserFile)
    {
        exit("Something went wrong while trying to open user file");
    }

    $content = fread($currentUserFile, filesize("../data/users/$currentUser.sunshine"));
    $array = explode("\r\n", $content);

    $blockedUsers = $array[11];

    if (!fclose($currentUserFile))
    {
        exit("Something went wrong while trying to close user file");
    }

    if (!str_contains($blockedUsers, $blocked)) // If user isn't alrdy blocked
    {
        $currentUserFile = fopen("../data/users/$currentUser.sunshine", "ab");
        if (!$currentUserFile)
        {
            exit("Something went wrong while trying to open user file");
        }

        fprintf($currentUserFile, "%s ", $blocked);

        if (!fclose($currentUserFile))
        {
            exit("Something went wrong while trying to close user file");
        }
    }
    else
    {
        echo error_page_user("L'utilisateur est déjà bloqué.");
        exit();
    }

    if (file_exists("../data/conversations/" . min($blocked, $currentUser) . '_' . max($blocked, $currentUser) . ".sunshine"))
    {
        unlink("../data/conversations/" . min($blocked, $currentUser) . '_' . max($blocked, $currentUser) . ".sunshine"); // Deletes conv file
    }

    session_destroy();

    $htmlTemplate = file_get_contents('sign-in-html.php');
    $reconnect = str_replace('Connexion', "Succès. Veuillez vous reconnecter", $htmlTemplate);
    echo $reconnect;
}
else
{
    echo error_page_user("Erreur argument url (GET)");
    exit();
}
