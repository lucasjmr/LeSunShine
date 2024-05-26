<?php

function error_page($message)
{
    $htmlTemplate = file_get_contents('error.html');
    $errorPage = str_replace('{error_message}', $message, $htmlTemplate);
    $errorPage = str_replace('history.back()', "location.href='dashboard-html.php'", $errorPage);
    return $errorPage;
}

if (isset($_GET['user']))
{
    $pseudo = htmlspecialchars($_GET['user']);
}
else
{
    echo error_page("Erreur GET");
    exit();
}
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="ban-animation.css">
    <link rel="icon" href="../media/logo.png">
    <title>Banning page</title>
</head>

<body>
    <script>
        function videoEnded() {
            location.href = "panel-admin-html.php";
        }
    </script>
    <video autoplay muted playsinline poster="../media/ban-poster.jpg" onended="videoEnded()">
        <source src="../media/ban.mp4">
    </video>
    <header>
        <div id="header-container-left">
            <img class="logo" src="../media/logo.png" alt="logo">
            <p id="title">BAN HAMMER HAS SPOKEN</p>
        </div>
    </header>
    <div class="box">
        <?= $pseudo ?>
    </div>
</body>

</html>