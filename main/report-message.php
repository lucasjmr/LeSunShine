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

$currentUser = $_SESSION['pseudo'];
$conversationsDir = "../data/conversations/";

if (isset($_POST['conversation']) && isset($_POST['index']) && isset($_POST['reason']) && !empty($_POST['conversation']) && !empty($_POST['reason']))
{
    $conversation = $_POST['conversation'];
    $index = intval($_POST['index']);
    $reason = $_POST['reason'];
    $conversationFile = $conversationsDir . $conversation . ".sunshine";

    if (file_exists($conversationFile))
    {
        $messages = file($conversationFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $messages = array_reverse($messages); // messages are reversed while displayed in conversation !
        if ($index >= 0 && ($index < count($messages)))
        {
            $reportedMessage = $messages[$index];

            $reportsFile = fopen("../data/reports.sunshine", "ab"); // Opens in binary to work on linux, windows and macos 
            if (!$reportsFile)
            {
                exit("Something went wrong while trying to open reports file");
            }

            // If everything is okay, write reported message in file
            fprintf($reportsFile, "%s %s %s\r\n", $reason, $currentUser, $reportedMessage);

            if (!fclose($reportsFile))
            {
                exit("Something went wrong while trying to close logins file");
            }

            echo "<script>
                    alert('Le message a été signalé avec succès');
                    window.location.href = 'inbox-html.php?conversation=" . htmlspecialchars($conversation) . "';
                  </script>";
        }
        else
        {
            echo error_page("Erreur d'index");
            exit();
        }
    }
    else
    {
        echo error_page("Erreur nom de conversation");
        exit();
    }
}
else
{
    echo error_page("Un problème est survenu lors du traitement du signalement");
    exit();
}
