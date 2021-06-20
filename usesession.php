<?php
    require("classes/SessionManager.class.php");
    SessionManager::sessionStart("vr", 0, "/~jane.milvek/", "tigu.hk.tlu.ee");

    // kontroll, kas kasutaja on sisse logitud
    if(!isset($_SESSION["user_id"])){
        //suunatakse sisselogimise lehele
        header("Location: page.php");
        exit();
    }
      
    //logime kasutaja välja
    if(isset($_GET["logout"])){
        //lõpetame sessiooni
        session_destroy();
        
        //suunatakse sisselogimise lehele
        header("Location: page.php");
        exit();
    }