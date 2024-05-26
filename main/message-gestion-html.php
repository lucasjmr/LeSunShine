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
    exit();
}
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="message-gestion.css">
    <link rel="icon" href="../media/logo.png">
    <title>Gestion messagerie</title>
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
        <div class="left">
            <h1>SIGNALEMENTS</h1>
            <?php
            $reports = file("../data/reports.sunshine", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            ?>
            <?php for ($i = 0; $i < count($reports); $i = $i + 3) : ?>
                <?php
                list($pseudo, $message) = explode(': ', $reports[$i + 2], 2);
                $userBeingReported = trim($pseudo, "<>");
                ?>
                <div class="report">
                    <div class="info1">
                        <p>Utilisateur signalé : <?= $userBeingReported ?></p>
                        <p>Signalé par : <?= $reports[$i + 1] ?></p>
                        <p>Motif : <?= $reports[$i] ?></p>
                    </div>
                    <div class="info2">
                        <p>Message : <?= htmlspecialchars($message) ?></p>
                    </div>
                    <div class="button-container">
                        <?php if ($userBeingReported != $_SESSION['pseudo']) : ?>
                            <div class="ban" onclick="location.href='ban.php?user=<?= $userBeingReported ?>'">
                                Bannir <?= $userBeingReported ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        <div class="right">
            <h1>MESSAGERIES</h1>
            <?php
            $conversationFiles = glob("../data/conversations/*.sunshine");
            foreach ($conversationFiles as $file)
            {
                $filename = basename($file, ".sunshine"); // file name without extention
                list($user1, $user2) = explode('_', $filename); // get usernames in 2 variables
            ?>
                <div class="report">
                    <div class="conv_name">
                        <p>Conversation entre  <?= $user1 ?> et <?= $user2 ?></p>
                    </div>
                    <div class="button-container" onclick="location.href='admin-see-conv.php?conv=<?= $filename ?>'">
                        <div class="conv">
                            Voir conversation
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</body>

</html>