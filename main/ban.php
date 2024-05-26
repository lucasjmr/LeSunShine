<?php
session_start();

function error_page($message)
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    $errorPage = str_replace('history.back()', "location.href='dashboard-html.php'", $errorPage);
    return $errorPage;
}

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != "admin")
{
    echo error_page("Vous n'êtes pas administrateur.");
    exit();
}

if (isset($_GET['user']))
{
    $userToBan = $_GET['user'];

    if (!file_exists("../data/users/$userToBan.sunshine"))
    {
        echo error_page("L'utilisateur n'existe pas.");
        exit();
    }

    // BAN : delete convs, delete images, delete in logins file, delete user file, delete visitors file, delete his name of all visitors, delete name in blocked users, add email to banned list, remove reported messages

    // Delete image
    if (file_exists("../data/images/$userToBan.jpg"))
    {
        unlink("../data/images/$userToBan.jpg");
    }

    // Delete convs
    $conversationFiles = glob("../data/conversations/*.sunshine"); // returns array of all files in conversations folder

    foreach ($conversationFiles as $file) // Go trough every files in conversations folder
    {
        $filename = basename($file, ".sunshine"); // filename without extention -> could also use explode, but easier like this
        list($user1, $user2) = explode('_', $filename); // split names in filename and insert it in 2 variables
        if ($user1 == $userToBan || $user2 == $userToBan) // If conversation contains current pseudo of userToBan
        {
            unlink($file);
        }
    }

    // Delete visitors file and name in others visitors files
    if (file_exists("../data/visitors/$userToBan.sunshine"))
    {
        unlink("../data/visitors/$userToBan.sunshine");
    }

    $visitorsFiles = glob("../data/visitors/*.sunshine"); // returns array of all files in visitors folder

    foreach ($visitorsFiles as $file) // Go trough every files in visitors folder
    {
        $visitors = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        for ($i = 0; $i < count($visitors); $i++)
        {
            if ($visitors[$i] == $userToBan) // if in the line userToBan is written
            {
                unset($visitors[$i]);
            }
        }
        $newContent = implode(PHP_EOL, $visitors);
        if (count($visitors) == 1)
        {
            $newContent .= PHP_EOL;
        }
        file_put_contents($file,  $newContent);
    }

    // Delete user in logins file
    $userFile = fopen("../data/logins.sunshine", "rb+");
    if (!$userFile)
    {
        exit("Something went wrong while trying to open/read/write user file");
    }

    $updated_file_contents = '';
    while (!feof($userFile))
    {
        $line = rtrim(fgets($userFile)); // Get the line
        $line_parts = explode(" ", $line); // Split email pseudo and password hash and returns it in array
        if (isset($line_parts[1])) // Checks if we didn't get the empty line at the end of our login file
        {
            if ($line_parts[1] != $userToBan)
            {
                $line .= PHP_EOL;
                $updated_file_contents .= $line; // Append the line to the updated file content
            }
            else // Save email of userToBan to add it later in ban file
            {
                $emailToBan = $line_parts[0];
            }
        }
    }
    // erase file content before writing
    file_put_contents("../data/logins.sunshine", "");
    // Move pointer to the beginning of the file and rewrite the entire content
    rewind($userFile);
    fwrite($userFile, $updated_file_contents);
    if (!fclose($userFile))
    {
        exit("Something went wrong while trying to close user file");
    }


    // Add userToBan email to the banned files
    $banFile = fopen("../data/bans.sunshine", "ab+");
    if (!$banFile)
    {
        exit("Something went wrong while trying to open bans file");
    }

    fprintf($banFile, "%s\r\n", $emailToBan);

    if (!fclose($banFile))
    {
        exit("Something went wrong while trying to close bans file");
    }

    // delete user file
    if (file_exists("../data/users/$userToBan.sunshine"))
    {
        unlink("../data/users/$userToBan.sunshine");
    }

    // delete username of blocked user in others userfile
    $userFiles = glob("../data/users/*.sunshine"); // returns array of all files in users folder

    foreach ($userFiles as $file) // Go trough every files in users folder
    {
        $infos = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (isset($infos[11]))
        {
            if (str_contains($infos[11], $userToBan))
            {
                $infos[11] = str_replace($userToBan . " ", "", $infos[11]);
            }
        }
        else
        {
            $infos[10] .= PHP_EOL;
        }
        file_put_contents($file, implode(PHP_EOL, $infos));
    }

    // deletes reports with the usertoban name
    $reports = file("../data/reports.sunshine", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
    $newReports = array();
    for ($i = 0; $i < count($reports); $i = $i + 3)
    {
        list($pseudo, $message) = explode(': ', $reports[$i + 2], 2);
        $userBeingReported = trim($pseudo, "<>");

        if ($userBeingReported != $userToBan)
        {
            // Ajouter le motif
            $newReports[] = $reports[$i];
            // Ajouter l'utilisateur signalé
            $newReports[] = $reports[$i + 1];
            // Ajouter le message
            $newReports[] = $reports[$i + 2];
        }
    }
    if (!empty($newReports))
    {
        file_put_contents("../data/reports.sunshine", implode(PHP_EOL, $newReports) . PHP_EOL);
    }
    
    header("Location: ban-animation.php?user=$userToBan");
}
else
{
    header("Location: panel-admin-html.php");
}
