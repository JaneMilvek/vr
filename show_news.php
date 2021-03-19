<?php

    require_once "../../../config.php";

    function read_news() {
        
        // loome andmebaasis serveriga ja baasiga ühenduse
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        // määrasme suhtluseks kodeeringu
        $conn -> set_charset("utf8");
        // valmistan ette SQL käsu
        $statement = $conn -> prepare("SELECT vr21_news_news_title, vr21_news_news_content, vr21_news_news_author FROM vr_2021_news");
        echo $conn -> error;
        $statement -> bind_result($news_title_from_db, $news_content_from_db, $news_author_from_db);
        $statement -> execute();
        $raw_news_html = null;
        while ($statement -> fetch()) {
            $raw_news_html .= "\n <h2>" .$news_title_from_db ."</h2>";
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
    <h1>Uudiste lugemine</h1>
    <p>See leht on valmistatud õppetöö raames</p>
    <hr>
    <?php echo $news_html; ?>
</body>
</html>