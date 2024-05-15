<?php

session_start();

function error_page($message)
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    return $errorPage;
}

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    // Verify if user entered all fields
    if (!isset($_POST['pseudo']) || !isset($_POST['password']) || empty($_POST['pseudo']) || empty($_POST['password']))
    {
        echo error_page("L'utilisateur doit remplir tous les champs.");
        exit("User must fill all the fields.");
    }

    // Get the logins infos
    $form_pseudo = htmlspecialchars($_POST['pseudo']);
    $form_password = htmlspecialchars($_POST['password']);

    // Open file and get all logins -> fill associative array with data
    $loginsFile = fopen("../data/logins.sunshine", "rb"); // Opens in binary to work on linux, windows and macos 
    if (!$loginsFile)
    {
        exit("Something went wrong while trying to open logins file");
    }

    $logins_array = array();

    while (!feof($loginsFile))
    {
        fscanf($loginsFile, "%s %s %s", $email, $pseudo, $password);
        $logins_array[$pseudo] = $password;
    }

    if (!fclose($loginsFile))
    {
        exit("Something went wrong while trying to close logins file");
    }

    // Verify if pseudo is in array 
    $found = 0;
    if (filesize("../data/logins.sunshine") != 0)
    {
        foreach ($logins_array as $x => $y)
        {
            if ($x == $form_pseudo)
            {
                $found = 1;
            }
        }
    }
    // Verify if password is correct
    if ($found && password_verify($form_password, $logins_array[$form_pseudo]))
    {
        // Read all data from user 
        $userFile = fopen("../data/users/$form_pseudo.sunshine", "rb");
        if (!$userFile)
        {
            exit("Something went wrong while trying to open user file");
        }

        $content = fread($userFile, filesize("../data/users/$form_pseudo.sunshine"));
        $array = explode("\r\n", $content);

        // Fill session infos 
        $_SESSION['pseudo'] = $array[0];
        $_SESSION['signup_date'] = $array[1];
        $_SESSION['gender'] = $array[2];
        $_SESSION['birthday'] = $array[3];
        $_SESSION['custom_message'] = $array[4];
        $_SESSION['last_name'] = $array[5];
        $_SESSION['first_name'] = $array[6];
        $_SESSION['home_adress'] = $array[7];
        $_SESSION['email'] = $array[8];
        $_SESSION['rank'] = $array[9];
        $_SESSION['exp_date'] = $array[10];

        if (!fclose($userFile))
        {
            exit("Something went wrong while trying to close user file");
        }

        // Verify plan
        if ((strtotime(date("Y-m-d")) > strtotime($_SESSION['exp_date'])) && $_SESSION['rank'] != "platinum")
        {
            $_SESSION['rank'] = "bronze";
            $exp_date = new DateTime();
            $exp_date->add(new DateInterval('P100Y'));
            $exp_date = $exp_date->format('Y-m-d');
            $_SESSION['exp_date'] = $exp_date;

            // Update user info file
            $userFile = fopen("../data/users/$form_pseudo.sunshine", "wb");
            if (!$userFile)
            {
                exit("Something went wrong while trying to create user file");
            }

            fprintf($userFile, "%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n", $_SESSION['pseudo'], $_SESSION['signup_date'], $_SESSION['gender'], $_SESSION['birthday'], $_SESSION['custom_message'], $_SESSION['last_name'], $_SESSION['first_name'], $_SESSION['home_adress'], $_SESSION['email'], $_SESSION['rank'], $_SESSION['exp_date']);
            if (!fclose($userFile))
            {
                exit("Something went wrong while trying to close user file");
            }
        }
        header("Location: dashboard-html.php");
    }
    else
    {
        echo error_page("L'Authentification a échoué.");
    }
}
else
{
    header("Location: sign-in.html");
}
