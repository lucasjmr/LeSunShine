<?php

session_start();
if (!isset($_SESSION['pseudo'])) // if user not connected, bring back to connection
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

if (isset($_POST['submit']))
{
    $file = $_FILES['file'];

    $filename = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode(".", $filename);
    $fileActualExt = strtolower(end($fileExt)); // Keep latest string after a "." = extention

    // Verify if file given is a jpg
    if ($fileActualExt == "jpg")
    {
        if ($fileError === 0) // Checks if there is no error while uploading file
        {
            if ($fileSize < 20000000) // IF file is >20mb, do not process.
            {
                $fileDestination = "../data/images/" . $_SESSION['pseudo'].".jpg";
                move_uploaded_file($fileTmpName, $fileDestination);
                header("Location: profil-html.php");
            }
            else
            {
                echo error_page("L'image doit faire moins de 20mb");
            }
        }
        else
        {
            echo error_page("Une erreur est survenue pendant l'upload de votre image.");
        }
    }
    else
    {
        echo error_page("L'image doit être au format .jpg");
    }
}
else
{
    header("Location: upload-html.php");
}
