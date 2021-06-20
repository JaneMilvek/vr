<?php
    require_once "usesession.php";
    require_once "../../../config.php";
    require_once "fnc_general.php";
    // echo $server_host;               // kui tahan testida, kas ühendus on olemas
    // var_dump($_POST);   	            // on olemas ka $_GET  psot puhul saame POST massiivi

    $news_input_error = null;
    // $news_title = null;
    // $news_content = null;
    // $news_author = null;

    if (isset($_POST["news_submit"])) {
        if(empty($_POST["news_title_input"])) {
            $news_input_error = "Uudise pealkiri on puudu! "; 
        }

        if(empty($_POST["news_content_input"])) {
            $news_input_error .= "Uudise sisu on puudu!"; // .= lisab eelmisele veateatele juurde
        }
            
        if (empty($news_input_error)) {
            //salvestame andmebaasi
            store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"]);
        }
    }

    function store_news($news_title, $news_content, $news_author_input) {
        // echo $news_title .$news_content .$news_author_input;           // kuvatakse sisestatud sisu
        // echo $GLOBALS["server_host"];
        
        // loome andmebaasis serveriga ja baasiga ühenduse
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        
        // määrame suhtluseks kodeeringu
        $conn -> set_charset("utf8");
        
        // valmistan ette SQL käsu
        $statement = $conn -> prepare("INSERT INTO vr_2021_news (vr21_news_news_title, vr21_news_news_content, vr21_news_news_author) VALUES (?,?,?)");
        echo $conn -> error;
        
        // i - integer s - string d - decimal ehk seome küsimärgid päris andmetega
        $statement -> bind_param("sss", $news_title, $news_content, $news_author);
        $statement -> execute();
        $statement -> close();
        $conn -> close();
    }

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
        <h1>Uudiste lisamine</h1>
        <p>See leht on valmistatud õppetöö raames</p>
    </header>
    <div class="topnav">
        <a href="?logout=1">Logi välja</a>
        <a href="show_news.php">Uudiste lugemine</a>
        <a href="upload_photo.php">Fotode üleslaadimine</a>
        <a href="photogallery_loginusers.php">Fotogalerii</a>
        <a href="home.php">Avalehele</a>
    </div>
    <form class="uudiste_lisamine" method="POST">
        <label for="news_title_input">Uudise pealkiri</label>
        <br>
        <input type="text" id="news_title_input" name="news_title_input" placeholder="Uudise pealkiri">
        <br>
        <label for="news_content_input">Uudise sisu</label>
        <br>
        <textarea for="news_content_input" name="news_content_input" placeholder="Uudise sisu" rows="6" cols="40"></textarea>
        <br>
        <label for="news_author_input">Uudise lisaja nimi</label>
        <br>
        <input type="text" id="news_author_input" name="news_author_input" placeholder="Autor">
        <br>
        <input type="submit" name="news_submit" value="Salvesta uudis">
    </form>
    <br>
    <p><?php echo $news_input_error; ?></p>
</body>
</html>