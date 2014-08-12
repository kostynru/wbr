<?php
	if(empty($_POST['act'])){

	if (!empty($_FILES)) {
		$upload_dir = 'img_upl' . DIRECTORY_SEPARATOR;
		$file_name = md5($_FILES['img_attachment']['name'] . time()) . '.' . end(explode('.', $_FILES['img_attachment']['name']));
		if (move_uploaded_file($_FILES['img_attachment']['tmp_name'], $upload_dir . $file_name)) {/*
			$query = "INSERT INTO `wall_attch`(`img_name`) VALUES ('{$file_name}')";
			include_once 'db.php';
			if (mysqli_query($mysqli_link, $query) <> FALSE) {*/
				echo $file_name;
			/*} else {
				echo 0;
			}*/
		} else {
			echo 0;
		}
	}
	} elseif($_POST['act'] == 'remove_img'){
		ignore_user_abort();
		$file_name = $_POST['file_name'];
		unlink('img_upl'.DIRECTORY_SEPARATOR.$file_name);
		echo '1';
	}