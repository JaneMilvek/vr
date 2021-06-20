<?php
    //session_start();

    require "classes/SessionManager.class.php";
    SessionManager::sessionStart("vr", 0, "/~jane.milvek/", "tigu.hk.tlu.ee");

    require_once "../../../config.php";
    //require_once "fnc_general.php";
    require_once "fnc_user.php";

    /* // class näide
    require_once "classes/Test.class.php";
    $test_object = new Test(5);
    //echo $test_object->secret;          // annab veateate, kuna ei saa küsida secret objekti
    echo " Avalik number on " .$test_object->non_secret .". ";        // töötab, kuna on public objekt
    $test_object->reveal();
    unset($test_object); */

    $myname = "Jane Milvek";
    $currenttime = date("d.m.Y H:i:s");
    $timehtml = "\n <p> Lehe avamise hetkel oli: " .$currenttime .".</p> \n";
    $semesterbegin = new DateTime("2021-1-25");
    $semesterend = new DateTime("2021-6-30");
    $semesterduration = $semesterbegin->diff($semesterend);         // Diff funktsioon võrdleb alguse ja lõpuaega
    $semesterdurationdays = $semesterduration->format("%r%a");      // muudab ajaformaadi päevadeks

    $semesterdurhtml = "\n <p>Kevadsemester 2021 kestvus on " .$semesterdurationdays ." päeva.</p> \n";
    $today = date_create();                                         // määrab muutuja tüübi
    $today = new DateTime("now");                                   // määran tänase kuupäeva
    $fromsemesterbegin = $semesterbegin->diff($today);
    $fromsemesterbegindays = $fromsemesterbegin->format("%r%a");
    
    if($fromsemesterbegindays <= $semesterdurationdays) {
        $semesterprogress = "\n" .'<p>Semester edeneb: <meter min="0" max="' .$semesterdurationdays .'" value="' .$fromsemesterbegindays .'"></meter>.<p>' ."\n";
    } else {
        $semesterprogress = "\n <p>Semester on loppenud.</p> \n";
    }

    // Nädalapäeva leidmine
    $weekday_nr=date('w');                                  // date(w) on PHP funktsioon on nädalapäevade numbriline definitsioon
	                                                        // moodustame listi/massiivi nädalapäevadega
	$day_names=['pühapäev','esmaspäev','teisipäev','kolmapäev','neljapäev','reede','laupäev'];
	                                                        // listist võetakse tänane päev ja kuvatakse seda.
	$todaysweekdayhtml="<p> Täna on ". $day_names[$weekday_nr].".</p>";

    // kuupäeva kontroll
    $today_manually = new DateTime();                                   // seadistame kuupäeva näitamise
    $today_manually->setDate(2021, 1, 1);                              // muudame kuupäeva vastavalt, et näha semestri progressi muutusi
    $iftoday = "Kui täna oleks ".$today_manually->format('d.m.Y'.",");  // edasi kontrollime, kas etteantud kuupäev on semestri kestuse sees või mitte
    $fromsemesterbegin = $semesterbegin->diff($today_manually); 	    // diff annab ajavahemiku semestri alguskuupäevast määratud kuupäevani
    $fromsemesterbegindays = $fromsemesterbegin->format("%r%a");        // muudame ajavahemiku päevadeks

    if($fromsemesterbegindays <= $semesterdurationdays && $fromsemesterbegindays >=0) {
        $semesterprogress_ver2 = 'Oleks semester omadega sealmaal: <meter min="0" max="' . $semesterdurationday
        .'" value="' .$fromsemesterbegindays .'"></meter>';             // ajavahemik on lubatud piires, seega semester kestab ja vormindame HTML muutuja mis näitab semetri kulgu
    } else {
        if ($fromsemesterbegindays <0) {
            $semesterprogress_ver2 = " poleks semester veel alanud.";      // ajavahemik on negatiivne, seega pole semester veel alanud
        } else {
        $semesterprogress_ver2 = " oleks semester lõppenud.";           // ajavahemik oli semestrist pikem ja seega semester on lõppenud
        }          
    }

    // Piltide kuvamine
    $picsdir = "pics/";                                                 // loeme piltide kataloogi sisu
    $allfiles = array_slice(scandir($picsdir), 2);                      // nr 2 lõpus on scandiriga loetud kaks esimest kirjet, mis räägivad lihtsalt kataloogist, seega need ei ole pildid

    $allowedphototypes = ["image/jpeg", "image/png"];
    $photocountlimit = 3;
    $picfiles = [];                                                     // tekitame listi/massiivi $picfiles
    $photostoshow = [];

    foreach($allfiles as $file) {                                       // for tsükkel et leida vaid pildifailid allfilest ja siis tähista iga võetud fail $file. Tsükkel läbitakse niipalju kordi, kui me $allfilesis leidsime
        $fileinfo = getimagesize($picsdir .$file);                      // küsime faili suurust, sest selle abil saame me veel hunniku asju teada just sellelt pildilt mh failitüübi, mida meil vaja ongi
        if(isset($fileinfo["mime"])) {                                  // kui nüüd fileinfos on "mime" siis edasi
            if(in_array($fileinfo["mime"], $allowedphototypes) == true) {       // kui arrays on mime ja kas ta on allowed... massiivis
                array_push($picfiles, $file);                                   // array_push tähendab võetakse failime ja pannakse file picfiles massiivi
            }
        }
    }

    $randomphotofunc = array_rand($picfiles,3);                         // kuvatakse kolme juhuslikku pilti

    //sisselogimine
    $notice = null;
    $email= null;
    $email_error = null;
    $password_error = null;

    if(isset($_POST["login_submit"])) {
        // kontrollime, kas email ja parool on põhimõtteliselt olemas

        $notice = sign_in($_POST["email_input"], $_POST["password_input"]);
    }

    if(isset($_SESSION["user_id"])) {
        $username = $_SESSION["user_name"];
    }
