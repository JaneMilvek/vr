<?php
    require_once "usesession.php";
    require_once "../../../config.php";

    function read_news() {

        if(isset($_POST["count_submit"])) {         //kui oled valinud kuvatavate uudiste arvu
            $newsCount = $_POST['newsCount'];       // kuvatavate uudiste arv, mis antakse sisendist
        } else {
            $newsCount = 3;                         //uudiste arv vaikimisi
        }
        
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);       // loome andmebaasis serveriga ja baasiga ühenduse
        $conn -> set_charset("utf8");               // määrasme suhtluseks kodeeringu
        $statement = $conn -> prepare("SELECT vr21_news_news_title, vr21_news_news_content, vr21_news_news_author, vr21_news_added FROM vr_2021_news ORDER BY vr21_news_id DESC LIMIT ?");      // valmistan ette SQL käsu
        echo $conn -> error;

        $statement -> bind_result($news_title_from_db, $news_content_from_db, $news_author_from_db, $news_date_from_db);
        $statement -> bind_param("s", $newsCount);          // sisend uudiste käsule
        $statement -> execute();
        $raw_news_html = null;

        

        while ($statement -> fetch()) {
            $raw_news_html .= "\n <h2>" .$news_title_from_db ."</h2>";
            $newsDate = new DateTime($news_date_from_db);               // andmebaasist võetud kuupäev muudetakse dateTime objektiks
            $newsDate = $newsDate -> format('d.m.Y');                   // teisendame dateTime objekti eesti keelele sobivamale kujule
            $raw_news_html .= "\n <p>Lisatud: " .$newsDate ."<p>";      // lisatakse artikli alla ka artikli sisestamise kuupäev
            $raw_news_html .= "\n <p>" .nl2br($news_content_from_db) ."</p>";
            $raw_news_html .= "\n <p>Edastas: ";
            if(!empty($news_author_from_db)) {
                $raw_news_html .= $news_author_from_db;
            } else {
                $raw_news_html .= "Tundmatu reporter";
            }
            $raw_news_html .= "</p>";
        }
        $statement -> close();
        $conn -> close();
        return $raw_news_html;
    }

    $news_html = read_news();

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
    <h1>Uudiste lugemine</h1>
    <p>See leht on valmistatud õppetöö raames</p>
    </header>
    <div class="topnav">
        <a href="?logout=1">Logi välja</a>
        <a href="add_news.php">Uudiste lisamine</a>
        <a href="upload_photo.php">Fotode üleslaadimine</a>
        <a href="photogallery_loginusers.php">Fotogalerii</a>
        <a href="home.php">Avalehele</a>
    </div>
    <div class="uudiste_lugemine">
        <p>Mitu uudist soovid kuvada?</p>
        <form method="POST">        <!--Uudiste arvu küsimine-->
        <input type="number" min="1" max="10" value="3" name="newsCount">
        <input type="submit" name="count_submit" value="Kuva uudised">
        </form>

        <?php echo $news_html; ?>

    </div>
</body>
</html>