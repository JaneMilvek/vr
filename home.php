<?php
    require_once "usesession.php";

    /* session_start();
    // kas kasutaja on sisse logitud
    if(!isset($_SESSION["user_id"])) {
        header("Location: page.php");
    }

    // välja logimine
    if(isset($_GET["logout"])) {
        session_destroy();
        header("Location: page.php");
    } */
?>

<!doctype html>
<html lang="et">
<head>    
    <meta charset="utf-8">
    <title>Veebirakendused ja nende loomine 2021</title>
    <link rel="stylesheet" type="text/css" href="style/page_styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
    <h1>Sisseloginud kasutaja</h1>
    <p>See leht on valmistatud õppetöö raames</p>
    </header>
    <div class="topnav">
        <a href="?logout=1">Logi välja</a>
        <a href="add_news.php">Uudiste lisamine</a>
        <a href="add_news_photo.php">Uudiste lisamine pildiga</a>
        <a href="show_news.php">Uudiste lugemine</a>
        <a href="upload_photo.php">Fotode üleslaadimine</a>
        <a href="photogallery_loginusers.php">Fotogalerii</a>
    </div>
</body>
</html>