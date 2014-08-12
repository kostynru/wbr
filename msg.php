<?php
	header("Content-Type: text/event-stream");
	header("Cache-Control: no-cache");
	header("Connection: keep-alive");
	function sendMsg($id, $msg) {
		echo "id: {$id}" . PHP_EOL;
		if (!is_array($msg)) {
			echo "data: {\n";
			echo "data: \"msg\": \"{$msg[0]}\", \n";
			echo "data: \"id\": {$id}\n";
			echo "data: }\n";
		} else {
			echo "data: {\n";
			echo "data: \"msg\": \"<messages>";
			foreach($msg as $value){
				echo '<message id=\"'.$value['mid'].'\">'.$value['text'].'</message>';
			}
			echo "</messages>\", \n";
			echo "data: \"id\": {$id}\n";
			echo "data: }\n";
		}
		echo PHP_EOL;
		ob_flush();
		flush();
	}

	$startedAt = time();
	include_once 'db.php';
	if ($_GET['act'] == 'im_count') {
		$uid = $_GET['uid'];
		//$last_id = $_GET['lid'];
		do {
			if ((time() - $startedAt) > 30) {
				die();
			}
			$query = "SELECT COUNT(*) FROM `ims` WHERE `uid` = {$uid} AND `checked` = 0";
			$result = mysqli_query($mysqli_link, $query) or die(mysqli_error($mysqli_link));
			$answer = mysqli_fetch_array($result);
			if ($answer[0] <> '0') {
				sendMsg($startedAt, $answer[0]);
			}
			sleep(30);
		} while (TRUE);
	} elseif ($_GET['act'] == 'im_upd') {
		$uid = $_GET['uid'];
		$cid = $_GET['cid'];
		do {
			if ((time() - $startedAt) > 10) {
				die();
			}
			$query = "SELECT * FROM `ims` WHERE `uid` = {$uid} AND `sid` = {$cid} AND `checked` = 0";
			$result = mysqli_query($mysqli_link, $query);
			$answer = [];
			while ($row = mysqli_fetch_assoc($result)) {
				$answer[] = $row;
			}
			if(!empty($answer)){
				sendMsg($startedAt, $answer);
			}
			sleep(10);
		} while (TRUE);
	}
