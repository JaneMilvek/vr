<?php
    $myname = "Jane Milvek"
    $currenttime = date("d.m.Y H:i:s")
    $timehtml = "\n <p> Lehe avamise hetkel oli: " .$currenttime .".</p> \n";
    $semesterbegin = new DateTime("2021-1-25");
    $semesterend = new DateTime("2021-6-30");
    $semesterduration = $semesterbegin->diff($semesterend);
    $semesterdurationdays = $semesterduration->format("%r%a");
    $semesterdurhtml = "\n <p>Kevadsemester 2021 kestvus on " .$semesterdurationdays ." päeva.</p> \n";
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
    </php
        echo $timehtml;
        echo $semesterdurhtml;
    ?>
</body>
</html>