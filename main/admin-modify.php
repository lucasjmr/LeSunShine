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
}


if (isset($_POST['submit']))
{
    $pseudo = $_POST['pseudo'];
    $signup_date = $_POST['signup_date'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $message = $_POST['message'];
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $home_adress = $_POST['home_adress'];
    $email = $_POST['email'];
    $rank = $_POST['rank'];
    $exp_date = $_POST['exp_date'];
    $block = $_POST['block'];

    $userFile = fopen("../data/users/$pseudo.sunshine", "wb");
    if (!$userFile)
    {
        exit("Something went wrong while trying to create user file");
    }

    fprintf($userFile, "%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s", $pseudo, $signup_date, $gender, $birthdate, $message, $last_name, $first_name, $home_adress, $email, $rank, $exp_date, $block);

    if (!fclose($userFile))
    {
        exit("Something went wrong while trying to close user file");
    }

    header("Location: admin-modify-html.php?user=$pseudo");
}
else {
    header("Location: user-gestion-html.php");
}
