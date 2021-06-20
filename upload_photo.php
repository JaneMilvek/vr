<?php
    
    // Vigade kuvamiseks
    error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

    require_once "usesession.php";
	require_once "../../../config.php";
	require_once "fnc_general.php";
	require_once "fnc_upload_photo.php";
	require_once "classes/Upload_photo.class.php";

    // echo $server_host;
    // var_dump($_POST);   	            // on olemas ka $_GET  psot puhul saame POST massiivi

    $photo_upload_error = null;
    $image_file_type = null;
    $image_file_name = null;
    $file_name_prefix = "vr_";
    $file_size_limit = 1.5 * 1024 * 1024;
    $image_max_w = 600;
    $image_max_h = 400;
    $image_thumbnail_size = 100;
    $notice = null;
    $watermark = "images/vr_watermark.png";

    if (isset($_POST["photo_submit"])) {
        
        // kontrollime kas tegemist on pildiga
        $check = getimagesize ($_FILES["file_input"]["tmp_name"]);
        if ($check !== false) {
            // kontrollime failivormingut ja fikseerime laiendi
            if ($check["mime"] == "image/jpeg") {
                $image_file_type = "jpg";
            } elseif ($check["mime"] == "image/png") {
                $image_file_type = "png";
            } else {
                $photo_upload_error = "Pole sobiv pildi formaat, ainult jpg ja png on lubatud!";
            }
        } else {
            $photo_upload_error = "Tegemist ei ole pildiga";
        }

        if(empty($photo_upload_error)) {

            // ega pole liiga suur fail
            if ($_FILES["file_input"]["size"] > $file_size_limit) {
                $photo_upload_error = "Valitud fail on liiga suur, lubatud kuni 1.5 MiB!";
            }

            if(empty($photo_upload_error)) {

                // võtame kasutusele Upload_photo klassi
                $photo_upload = new Upload_photo($_FILES["file_input"], $image_file_type);

                // loome oma failinime
                $timestamp = microtime(1) * 10000;
                $image_file_name = $file_name_prefix .$timestamp ."." .$image_file_type;

                $photo_upload->resize_photo($image_max_w, $image_max_h);

                // lisan vesimärgi
                $photo_upload->add_watermark($watermark);

                // salvestame piksli kogumi faili 
                $target_file = "../upload_photos_normal/" .$image_file_name;
                $result = $photo_upload->save_image_to_file($target_file);
                if ($result == 1) {
                    $notice = "Vähendatud pilt laeti üles!";
                } else {
                    $photo_upload_error = "Vähendatud pildi salvestamisel tekkis viga!";
                }

                // teen pisipildi
                $photo_upload->resize_photo($image_thumbnail_size, $image_thumbnail_size, false);
				
				//salvestame pisipildi faili
				$target_file = "../upload_photos_thumbnail/" .$image_file_name;
				$result = $photo_upload->save_image_to_file($target_file);
				if($result == 1) {
					$notice .= " Pisipilt laeti üles! ";
				} else {
					$photo_upload_error .= " Pisipildi salvestamisel tekkis viga!";
				}
				
				unset($photo_upload);

                $target_file= "../upload_photos_orig/" .$image_file_name;
                if (move_uploaded_file($_FILES["file_input"]["tmp_name"], $target_file)) {
                    $notice .= " Originaalfoto üleslaadimine õnnestus.";
                } else {
                    $photo_upload_error .= " Originaalfoto üleslaadimine ebaõnnestus.";
                }

                // kui kõik hästi, salvestame info andmebaasi
			    if($photo_upload_error == null){
				$result = store_photo_data($image_file_name, $_POST["alt_input"], $_POST["privacy_input"], $_FILES["file_input"]["name"]);
				    if($result == 1){
					$notice .= " Pildi andmed lisati andmebaasi!";
				    } else {
					$photo_upload_error = "Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
				    }
			    }
            }
        }
    }
?>


<!doctype html>
<html lang="et">
<head>    
    <meta charset="utf-8">
    <title>Veebirakendused ja nende loomine 2021</title>
    <script src="javascript/checkImageSize.js" defer></script>
    <link rel="stylesheet" type="text/css" href="style/page_styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
    <h1>Piltide üleslaadimine</h1>
    <p>See leht on valmistatud õppetöö raames</p>
    </header>
    </header>
    <div class="topnav">
        <a href="?logout=1">Logi välja</a>
        <a href="add_news.php">Uudiste lisamine</a>
        <a href="show_news.php">Uudiste lugemine</a>
        <a href="photogallery_loginusers.php">Fotogalerii</a>
        <a href="home.php">Avalehele</a>
    </div>
    <div class="fotode_import">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <label for="file_input">Vali pildi fail</label>
            <input id="file_input" name="file_input" type="file">
            <br>
            <label for="alt_input">Alternatiivtekst ehk pildi selgitus</label>
            <input id="alt_input" name="alt_input" type="text" placeholder="Pildil on ...">
            <br>
            <label>Privaatsustase: </label>
            <br>
            <input id="privacy_input_1" name="privacy_input" type="radio" value="3" checked> 
            <label for="privacy_input_1">Privaatne</label>
            <br>
            <input id="privacy_input_2" name="privacy_input" type="radio" value="2">
            <label for="privacy_input_2">Registreeritud kasutajatele</label>
            <br>
            <input id="privacy_input_3" name="privacy_input" type="radio" value="1"> 
            <label for="privacy_input_3">Avalik</label>
            <br>
            <input type="submit" id="photo_submit" name="photo_submit" value="Lae pilt üles">
        </form>
        <br>
        <p id="notice"><?php echo $photo_upload_error; echo $notice; ?></p>
    </div>
</body>
</html>