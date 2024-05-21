<?php
session_start();

if (!isset($_SESSION['pseudo']))
{
    header("Location: sign-in-html.php");
    exit();
}

if ($_SESSION['rank'] == "bronze")
{
    header("Location: pricing-html.php");
    exit("User isn't subcribed");
}

function error_page($message)
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    return $errorPage;
}

function error_page_user($message) // Special error function : avoid getting message "re-send form"
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    $errorPage = str_replace('history.back()', "location.href='search-html.php'", $errorPage);
    return $errorPage;
}

if (!isset($_GET['send']))
{
    echo error_page("Aucun destinataire n'a été sélectionné");
    exit("The recipient isnt set.");
}

$recipient = htmlspecialchars($_GET['send']);

// Verify user exists
if (!file_exists("../data/users/$recipient.sunshine"))
{
    echo error_page("L'utilisateur $recipient n'existe pas.");
    exit("The recipient does not exist");
}

// Get the rank of recipient
$userFile = fopen("../data/users/$recipient.sunshine", "rb");
if (!$userFile)
{
    exit("Something went wrong while trying to open user file");
}

$content = fread($userFile, filesize("../data/users/$recipient.sunshine"));
$array = explode("\r\n", $content);

$recipient_rank = $array[9];

if (!fclose($userFile))
{
    exit("Something went wrong while trying to close user file");
}

if ($recipient_rank == "bronze")
{
    echo error_page_user("Le destinataire n'est pas abonné.");
    exit("The recipient isnt subscribed.");
}

?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="message.css">
    <link rel="icon" href="../media/logo.png">
    <title>Envoyer Message</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">Envoyer message à <?= $recipient ?></p>
        </div>
        <div id="header-container-right">
            <div class="button" onclick="location.href='search-html.php'">
                Recherche
            </div>
        </div>
    </header>
    <div class="box">
        <form action="send_message.php" method="POST">
            <input type="hidden" name="recipient" value="<?= $recipient ?>"> <!-- Communicates the recipient of message to send_message.php -->
            <textarea name="message" maxlength="128" minlength="2" placeholder="Saisissez votre message ..." spellcheck="false" required></textarea>
            <input type="submit" name="send" Value="Envoyer">
        </form>
    </div>
</body>

</html>