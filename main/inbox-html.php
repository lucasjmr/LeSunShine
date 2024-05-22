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
        $recipient = ($user1 == $currentUser) ? $user2 : $user1; // save recipient name for later use
        $conversations[$filename] = $recipient; // saves recipient name and conv name in associative array
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
}

$visitorsArray = array();
if (!file_exists("../data/visitors/$currentUser.sunshine"))
{
    echo error_page("The visitors file of your profile does not exist.");
    exit("Visitors file missing");
}
else
{
    $visitorsArray = file("../data/visitors/$currentUser.sunshine", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

foreach ($visitorsArray as $visitor)
{
    $filename = htmlspecialchars(min($visitor, $currentUser) . '_' . max($visitor, $currentUser));
    $associativeVisitorsArray[$filename] = $visitor;
}

function isVisitorInConversations($visitor, $conversations, $currentUser)
{
    foreach ($conversations as $filename => $recipient)
    {
        list($user1, $user2) = explode('_', $filename);
        if (($user1 == $visitor || $user2 == $visitor) && ($user1 == $currentUser || $user2 == $currentUser))
        {
            return true;
        }
    }
    return false;
}

function startsWith($stringToSearchIn, $stringToCheck)
{
    return strpos($stringToSearchIn, $stringToCheck) === 0; // strpos returns 0 if the postion of $stringToCheck is the first string in $stringTOSearchIn
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
        <div class="leftpanel">
            <div class="recipients">
                <p>Conversations</p>
                <?php foreach ($conversations as $filename => $recipient) : ?>
                    <div class="recipient" onclick="location.href='?conversation=<?= $filename ?>'"><?= htmlspecialchars($recipient) ?></div>
                <?php endforeach; ?>
            </div>
            <div class="seenby">
                <p>Visiteurs de votre profil</p>
                <?php if (!empty($associativeVisitorsArray)) : ?>
                    <?php foreach ($associativeVisitorsArray as $filename => $visitor) : ?>
                        <?php if (!isVisitorInConversations($visitor, $conversations, $currentUser)) : ?>
                            <div class="recipient" onclick="location.href='?conversation=<?= $filename ?>'"><?= htmlspecialchars($visitor) ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="conversations">
            <div class="conv_name">
                <?php
                if (empty($selectedConversation))
                {
                    echo "Aucune conversation sélectionnée";
                }
                else if (!file_exists("../data/conversations/" . $selectedConversation . ".sunshine") && isset($associativeVisitorsArray[$selectedConversation]))
                {
                    echo $associativeVisitorsArray[$selectedConversation];
                }
                else if (isset($conversations[$selectedConversation]))
                {
                    echo htmlspecialchars($conversations[$selectedConversation]);
                }
                else
                {
                    echo "User does not exist";
                }
                ?>
            </div>
            <div class="content">
                <?php if (empty($messages) && !empty($selectedConversation)) : ?>
                    <p class="left">Aucun message, envoyez-en un pour commencer la discussion</p>
                <?php else : ?>
                    <?php foreach ($messages as $index => $message) : ?>
                        <?php $messageParts = explode(':', $message, 2); ?>
                        <div class="<?= ($messageParts[0] == '<' . $currentUser . '>') ? 'right' : 'left' ?>">
                            <span><?= htmlspecialchars($messageParts[1]) ?></span>
                            <?php if ($messageParts[0] == '<' . $currentUser . '>') : ?>
                                <div class="message-options">
                                    <button onclick="location.href='delete_message.php?conversation=<?= $selectedConversation ?>&index=<?= $index ?>'">
                                        <img src="../media/delete.png" alt="Supprimer" class="img_left">
                                    </button>
                                </div>
                            <?php else : ?>
                                <div class="message-options">
                                    <button onclick="location.href='report_message.php?conversation=<?= $selectedConversation ?>&index=<?= $index ?>'">
                                        <img src="../media/report.png" alt="Signaler" class="img_right">
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="send_message">
                <form action="send_message.php" method="POST">
                    <input type="hidden" name="recipient" value="
                    <?php
                    if (empty($selectedConversation))
                    {
                        echo "";
                    }
                    else if (!file_exists("../data/conversations/" . $selectedConversation . ".sunshine") && isset($associativeVisitorsArray[$selectedConversation]))
                    {
                        echo $associativeVisitorsArray[$selectedConversation];
                    }
                    else if (isset($conversations[$selectedConversation]))
                    {
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