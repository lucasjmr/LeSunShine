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

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    if (isset($_POST['submit1'])) // Modify password
    {
        $new_password = htmlspecialchars($_POST['password']);
        if (strlen($new_password) > 16 || strlen($new_password) < 3)
        {
            echo error_page("La longueur du mot de passe n'est pas comprise entre 3 et 16 caractères.");
            exit("Wrong password length");
        }

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
                $pseudo = $line_parts[1]; // pseudo is second element of array

                if ($pseudo == $_SESSION['pseudo'])
                {
                    // Update the password
                    $line_parts[2] = password_hash($new_password, PASSWORD_DEFAULT);
                    $line = implode(" ", $line_parts); // Reconstruct the line with new password hash
                }
                $line .= PHP_EOL; // Add end of line
            }

            $updated_file_contents .= $line; // Append the line to the updated file content
        }

        // Move pointer to the beginning of the file and rewrite the entire content
        rewind($userFile);
        fwrite($userFile, $updated_file_contents);

        if (!fclose($userFile))
        {
            exit("Something went wrong while trying to close user file");
        }

        header("Location: profil-html.php");
    }
    else if (isset($_POST['submit2'])) // Modify custom message
    {
        $new_custom_message = htmlspecialchars($_POST['custom_message']);

        // Verify if given string is valid or not
        if (strlen($new_custom_message) > 64 || strlen($new_custom_message) < 3)
        {
            echo error_page("La longueur du message n'est pas comprise entre 3 et 64 caractères.");
            exit();
        }
        $_SESSION['custom_message'] = $new_custom_message;

        // Rewrite file with new custom message
        $pseudo = $_SESSION['pseudo'];
        $userFile = fopen("../data/users/$pseudo.sunshine", "wb");
        if (!$userFile)
        {
            exit("Something went wrong while trying to create user file");
        }

        fprintf($userFile, "%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n%s\r\n", $_SESSION['pseudo'], $_SESSION['signup_date'], $_SESSION['gender'], $_SESSION['birthday'], $_SESSION['custom_message'], $_SESSION['last_name'], $_SESSION['first_name'], $_SESSION['home_adress'], $_SESSION['email'], $_SESSION['rank'], $_SESSION['exp_date']);
        if (!fclose($userFile))
        {
            exit("Something went wrong while trying to close user file");
        }
        header("Location: profil-html.php");
    }
}
else
{
    header("Location: modify-html.php");
}