?>

<!DOCTYPE html>
<html>
<head>    
    <meta charset="utf-8">
    <title>Veebirakendused ja nende loomine 2021</title>
    <link rel="stylesheet" type="text/css" href="style/page_styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <h1>
            <?php
                echo $myname
            ?>    
        </h1>
        <div>
            <?php
                echo "Tere tulemast ".((isset($_SESSION["user_id"])) ? $username : "Külaline")."!";
            ?>
        </div>
    </header>
        <div class="topnav">
            <a id="home" href="">Avaleht</a>
            <a target="_self" href="add_user.php">Kasutaja loomine</a>
        </div>
    <div>
        <?PHP if(!isset($_SESSION["user_id"])): ?>
            <div class="user_log_in">
                <h2>Logi sisse</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <label>E-mail (kasutajatunnus):</label><br>
                    <input type="email" name="email_input" value="<?php echo $email; ?>"><span><?php echo $email_error; ?></span><br>
                    <label>Salasõna:</label><br>
                    <input type="password" name="password_input"><span><?php echo $password_error; ?></span><br>
                    <input type="submit" name="login_submit" value="Logi sisse!"><span><?php echo $notice == 0 ? "" : ($notice == 1 ? "Vale salasõna!" : "Sellist kasutajanime pole!"); ?></span>
                </form>
                <!-- <p>Loo endale <a href="add_user.php">kasutajakonto!</a></p> -->

                <?php else: ?>	
                    <p>Oled Sisse logitud</p>
                    <p><a href="home.php?logout=1">Logi välja</a></p>
                <?php endif ?>
            </div>
    </div>        
    <hr>
    <div class="andmed">
        <?php
            echo $timehtml;
            echo $semesterdurhtml;
            echo $semesterprogress;
            echo "<p>";
            echo $iftoday;
            echo $semesterprogress_ver2;
            echo "<p>";
            echo $todaysweekdayhtml;
        ?>
    <div>

    <div class="row">
        <h2 class="col-12">Piltide valik</h2>
        <div class="d-md-flex">
            <img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[0]]; ?>" alt="Suvaline pilt 1">
            <img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[1]]; ?>" alt="Suvaline pilt 2">
            <img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[2]]; ?>" alt="Suvaline pilt 3">
        </div>
    </div>
    <footer>
            <h3>Kontakt</h3>
            <p>Jane Milvek</p>
            <a href="mailto:jane.milvek@tlu.ee">jane.milvek@tlu.ee</a><br><br>
            <a href="https://github.com/JaneMilvek/vr" target="_blank">Jane GitHub</a>
            </br>
            </br>
            <p>See leht on valminud õppetöö raames!</p>
            <p><a target="_self" href="#home">Back to top</a></p>
    </footer>
</body>
</html>
