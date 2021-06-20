<?php
    
    // Vigade kuvamiseks
    error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);


    require_once "../../../config.php";
    // echo $server_host;
    // var_dump($_POST);   	            // on olemas ka $_GET  psot puhul saame POST massiivi
    // require_once "fnc_general.php";

    $photo_upload_error = null;
    $image_file_type = null;
    $image_file_name = null;
    $file_name_prefix = "vr_";
    $file_size_limit = 1.5 * 1024 * 1024;
    $image_max_w = 600;
    $image_max_h = 400;

    if (isset($_POST["photo_submit"])) {
        //var_dump($_POST);
        //var_dump($_FILES);
        
        // kontrollime kas tegemist on pildiga
        $check = getimagesize ($_FILES["file_input"]["tmp_name"]);
        if ($check !== false) {
            // kontrollime failivormingut ja fikseerime laiendi
            if ($check["mime"] == "image/jpeg") {
                $image_file_type = "jpg";
            } elseif ($check["mime"] == "image/png") {
                $image_file_type = "png";
            } else {
                $photo_upload_error = "Pole sobiv pildi formaat, ainule jpg ja png on lubatud!";
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
                // loome oma failinime
                $timestamp = microtime(1) * 10000;
                $image_file_name = $file_name_prefix .$timestamp ."." .$image_file_type;

                // Pildi suuruse muutmine
                // loome pikslikogumi ehk image objekti
                $temp_image = null;
                if ($image_file_type == "jpg") {
                    $temp_image = imagecreatefromjpeg($_FILES["file_input"]["tmp_name"]);
                }
                if ($image_file_type == "png") {
                    $temp_image = imagecreatefrompng($_FILES["file_input"]["tmp_name"]);
                }

                $image_w = imagesx($temp_image);
                $image_h = imagesy($temp_image);

                // kuvasuhte säilitamiseks arvutame suuruse muutuse kordaja lähtudes kõrgusest või laiusest
                if ($image_w / $image_max_w > $image_h / $image_max_h) {
                    $image_size_ratio = $image_w / $image_max_w;
                } else {
                    $image_size_ratio = $image_h / $image_max_h;
                }

                $image_new_w = round($image_w / $image_size_ratio);
                $image_new_h = round($image_h / $image_size_ratio);

                // vähendamiseks loome uue image objekti, kuhu kopeerime vähendatud kujutise
                $new_temp_image = imagecreatetruecolor($image_new_w, $image_new_h);
                imagecopyresampled($new_temp_image, $temp_image, 0, 0, 0, 0, $image_new_w, $image_new_h, $image_w, $image_h);

                // salvestame piksli kogumi faili
                $target_file = "../upload_photos_normal/" .$image_file_name;
                if ($image_file_type == "jpg") {
                    if(imagejpeg($new_temp_image, $target_file, 90)) {
                        $photo_upload_error = "Vähendatud pilt on salvestatud.";
                    } else {
                        $photo_upload_error = "Vähendatud pilti ei salvestatud.";
                    }
                }
                if ($image_file_type == "png") {
                    if(imagepng($new_temp_image, $target_file, 6)) {
                        $photo_upload_error = "Vähendatud pilt on salvestatud.";
                    } else {
                        $photo_upload_error = "Vähendatud pilti ei salvestatud.";
                    }
                }

                //$target_file= "../upload_photos_orig/" .$_FILES["file_input"]["name"];
                    $target_file= "../upload_photos_orig/" .$image_file_name;
                if (move_uploaded_file($_FILES["file_input"]["tmp_name"], $target_file)) {
                        $photo_upload_error .= " Pildi üleslaadimine õnnestus.";
                } else {
                    $photo_upload_error .= " Pildi üleslaadimine ebaõnnestus.";
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
    <hr>
    <p><a href="?logout=1">Logi välja</a></p>
	<p><a href="home.php">Avalehele</a></p>
</head>
<body>
    <h1>Piltide üleslaadiminme</h1>
    <p>See leht on valmistatud õppetöö raames</p>
    <hr>
    <form method="POST" enctype="multipart/form-data"> <!--action="<//?php echo htmlspecialschars ($_SERVER["PHP_SELF"]);?>"-->
        <label for="file_input">Vali pildi fail</label>
        <input id="file_input" name="file_input" type="file">
        <br>
        <label for="alt_input">Alternatiivtekst ehk pildi selgitus</label>
        <input id="alt_text" name="alt-text" type="text" placeholder="Pildil on ...">
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
        <input type="submit" name="photo_submit" value="Lae pilt üles">
    </form>
    <br>
    <p><?php echo $photo_upload_error; ?></p>
</body>
</html>