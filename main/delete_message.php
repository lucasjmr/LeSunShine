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
    $index = intval($_GET['index']); // get result as int and not string ...
    $conversationFile = $conversationsDir . $conversation . ".sunshine";

    if (file_exists($conversationFile))
    {
        $messages = file($conversationFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $messages = array_reverse($messages); // messages are printed reversed in html page !!!
        if ($index >= 0 && $index < count($messages)) // avoid trying to delete wrong message
        {
            unset($messages[$index]);
            $messages = array_reverse($messages); // invert again messages
            file_put_contents($conversationFile, implode(PHP_EOL, $messages) . PHP_EOL);
        }
    }
}

header("Location: inbox-html.php?conversation=" . $conversation);
