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
    if ($form_gender == "Femme")
    {
        $rank = "platinum";
    }
    $exp_date = new DateTime();
    $exp_date->add(new DateInterval('P100Y'));
    $exp_date = $exp_date->format('Y-m-d');

    // Verify if the given birthday date is correct
    $date = new DateTime($form_birthday);
    $now = new DateTime();
    $interval = $now->diff($date);
    $age = $interval->y;

    $parsed_birthday = date_parse($form_birthday); // get associative array of the date to check for invalid input
    if (!checkdate(!$parsed_birthday || $parsed_birthday['month'], $parsed_birthday['day'], $parsed_birthday['year'])) // check if date is valid
    {
        echo error_page("La date de naissance n'est pas valide.");
        exit("Invalid birth date.");
    }

    if ($age < 18 || $age > 122)
    {
        echo error_page("Vous devez avoir entre 18 et 122 ans");
        exit("User must be between 18 and 122 years old.");
    }

    // Verify if there are spaces in pseudo or email
    if (strpos($_POST['pseudo'], ' ') !== false || strpos($_POST['email'], ' ') !== false)
    {
        echo error_page("Le pseudo ou l'email ne peut pas contenir d'espaces.");
        exit("Pseudo or email cannot contain spaces.");
    }

    // Verify if given variables are not too long/ too short
    if (
        strlen($form_pseudo) > 16 ||
        strlen($form_password) > 16 ||
        strlen($form_email) > 64 ||
        strlen($form_last_name) > 32 ||
        strlen($form_first_name) > 32 ||
        strlen($form_home_adress) > 64 ||
        strlen($form_custom_message) > 64
    )
    {
        echo error_page("Un ou plusieurs champs dépassent la limite maximum de caractères");
        exit("One or more fields exceed the maximum length.");
    }
    if (
        strlen($form_pseudo) < 3 ||
        strlen($form_password) < 3 ||
        strlen($form_email) < 8 ||
        strlen($form_last_name) < 2 ||
        strlen($form_first_name) < 2 ||
        strlen($form_home_adress) < 12 ||
        strlen($form_custom_message) < 3
    )
    {
        echo error_page("Un ou plusieurs champs ne font pas la limite minimum de caractères");
        exit("One or more fields are too short.");
    }

    // Verify if someone already has same pseudo/email
    $loginsFile = fopen("../data/logins.sunshine", "rb"); // Opens in binary to work on linux, windows and macos 
    if (!$loginsFile)
    {
        exit("Something went wrong while trying to open logins file.");
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

    fprintf($userFile, "%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n", $form_pseudo, $signup_date, $form_gender, $form_birthday, $form_custom_message, $form_last_name, $form_first_name, $form_home_adress, $form_email, $rank, $exp_date);

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
    $_SESSION['exp_date'] = $exp_date;

    // Once signed up, redirect to dashboard
    header("Location: dashboard-html.php");
}
else
{
    header("Location: sign-up-html.php");
}
