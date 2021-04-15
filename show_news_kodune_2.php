<?php

    require_once "../../../../config.php";

    function read_news() {

        //Kodutöö osa

        if(isset($_POST["count_submit"])) {
            //kui oled valinud kuvatavate uudiste arvu
            $newsCount = $_POST['newsCount'];
        // kuvatavate uudiste arv, mis antakse sisendist
        }
        else {
            $newsCount = 3;         //uudiste arv vaikimisi
        }
        
        // loome andmebaasis serveriga ja baasiga ühenduse
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        // määrasme suhtluseks kodeeringu
        $conn -> set_charset("utf8");
        // valmistan ette SQL käsu
        $statement = $conn -> prepare("SELECT vr21_news_news_title, vr21_news_news_content, vr21_news_news_author, vr21_news_added FROM vr_2021_news ORDER BY vr21_news_id DESC LIMIT ?");
        echo $conn -> error;
        
        $statement -> bind_result($news_title_from_db, $news_content_from_db, $news_author_from_db, $news_date_from_db);
        $statement -> bind_param("s", $newsCount);          // sisend uudiste käsule
        $statement -> execute();
        $raw_news_html = null;

        // Kodutöö osa
        $newsDate = new DateTime($news_date_from_db);       // andmebaasist võetud kuupäev muudetakse dateTime objektiks
        $newsDate = $newsDate -> format('d.m.Y');           // teisendame dateTime objekti eesti keelele sobivamale kujule


        while ($statement -> fetch()) {
            $raw_news_html .= "\n <h2>" .$news_title_from_db ."</h2>";
            $raw_news_html .= "\n <p>Lisatud: " .$newsDate ."<p>";              // lisatakse artikli alla ka artikli sisestamise kuupäev
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
</head>
<body>
    <h1>Uudiste näitamine</h1>
    <p>See leht on valmistatud õppetöö raames</p>
    <hr>
    <form method="POST">        <!--Uudiste arvu küsimine-->
    <input type="number" min="1" max="10" value="3" name="newsCount">
    <input type="submit" name="count_submit" value="Kuva uudised">
    </form>

    <?php echo $news_html; ?>
    
</body>
</html>