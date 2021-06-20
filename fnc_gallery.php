<?php
	function read_all_semi_public_photo_thumbs(){
		$privacy = 2;
		$thumbs_dir = "../upload_photos_thumbnail/";
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$statement = $conn->prepare("SELECT vr_2021_photos.vr21_photos_id, vr_2021_photos.vr21_photos_filename, vr_2021_photos.vr21_photos_alttext, vr_2021_users.vr21_users_firstname, vr_2021_users.vr21_users_lastname FROM vr_2021_photos JOIN vr_2021_users ON vr_2021_photos.vr21_photos_userid = vr_2021_users.vr21_users_id WHERE vr_2021_photos.vr21_photos_privacy <= ? AND vr_2021_photos.vr21_photos_deleted IS NULL GROUP BY vr_2021_photos.vr21_photos_id");
		
		echo $conn->error;
		$statement->bind_param("i", $privacy);
		$statement->bind_result($id_from_db, $filename_from_db, $alt_from_db, $firstname_from_db, $lastname_from_db);
		$statement->execute();
		while($statement->fetch()){
			$html .= '<div class="thumbgallery">' ."\n";
			$html .= '<img src="' .$thumbs_dir .$filename_from_db .'" alt="'.$alt_from_db .'" class="thumbs" data-fn="' .$filename_from_db .'" data-id="' .$id_from_db .'">' ."\n \t \t";
			$html .= "<p>" .$firstname_from_db ." " .$lastname_from_db ."</p> \n \t \t";
			$html .= "</div> \n \t \t";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		$statement->close();
		$conn->close();
		return $finalHTML;
	}