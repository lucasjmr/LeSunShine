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
    // Get values from form and checks if they are valid
    if (!isset($_POST['pseudo']) || !isset($_POST['password']) || !isset($_POST['email']) || !isset($_POST['gender']) || !isset($_POST['birthday']) || !isset($_POST['last_name']) || !isset($_POST['first_name']) || !isset($_POST['home_adress']) || !isset($_POST['custom_message']) || empty($_POST['pseudo']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['gender']) || empty($_POST['birthday']) || empty($_POST['last_name']) || empty($_POST['first_name']) || empty($_POST['home_adress']) || empty($_POST['custom_message']))
    {
        echo error_page("L'utilisateur doit remplir tous les champs.");
        exit("User must fill all the fields.");
    }

    $form_pseudo = htmlspecialchars($_POST['pseudo']);
    $form_password = htmlspecialchars($_POST['password']);
    $form_email = htmlspecialchars($_POST['email']);
    $form_gender = $_POST['gender'];
    $form_birthday = $_POST['birthday'];
    $form_last_name = htmlspecialchars($_POST['last_name']);
    $form_first_name = htmlspecialchars($_POST['first_name']);
    $form_home_adress = htmlspecialchars($_POST['home_adress']);
    $form_custom_message = htmlspecialchars($_POST['custom_message']);
    $signup_date = date('Y-m-d');
    $rank = "bronze"; // When user creates an account, its rank is bronze

    // Verify if someone already has same pseudo/email
    $loginsFile = fopen("../data/logins.sunshine", "rb"); // Opens in binary to work on linux, windows and macos 
    if (!$loginsFile)
    {
        exit("Something went wrong while trying to open logins file");
    }

    while (!feof($loginsFile))
    {

        fscanf($loginsFile, "%s %s %s", $email, $pseudo, $password);
        if ($pseudo == $form_pseudo || $email == $form_email)
        {
            echo error_page("Le pseudo/email est déjà utilisé.");
            exit("Pseudo/email already taken.");
        }
    }

    if (!fclose($loginsFile))
    {
        exit("Something went wrong while trying to close logins file");
    }


    // Now open logins file in append mode to add the new logins
    $loginsFile = fopen("../data/logins.sunshine", "ab"); // Opens in binary to work on linux, windows and macos 
    if (!$loginsFile)
    {
        exit("Something went wrong while trying to open logins file");
    }

    // If everything is okay, write logins infos in file
    fprintf($loginsFile, "%s %s %s\r\n", $form_email, $form_pseudo, password_hash($form_password, PASSWORD_DEFAULT));

    if (!fclose($loginsFile))
    {
        exit("Something went wrong while trying to close logins file");
    }

    // Create the user info file
    $userFile = fopen("../data/users/$form_pseudo.sunshine", "wb");
    if (!$userFile)
    {
        exit("Something went wrong while trying to create user file");
    }

    fprintf($userFile, "%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n", $form_pseudo, $signup_date, $form_gender, $form_birthday, $form_custom_message, $form_last_name, $form_first_name, $form_home_adress, $form_email, $rank);

    if (!fclose($userFile))
    {
        exit("Something went wrong while trying to close user file");
    }

    // Fills $_SESSION array with data, so we can use them later
    $_SESSION['pseudo'] = $form_pseudo;
    $_SESSION['signup_date'] = $signup_date;
    $_SESSION['gender'] = $form_gender;
    $_SESSION['birthday'] = $form_birthday;
    $_SESSION['custom_message'] = $form_custom_message;
    $_SESSION['last_name'] = $form_last_name;
    $_SESSION['first_name'] = $form_first_name;
    $_SESSION['home_adress'] = $form_home_adress;
    $_SESSION['email'] = $form_email;
    $_SESSION['rank'] = $rank;

    // Once signed up, redirect to dashboard
    header("Location: dashboard-html.php");
}
