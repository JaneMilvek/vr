<?php
    function sign_up($name, $surname, $gender, $birth_date, $email, $password) {
        $notice = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $statement = $conn->prepare("INSERT INTO vr_2021_users (vr21_users_firstname, vr21_users_lastname, vr21_users_birthdate, vr21_users_gender, vr21_users_email, vr21_users_password) VALUES (?,?,?,?,?,?)");
        echo $conn->error;

        // krüpteerime parooli
        // $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
        $options = ["cost" => 12];
        $pwd_hash = password_hash($password, PASSWORD_BCRYPT, $options);

        $statement ->bind_param("sssiss", $name, $surname, $birth_date, $gender, $email, $pwd_hash);

        if($statement -> execute()) {
            $notice = 1;
        }

        $statement -> close();
        $conn -> close();
        return $notice;
    }


    function sign_in($email, $password) {
        $notice = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $statement = $conn -> prepare("SELECT vr21_users_id, vr21_users_firstname, vr21_users_lastname, vr21_users_password FROM vr_2021_users WHERE vr21_users_email = ?");
        echo $conn -> error;
        $statement -> bind_param("s", $email);
        $statement -> bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $password_from_db);
        $statement -> execute();

        //kui leiti
        if($statement -> fetch()) {
            // kas parool klapib
            if(password_verify($password, $password_from_db)) {
                //olemegi sisse loginud
                $notice = 1;
                $_SESSION["user_id"] = $id_from_db;
                $statement -> close();
                $conn -> close();
                header("Location: home.php");
                exit();
            }
        }

        $statement -> close();
        $conn -> close();
        return $notice;
    }