<?php
    require_once "usesession.php";
    require_once "../../../config.php";
	
    $id = $_REQUEST["photoid"];
    $rating = $_REQUEST["rating"];

    $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
    $statement = $conn->prepare("INSERT INTO vr_2021_photoratings (vr21_photoratings_photoid, vr21_photoratings_userid, vr21_photoratings_rating) VALUES(?,?,?)");
    echo $conn->error;
    $statement->bind_param("iii", $id, $_SESSION["user_id"], $rating);
    $statement->execute();
    $statement->close();

    // loeme keskmise hinde
    $statement = $conn->prepare("SELECT AVG(vr21_photoratings_rating) as avgValue FROM vr_2021_photoratings WHERE vr21_photoratings_photoid = ?");
    echo $conn->error;
    $statement->bind_param("i", $id);
    $statement->bind_result($score);
    $statement->execute();
    $statement->fetch();
    $statement->close();
    $conn->close();
    echo round($score, 2);