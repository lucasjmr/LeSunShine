<?php
session_start();
if (!isset($_SESSION['pseudo'])) // if user not connected, bring back to connection
{
    header("Location: sign-in-html.php");
}
?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="upload.css">
    <link rel="icon" href="../media/logo.png">
    <title>LeSunShine</title>
</head>

<body>
    <header>
        <img class="logo" src="../media/logo.png" alt="logo">
        <p id="title">LeSunShine</p>
    </header>
    <div class="box">
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="file">Selectionner image (.jpg)</label>
            <input type="file" name="file" id="file">
            <input type="submit" name="submit" Value="UPLOAD">
        </form>
    </div>
</body>

</html>