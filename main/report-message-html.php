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

if (isset($_GET['conversation']) && isset($_GET['index']) && !empty($_GET['conversation']) && !empty($_GET['index']))
{
    $conversation = htmlspecialchars($_GET['conversation']);
    $index = intval($_GET['index']);
    $conversationFile = "../data/conversations/" . $conversation . ".sunshine";

    if (file_exists($conversationFile))
    {
        $messages = file($conversationFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $messages = array_reverse($messages); // messages are reversed while displayed in conversation !
        $reportedMessage = preg_replace('/^[\s:]+/', '', $messages[$index]);
    }
    else
    {
        echo error_page("Erreur nom de conversation");
        exit();
    }
}
else
{
    header("Location: inbox-html.php");
    exit();
}
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="report.css">
    <link rel="icon" href="../media/logo.png">
    <title>Signaler message</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">Signaler</p>
        </div>
        <div id="header-container-right">
            <div class="button" onclick="location.href='inbox-html.php'">
                Dashboard
            </div>
        </div>
    </header>
    <div class="box">
        <p class="text">Message signalé :</p>
        <div class="text message-box">
            <p id="message"><?= $reportedMessage ?></p>
        </div>
        <form action="report_message.php" method="POST">
            <input type="hidden" name="conversation" value="<?php echo htmlspecialchars($_GET['conversation']); ?>">
            <input type="hidden" name="index" value="<?php echo intval($_GET['index']); ?>">

            <label for="reason" class="text">Motif du signalement :</label>
            <select name="reason" id="reason" required>
                <option value="spam">Spam</option>
                <option value="insultes">Insultes</option>
                <option value="harcelement">Harcèlement</option>
                <option value="autre">Autre</option>
            </select>
            <input type="submit" name="submit" value="Signaler">
        </form>
    </div>
</body>

</html>