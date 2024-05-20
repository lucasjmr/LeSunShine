<?php

session_start();

if (!isset($_SESSION['pseudo']))
{
    header("Location: sign-in-html.php");
    exit();
}

if ($_SESSION['rank'] == "bronze") // If user isn't subscribed, redirects him to ranks page
{
    header("Location: pricing-html.php");
    exit("User must have a rank to view his inbox");
}

$currentUser = $_SESSION['pseudo'];
$conversationsDir = "../data/conversations/";

$conversationFiles = glob($conversationsDir . "*.sunshine"); // returns array of all files in conversations folder
$conversations = []; // Future array where we put matching conversations

foreach ($conversationFiles as $file) // Go trough every files in conversations folder
{
    $filename = basename($file, ".sunshine"); // filename without extention -> could also use explode, but easier like this
    list($user1, $user2) = explode('_', $filename); // split names in filename and insert it in 2 variables
    if ($user1 == $currentUser || $user2 == $currentUser) // If conversation contains current pseudo of user
    {
        $recipient = ($user1 == $currentUser) ? $user2 : $user1; // Determine le destinataire de la conversation
        $conversations[$filename] = $recipient; // Utilise le nom de fichier comme clé et le destinataire comme valeur
    }
}


$selectedConversation = isset($_GET['conversation']) ? htmlspecialchars($_GET['conversation']) : '';
$messages = []; // array of future messages

if ($selectedConversation) // Checks if conversation is selected or not
{
    $conversationFile = $conversationsDir . $selectedConversation . ".sunshine";
    if (file_exists($conversationFile))
    {
        list($user1, $user2) = explode('_', $selectedConversation);
        if ($user1 != $_SESSION['pseudo'] && $user2 != $_SESSION['pseudo']) // User tries to see others conversation x)
        {
            $messages[0] = '<server>:Cette conversation n\'existe pas.'; // dont tell random user if 2 users have an existing conversation !!!
        }
        else
        {
            $messages = file($conversationFile); // Reads conv file, returns array in $messages
            $messages = array_reverse($messages); // Invert messages order to get latest message first in conversation
        }
    }
    else
    {
        $messages[0] = '<server>:Cette conversation n\'existe pas.';
    }
}
else
{
    $messages[0] = '<server>:Aucune conversation n\'est sélectionnée.';
}
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="inbox.css">
    <link rel="icon" href="../media/logo.png">
    <title>Messagerie</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">Messagerie</p>
        </div>
        <div id="header-container-right">
            <div class="button" onclick="location.href='dashboard-html.php'">
                Dashboard
            </div>
        </div>
    </header>
    <div id="lower">
        <div class="recipients">
            <p>Destinataires</p>
            <?php foreach ($conversations as $filename => $recipient) : ?>
                <div class="recipient" onclick="location.href='?conversation=<?= $filename ?>'"><?= htmlspecialchars($recipient) ?></div>
            <?php endforeach; ?>
        </div>
        <div class="conversations">
            <div class="content">
                <?php if (empty($messages)) : ?>
                    <p>No messages found.</p>
                <?php else : ?>
                    <?php foreach ($messages as $message) : ?>
                        <?php $messageParts = explode(':', $message); ?>
                        <p class="<?= ($messageParts[0] == '<' . $currentUser . '>') ? 'right' : 'left' ?>"><?= htmlspecialchars($messageParts[1]) ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="send_message">
                <form action="send_message.php" method="POST">
                    <input type="hidden" name="recipient" value="
                    <?php 
                    if (!empty($selectedConversation)) {
                        echo htmlspecialchars($conversations[$selectedConversation]);
                    }
                    else 
                    {
                        echo "";
                    }
                    ?>
                    ">
                    <input type="text" name="message" placeholder="Saisissez votre message ici ..." required>
                    <input type="submit" value="Envoyer">
                </form>
            </div>
        </div>
    </div>
</body>

</html>