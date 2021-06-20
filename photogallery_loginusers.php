<?php
	require_once "usesession.php";
	require_once "../../../config.php";
	require_once "fnc_gallery.php";
	
	//$gallery = readAllSemiPublicPictureThumbsPage($page, $limit);
	$gallery = read_all_semi_public_photo_thumbs();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
	<link rel="stylesheet" type="text/css" href="style/gallery.css">
    <link rel="stylesheet" type="text/css" href="style/modal.css">
	<link rel="stylesheet" type="text/css" href="style/page_styles.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="javascript/modal.js" defer></script>
</head>
<body>
	<header>
    <h1>Fotogalerii</h1>
    <p>See leht on valmistatud õppetöö raames</p>
    </header>
    <div class="topnav">
        <a href="?logout=1">Logi välja</a>
        <a href="add_news.php">Uudiste lisamine</a>
        <a href="show_news.php">Uudiste lugemine</a>
        <a href="upload_photo.php">Fotode üleslaadimine</a>
        <a href="home.php">Avalehele</a>
    </div>
    <!--Modaalaken fotogalerii jaoks-->
    <div id="modalarea" class="modalarea">
	    <!--sulgemisnupp-->
	    <span id="modalclose" class="modalclose">&times;</span>
	    <!--pildikoht-->
	    <div class="modalhorizontal">
		    <div class="modalvertical">
			    <p id="modalcaption"></p>
			    <img id="modalimg" src="images/empty.png" alt="galeriipilt">

                <!--pildi hindamine-->
                <br>
                <div id="rating" class="modalRating">
                    <label><input id="rate1" name="rating" type="radio" value="1">1</label>
                    <label><input id="rate2" name="rating" type="radio" value="2">2</label>
                    <label><input id="rate3" name="rating" type="radio" value="3">3</label>
                    <label><input id="rate4" name="rating" type="radio" value="4">4</label>
                    <label><input id="rate5" name="rating" type="radio" value="5">5</label>
                    <button id="storeRating">Salvesta hinnang!</button>
                    <br>
                    <p id="avgRating"></p>
                </div>
		    </div>
	    </div>
    </div>
	<div class="gallery" id="gallery">
		<?php echo $gallery; ?>
	</div>
</body>
</html>