<?php
session_start();

if (!isset($_SESSION['pseudo']))
{
    header("Location: sign-in-html.php");
    exit();
}

$currentUser = $_SESSION['pseudo'];
$conversationsDir = "../data/conversations/";

if (isset($_GET['conversation']) && isset($_GET['index']))
{
    $conversation = htmlspecialchars($_GET['conversation']);
    $index = intval($_GET['index']);
    $conversationFile = $conversationsDir . $conversation . ".sunshine";

    if (file_exists($conversationFile))
    {
        $messages = file($conversationFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $messages = array_reverse($messages); // messages are reversed while displayed in conversation !
        if ($index >= 0 && $index < count($messages))
        {
            $reportedMessage = $messages[$index];

            $reportsFile = fopen("../data/reports.sunshine", "ab"); // Opens in binary to work on linux, windows and macos 
            if (!$reportsFile)
            {
                exit("Something went wrong while trying to open reports file");
            }
        
            // If everything is okay, write reported message in file
            fprintf($reportsFile, "%s %s\r\n", $currentUser, $reportedMessage);
        
            if (!fclose($reportsFile))
            {
                exit("Something went wrong while trying to close logins file");
            }
        }
    }
}

header("Location: inbox-html.php?conversation=" . urlencode($conversation));
