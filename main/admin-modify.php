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


if (isset($_POST['submit']))
{
    $pseudo = trim($_POST['pseudo']);
    $signup_date = trim($_POST['signup_date']);
    $gender = trim($_POST['gender']);
    $birthdate = trim($_POST['birthdate']);
    $message = trim($_POST['message']);
    $last_name = trim($_POST['last_name']);
    $first_name = trim($_POST['first_name']);
    $home_adress = trim($_POST['home_adress']);
    $email = trim($_POST['email']);
    $rank = trim($_POST['rank']);
    $exp_date = trim($_POST['exp_date']);
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
