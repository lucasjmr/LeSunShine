<?php
session_start();

if (!isset($_SESSION['pseudo']))
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

function error_page_user($message) // Special error function : avoid getting message "re-send form"
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    $errorPage = str_replace('history.back()', "location.href='dashboard-html.php'", $errorPage);
    return $errorPage;
}

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $message = trim($_POST['message']); // htmlspecialchars breaks some caracters, so we use it before displaying to the webpage, instead of here
    $recipient = trim(htmlspecialchars($_POST['recipient']));

    if (!isset($message) || empty($message))
    {
        echo error_page("Vous devez saisir un message.");
        exit();
    }
    if (!isset($recipient) || empty($recipient))
    {
        echo error_page("Aucun destinataire.");
        exit();
    }
    if (strlen($_POST['message']) > 128 || strlen($_POST['message']) < 2)
    {
        echo error_page("Vous devez respecter la longueur des champs.");
        exit();
    }
    if ($_POST['recipient'] == $_SESSION['pseudo'])
    {
        echo error_page("Vous ne pouvez pas envoyer un message à vous-même.");
        exit();
    }

    if (strpos($message, "\n") !== false || strpos($message, "\r") !== false)
    {
        echo error_page("Les retours à la ligne ne sont pas autorisés dans le message.");
        exit();
    }
    if (!file_exists("../data/users/$recipient.sunshine"))
    {
        echo error_page("L'utilisateur $recipient n'existe pas.");
        exit();
    }

    $sender = $_SESSION['pseudo'];

    // Checks if recipient is subscribed (2nd check here because if user changes html code in inbox-html.php, he can send messages to bronze ranks)
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
        exit();
    }

    // Create filename for the conversation -> avoid getting 2 different conversation files
    $conversationFile = "../data/conversations/" . min($sender, $recipient) . "_" . max($sender, $recipient) . ".sunshine";

    // Prepare the message format
    $newMessage = "<$sender>: $message" . PHP_EOL;

    // Append the message to the conversation file
    file_put_contents($conversationFile, $newMessage, FILE_APPEND);
    header("Location: inbox-html.php?conversation=" . min($sender, $recipient) . "_" . max($sender, $recipient));
}
else
{
    header("Location: message.php");
}
