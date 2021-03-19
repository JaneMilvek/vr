<?php
    $myname = "Jane Milvek";
    $currenttime = date("d.m.Y H:i:s");
    $timehtml = "\n <p> Lehe avamise hetkel oli: " .$currenttime .".</p> \n";
    $semesterbegin = new DateTime("2021-1-25");
    $semesterend = new DateTime("2021-6-30");
    $semesterduration = $semesterbegin->diff($semesterend);             // diff funktsioon võrdleb semestri alguse ja lõpu aega
    $semesterdurationdays = $semesterduration->format("%r%a");          // muudab ajaformaadi päevadeks

    $semesterdurhtml = "\n <p>Kevadsemester 2021 kestvus on " .$semesterdurationdays ." päeva.</p> \n";
    $today = date_create();                                            // määrab muutuja tüübi 
    $today = new DateTime("now");                                       // määratakse tänane kuupäev
    $fromsemesterbegin = $semesterbegin->diff($today);                  
    $fromsemesterbegindays = $fromsemesterbegin->format("%r%a");
    

    if($fromsemesterbegindays <= $semesterdurationdays) {
        $semesterprogress = "\n" .'<p>Semester edeneb: <meter min="0" max="' .$semesterdurationdays .'" value="' .$fromsemesterbegindays .'"></meter>.<p>' ."\n";
        // kui tänane kuupäev jääb semestri sisse siis kuvatakse, et semester veel käib
    } else {
        $semesterprogress = "\n <p>Semester on loppenud.</p> \n";
        // kui tänane kuupäev jääb semestrist välja siis kuvatakse, et semester on lõppenud
    }

    // Kodutöö 1 - 1

    setlocale(LC_TIME, 'et_EE.utf8');                                   // näitame eesti keeles kuupäeva
    $todayname ="<p> Täna on ". strftime('%A.');

    // Kodutöö 1 - 2

    $today_manually = new DateTime();                                   // seadistame kuupäeva näitamise
    $today_manually->setDate(2020, 5, 10);                              // muudame kuupäeva vastavalt, et näha semestri progressi muutusi
    $iftoday = "Kui täna oleks ".$today_manually->format('d.m.Y'.",");  // edasi kontrollime, kas etteantud kuupäev on semestri kestuse sees või mitte
    $fromsemesterbegin = $semesterbegin->diff($today_manually); 	    // diff annab ajavahemiku semestri alguskuupäevast määratud kuupäevani
    $fromsemesterbegindays = $fromsemesterbegin->format("%r%a");        // muudame ajavahemiku päevadeks

    if($fromsemesterbegindays <= $semesterdurationdays && $fromsemesterbegindays >=0) {
        $semesterprogress_ver2 = 'Oleks semester omadega sealmaal: <meter min="0" max="' . $semesterdurationdays
        .'" value="' .$fromsemesterbegindays .'"></meter>';             // ajavahemik on lubatud piires, seega semester kestab ja vormindame HTML muutuja mis näitab semetri kulgu
        }
        else {
            if ($fromsemesterbegindays <0) 
	        {$semesterprogress_ver2 = " poleks semester veel alanud."; }    // ajavahemik on negatiivne, seega pole semester veel alanud
	        else {
	        $semesterprogress_ver2 = " oleks semester lõppenud.";}          // ajavahemik oli semestrist pikem ja seega semester on lõppenud
        }

    // Kodutöö 1 -3

    $picsdir = "pics/";                                                 // loeme piltide kataloogi sisu
    $allfiles = array_slice(scandir($picsdir), 2);                      // nr 2 lõpus on scandiriga loetud kaks esimest kirjet, mis räägivad lihtsalt kataloogist, seega need ei ole pildid

    $allowedphototypes = ["image/jpeg", "image/png"];
    $picfiles = [];                                                     // tekitame listi/massiivi $picfiles
    
    foreach($allfiles as $file) {                                       // for tsükkel et leida vaid pildifailid allfilest ja siis tähista iga võetud fail $file. Tsükkel läbitakse niipalju kordi, kui me $allfilesis leidsime
        $fileinfo = getimagesize($picsdir .$file);                      // küsime faili suurust, sest selle abil saame me veel hunniku asju teada just sellelt pildilt mh failitüübi, mida meil vaja ongi
        if(isset($fileinfo["mime"])) {                                  // kui nüüd fileinfos on "mime" siis edasi
            if(in_array($fileinfo["mime"], $allowedphototypes) == true) {       // kui arrays on mime ja kas ta on allowed... massiivis
                array_push($picfiles, $file);                                   // array_push tähendab võetakse failime ja pannakse file picfiles massiivi
            }
        }
    }

    $randomphotofunc = array_rand($picfiles,3);                         // kuvatakse kolme juhuslikku pilti

?>

<!doctype html>
<html>
<head>   
    <meta charset="utf-8">
    <title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
    <h1>
        <?php
            echo $myname
        ?>    
    </h1>
    <p>See leht on valmistatud õppetöö raames</p>
    <?php
        echo $timehtml;
        echo $semesterdurhtml;
        echo $semesterprogress;
        echo "<p>";
        echo $iftoday;
        echo $semesterprogress_ver2;
        echo "<p>";
        echo $todayname;
    ?>

    <div class="row">
        <h2 class="col-12">Piltide valik</h2>
        <div class="d-md-flex">
            <img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[0]]; ?>" alt="Suvaline pilt 1">
            <img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[1]]; ?>" alt="Suvaline pilt 2">
            <img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[2]]; ?>" alt="Suvaline pilt 3">
        </div>
        <a class="col-12" href="www.smaily.com">Vaata koodi Githubist</a>
    </div>
</body>
</html>