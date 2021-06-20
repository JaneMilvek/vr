<?php
    
    // Vigade kuvamiseks
    error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);


    require_once "../../../../config.php";
    // echo $server_host;
    // var_dump($_POST);   	            // on olemas ka $_GET  psot puhul saame POST massiivi

    $news_input_error = null;

    if (isset($_POST["news_submit"])) {
        if(empty($_POST["news_title_input"])) {
            $news_input_error = "Uudise pealkiri on puudu! "; 
        }
        if(empty($_POST["news_content_input"])) {
            $news_input_error .= "Uudise sisu on puudu!";
        }
            
        if (empty($news_input_error)) {
            //salvestame andmebaasi
            store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"]);
        }
    }

    function store_news($news_title, $news_content, $news_author_input) {
        // echo $news_title .$news_content .$news_author_input;                     // kuvatakse sisestatud sisu
        // echo $GLOBALS["server_host"];
        
        // loome andmebaasis serveriga ja baasiga ühenduse
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        // määrasme suhtluseks kodeeringu
        $conn -> set_charset("utf8");
        // valmistan ette SQL käsu
        $statement = $conn -> prepare("INSERT INTO vr_2021_news (vr21_news_news_title, vr21_news_news_content, vr21_news_news_author) VALUES (?,?,?)");
        echo $conn -> error;
        // i - integer s - string d - decimal
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
</head>
<body>
    <h1>Uudiste lisamine</h1>
    <p>See leht on valmistatud õppetöö raames</p>
    <hr>
    <form method="POST">
        <label for="news_title_input">Uudise pealkiri</label>
        <br>
        <input type="text" id="news_title_input" name="news_title_input" placeholder="Uudise pealkiri" value="<?php echo isset($_POST["news_title_input"]) ? $_POST["news_title_input"] : "" ?>">
        <br>
        <label for="news_content_input">Uudise sisu</label>
        <br>
        <textarea for="news_content_input" name="news_content_input" placeholder="Uudise sisu" rows="6" cols="40"><?php echo isset($_POST["news_content_input"]) ? htmlspecialchars($_POST["news_content_input"]) : ""; ?></textarea>
        <br>
        <label for="news_author_input">Uudise lisaja nimi</label>
        <br>
        <input type="text" id="news_author_input" name="news_author_input" placeholder="Autor"value="<?php echo isset($_POST["news_author_input"]) ? $_POST["news_author_input"] : "" ?>">
        <br>
        <input type="submit" name="news_submit" value="Salvesta uudis">
    </form>
    <br>
    <p><?php echo $news_input_error; ?></p>
</body>
</html>