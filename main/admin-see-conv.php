<?php
session_start();

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != "admin")
{
    header("Location: user-gestion-html.php");
    exit();
}

if (!isset($_GET['conv']))
{
    header("Location: user-gestion-html.php");
    exit();
}

$conversationName = $_GET['conv'];
$conversationFile = "../data/conversations/$conversationName.sunshine";

if (!file_exists($conversationFile))
{
    echo error_page("La conversation n'existe pas.");
    exit();
}

// get all messages
$messages = file($conversationFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$messages = array_reverse($messages);

?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="admin-see-conv.css">
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
            <div class="button" onclick="location.href='message-gestion-html.php'">
                Gestion Messagerie
            </div>
        </div>
    </header>
    <div id="lower">
        <div class="box">
            <div class="content">
                <?php
                // display conv
                foreach ($messages as $index => $message)
                {
                    list($pseudo, $messageContent) = explode(': ', $message, 2);
                    $pseudo = htmlspecialchars(trim($pseudo, "<>"));
                    $messageContent = htmlspecialchars($messageContent);
                ?>
                    <div class="message">
                        <span><?= $pseudo ?> : <?= $messageContent ?></span>
                        <button onclick="location.href='admin-delete-message.php?conversation=<?= $conversationName ?>&index=<?= $index ?>'">
                            <img src="../media/delete.png" alt="Supprimer" class="img">
                        </button>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>