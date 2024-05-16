<?php
session_start();

if (!isset($_SESSION['pseudo'])) // if user not connected, bring back to connection
{
    header("Location: sign-in-html.php");
}

function error_page($message)
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    return $errorPage;
}

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    // The goal : fill 6 arrays : pseudos, pseudo user is searching for, age, gender, rank, photo
    $pseudo_array = array();
    $user_selected_pseudo_array = array();
    $age_array = array();
    $gender_array = array();
    $rank_array = array();
    $photo_array = array();

    $is_age_set = 0;
    $is_pseudo_set = 0;
    $is_gender_set = 0;
    $is_rank_set = 0;
    $is_photo_set = 0;

    // First : get all pseudos of signed up users
    $userFile = fopen("../data/logins.sunshine", "rb");
    if (!$userFile)
    {
        exit("Something went wrong while trying to open/read user file.");
    }

    while (!feof($userFile))
    {
        $line = rtrim(fgets($userFile)); // Get the line
        $line_parts = explode(" ", $line); // Split email pseudo and password hash and returns it in array

        if (isset($line_parts[1])) // Checks if we didn't get the empty line at the end of our login file
        {
            array_push($pseudo_array, $line_parts[1]);
        }
    }

    if (!fclose($userFile))
    {
        exit("Something went wrong while trying to close user file");
    }

    // Verify all given informations
    if ($is_age_set && (!is_numeric($_POST['age_min']) || !is_numeric($_POST['age_max'])))
    {
        echo error_page("Des nombres doivent être entrés dans les champs d'âge ...");
        exit("Not numbers given in age fields");
    }
    if (isset($_POST['age_min']) && !empty($_POST['age_min']) && isset($_POST['age_max']) && !empty($_POST['age_max']))
    {
        $is_age_set = 1;
        if (($_POST['age_min'] > $_POST['age_max']) || $_POST['age_min'] < 18 || $_POST['age_max'] > 122)
        {
            echo error_page("Veuillez entrer des ages corrects. (entre 18 et 122 ans).");
            exit("Wrong age numbers given");
        }
    }
    else if ((isset($_POST['age_min']) && !empty($_POST['age_min'])) != (isset($_POST['age_max']) && !empty($_POST['age_max'])))
    {
        echo error_page("Veuillez remplir soit tous les champs d'age soit aucun. (entre 18 et 122 ans).");
        exit("Wrong age numbers given");
    }

    if ($is_pseudo_set && (strlen($_POST['pseudo']) > 16 || strlen($_POST['pseudo']) < 3))
    {
        echo error_page("Veuillez remplir un pseudo de la bonne taille. (entre 3 et 16 caractères).");
        exit("Wrong pseudo length");
    }
    if (isset($_POST['pseudo']) && !empty($_POST['pseudo']))
    {
        $is_pseudo_set = 1;
    }
    if (isset($_POST['gender']) && !empty($_POST['gender']))
    {
        $is_gender_set = 1;
    }
    if (isset($_POST['rank']) && !empty($_POST['rank']))
    {
        $is_rank_set = 1;
    }
    if (isset($_POST['photo']) && !empty($_POST['photo']))
    {
        $is_photo_set = 1;
    }

    // Open every user file and fill the arrays when infos are needed
    for ($i = 0; $i < count($pseudo_array); $i++)
    {
        // Get current user infos
        $current_pseudo = $pseudo_array[$i];
        $userFile = fopen("../data/users/$current_pseudo.sunshine", "rb");
        if (!$userFile)
        {
            exit("Something went wrong while trying to open user file");
        }

        $content = fread($userFile, filesize("../data/users/$current_pseudo.sunshine"));
        $content = explode("\r\n", $content);

        // Calculate age of current pseudo
        $birthdate = new DateTime($content[3]); // $array[3] is the date of birth
        $now = new DateTime();
        $interval = $now->diff($birthdate);
        $current_age = $interval->y;

        if (!fclose($userFile))
        {
            exit("Something went wrong while trying to close user file");
        }

        if ($is_age_set) // If age is set in form
        {
            if ($current_age >= $_POST['age_min'] && $current_age <= $_POST['age_max']) // If age of current pseudo is what the user is searching for
            {
                array_push($age_array, $current_pseudo); // Add the current pseudo in array
            }
        }
        else // else all users meet critera
        {
            $age_array = $pseudo_array;
        }
        if ($is_pseudo_set)
        {
            if ($current_pseudo == $_POST['pseudo'])
            {
                array_push($user_selected_pseudo_array, $current_pseudo);
            }
        }
        else
        {
            $user_selected_pseudo_array = $pseudo_array;
        }
        if ($is_gender_set)
        {
            if ($content[2] == $_POST['gender'])
            {
                array_push($gender_array, $current_pseudo);
            }
        }
        else
        {
            $gender_array = $pseudo_array;
        }
        if ($is_rank_set)
        {
            if ($content[9] == $_POST['rank'])
            {
                array_push($rank_array, $current_pseudo);
            }
        }
        else
        {
            $rank_array = $pseudo_array;
        }
        if ($is_photo_set)
        {
            if ((file_exists("../data/images/$current_pseudo.jpg") && $_POST['photo'] == "avec") || (!file_exists("../data/images/$current_pseudo.jpg") && $_POST['photo'] == "sans"))
            {
                array_push($photo_array, $current_pseudo);
            }
        }
        else
        {
            $photo_array = $pseudo_array;
        }
    }

    // Keep the intersection of all arrays : it keeps user pseudos wich are meeting all requirements
    $intersection = array_intersect($user_selected_pseudo_array, $age_array, $gender_array, $rank_array, $photo_array);
}
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="search-result.css">
    <link rel="icon" href="../media/logo.png">
    <title>Résulats</title>
</head>

<body>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">LeSunShine</p>
        </div>
        <div id="header-container-right">
            <div class="button" onclick="location.href='search-html.php'">
                Recherche
            </div>
        </div>
    </header>
    <div id="lower">
        <?php
        foreach ($intersection as $elmt) :
            // Make sure you don't find yourself in search
            if ($elmt == $_SESSION['pseudo'])
            {
                continue;
            }

            // Get all the infos with user file
            $userFile = fopen("../data/users/$elmt.sunshine", "rb");
            if (!$userFile)
            {
                exit("Something went wrong while trying to open user file");
            }

            $content = fread($userFile, filesize("../data/users/$elmt.sunshine"));
            if (!fclose($userFile))
            {
                exit("Something went wrong while trying to close user file");
            }

            $array = explode("\r\n", $content);

            // Get variables
            $pseudo = $array[0];
            $signup_date = $array[1];
            $gender = $array[2];
            $birthdate = $array[3];
            $message = $array[4];

            // Get age 
            $date = new DateTime($birthdate);
            $now = new DateTime();
            $interval = $now->diff($date);
            $age = $interval->y;
        ?>
            <div class="result">
                <div class="content">
                    <div class="basic">
                        <p>Pseudo : <?= $pseudo ?></p>
                        <p>Genre : <?= $gender ?></p>
                        <p>Age : <?= $age ?></p>
                        <p>Date d'inscription : <?= $signup_date ?></p>
                    </div>
                    <div class="message">
                        Message : <?= $message ?>
                    </div>
                    <div class="sendmessage">
                        Envoyer Message
                    </div>
                </div>
                <div class="profil-image">
                    <img src="../data/images/<?php echo $elmt ?>.jpg" alt="L'utilisateur n'a pas d'image.">
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>