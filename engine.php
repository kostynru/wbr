<?php
// LOGIN
	if ($_GET['act'] == 'login') {
		$username = preg_replace('/\s+/', '', trim($_POST['username']));
		$password = $_POST['password'];
		if (empty($username) or empty($password)) {
			header('Location: /wbr/'.'index.php?error=1');
		}
		include_once 'db.php';
		$username = mysqli_real_escape_string($mysqli_link, $username);
		$query = "SELECT * FROM `profiles` WHERE `email` = '$username'";
		$res = mysqli_query($mysqli_link, $query);
		$result = [];
		while ($row = mysqli_fetch_assoc($res)) {
			$result = $row;
		}
		if (empty($result) or $result == FALSE) {
			header('Location: /wbr/index.php?error=1');
		}
		/*
		 * WHAT THE FUCK IS THIS???
		 * BY THE WAY - ACTUALLY, IT ALWAYS FALSE...
		if ($result['password'] <> $_GET['password']) {
			header('Location: index.php?error=1');
		}*/
		if (compare_hash($username, $password, $result['password'])) {
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			$time2store = (isset($_POST['stay_logged_in'])) ? time() + 7889231: time() + 2592000;
			$session_id = md5($result['email'].$ip.strlen($result['email']));
			$query = "INSERT INTO `sessions` VALUES('{$session_id}', {$result['id']}, {$time2store})";
			$clearing_query = "DELETE FROM `sessions` WHERE `uid` = {$result['id']}";
			setcookie('session', $session_id, $time2store);
			mysqli_query($mysqli_link, $clearing_query);
			mysqli_query($mysqli_link, $query);
			session_start();
			$_SESSION = $result;
			header('Location: /wbr/'.'profile.php');
		}
    }
	if($_REQUEST['act'] == 'is_logged'){
		include_once 'db.php';
		if(isset($_COOKIE['session'])){
			$uid = intval($_REQUEST['uid']);
			$query = "SELECT *, `sessions`.`id` AS `sid` FROM `sessions` JOIN `profiles` ON `profiles`.`id` = `sessions`.`uid` WHERE `uid` = {$uid} LIMIT 1";
			$result = mysqli_query($mysqli_link, $query);
			if(empty($result) or !$result) {
				echo json_encode(['logged_in' => 'false']);
			} else {
				$session_inf = [];
				while($row = mysqli_fetch_assoc($result)){
					$session_inf = $row;
				}
				if($_COOKIE['session'] <> $session_inf['sid']){
					// Now, try to restore cookie if ip is the same


					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$ip = $_SERVER['REMOTE_ADDR'];
					}


					$restored_id = md5($session_inf['email'].$ip.strlen($session_inf['email']));
					if($restored_id <> $session_inf['sid']){
						echo json_encode(['logged_in' => 'false']);
					} else {
						$time2store = $session_inf['expire'];
						setcookie('session', $restored_id, $time2store);
						echo json_encode(['logged_in' => 'true']);
					}
				} else {
					echo json_encode(['logged_in' => 'true']);
				}
			}
		} else {
			echo json_encode(['logged_in' => 'false']);
		}
	}
    //LOGOUT
	if ($_GET['act'] == 'logout') {
		if (isset($_COOKIE['session'])) {
			include_once 'db.php';
			$query = "DELETE FROM `sessions` WHERE `id` = '{$_COOKIE['session']}'";
			mysqli_query($mysqli_link, $query);
			session_start();
			session_unset();
			session_destroy();
			setcookie('session', '', time() - 3600);
			setcookie('PHPSESSID', '', time() - 3600);
			setcookie('gravatar', '', time() - 3600);
			setcookie('j_lch', '', time() - 3600);

		}
		header('Location: /wbr/'.'index.php');
	}
	/* ///////////////USERS///////////////////*/
	// GET USER BY ID (PRIVATE)
	if($_POST['act'] == 'get_user_by_id'){
		include_once 'db.php';
		$uid = intval($_POST['uid']);
		$query = "SELECT * FROM `profiles` JOIN `userdata` ON `profiles`.`id` = `userdata`.`uid` WHERE `profiles`.`id` = {$uid} LIMIT 1";
		$result = mysqli_query($mysqli_link, $query);
		$answer = mysqli_fetch_assoc($result);
		header('Content-Type: application/json');
		echo json_encode($answer);
	}
	// SEARCHING ENGINE
	if($_GET['act'] == 'user_search'){
		if(!empty($_POST)){
			include_once 'db.php';
				$search_str = trim($_POST['search_string']);
				$srch_str_arr = explode(' ', $search_str);
				/*if(count($srch_str_arr) != 2){
					die('0');
				}*/
				$first_name = mysqli_real_escape_string($mysqli_link, $srch_str_arr[0]);
				$second_name = mysqli_real_escape_string($mysqli_link, $srch_str_arr[1]);
				$opt_city = (empty($_POST['city']) or !isset($_POST['city'])) ? '' : $_POST['city'];
			$query = "SELECT * FROM `profiles` JOIN `userdata` ON `profiles`.`id` = `userdata`.`uid`
WHERE (`profiles`.`first_name` LIKE '%{$first_name}%' OR '%{$second_name}%') OR (`profiles`.`second_name` LIKE '%{$first_name}%' OR '%{$second_name}%')
AND (`userdata`.`city` LIKE '%{$opt_city}%') AND `profiles`.`id` != {$_COOKIE['j_id']}";
			if(isset($_POST['gender']) and !empty($_POST['gender'])){
				$opt_gender = $_POST['gender'] - 1;
				$query .= " AND `userdata`.`gender` = '{$opt_gender}'";
			}
			$result = mysqli_query($mysqli_link, $query);
			if(!$result){
				echo '0';
			} else {
				$profiles = [];
				while($row = mysqli_fetch_assoc($result)){
					$profiles[] = $row;
				}
				if(empty($profiles)){
					echo '0';
				} else {
					foreach($profiles as $profile){
						$grav_url = md5($profile['email']);
						/*if (strlen($profile['about']) > 20) {
							$about = substr($profile['about'], 0, 20) . '...';
						} elseif(strlen($profile['about']) == 0){
							$about = '<br>';
						} else {
							$about = $profile['about'];
						}*/
						$city = empty($profile['city'])?'<br>' : $profile['city'];
						echo <<<FRIEND
<div class="media">
  <a class="pull-left" href="show/{$profile['id']}">
    <img class="media-object" src="http://gravatar.com/avatar/{$grav_url}?s=80&d=mm" alt="">
  </a>
  <div class="media-body">
    <h4 class="media-heading" id="user{$profile['id']}"><a href="show/{$profile['id']}">{$profile['first_name']} {$profile['second_name']}</a></h4>
    <div class="help-block">{$city}</div>
  </div>
</div>
FRIEND;

					}
				}
			}
		} else {
			die('0');
		}
	}
	/* ///////////////Messages/////////////// */
// SEND MESSAGE
	if ($_REQUEST['act'] == 'im_send') {
		include_once 'db.php';
        $sid = intval($_POST['user_id']);
        $uid = intval($_POST['friend_id']);
        if($sid == $fid){
            die('0');
        }
        $msg = $_POST['msg'];
        if($msg == ''){
            die('0');
        }
        $time = time();
        $query = "INSERT INTO `ims`(`text`, `sid`, `uid`, `time`) VALUES('{$msg}', $sid, $uid, $time)";
        $result = mysqli_query($mysqli_link, $query) or die(mysqli_error($mysqli_link));
        if($result){
            echo '1';
        } else {
            echo '0';
        }
    }
// COUNT MESSAGES
	if ($_REQUEST['act'] == 'im_count') {
		include_once 'db.php';
		$uid = intval($_GET['uid']);
		$query = "SELECT COUNT(*), MAX(`mid`) FROM `ims` WHERE `uid` = {$uid} AND `checked` = 0 LIMIT 100";
		$result = mysqli_query($mysqli_link, $query) or die(mysqli_error($mysqli_link));
		$unread = '';
		while ($row = mysqli_fetch_array($result)) {
			$unread = $row[0].'|';
			$unread .= empty($row[1])? '0': $row[1];
		}
		echo $unread;
	}
	if($_REQUEST['act'] == 'mark_all_im_as_read'){
		include_once 'db.php';
		$uid = intval($_POST['user_id']);
		$query = "UPDATE `ims` SET `checked` = 1 WHERE `uid` = {$uid}";
		mysqli_query($mysqli_link, $query);
		echo '1';
	}
	// CHECK FOR NEW MSGS
	if($_REQUEST['act'] == 'check_for_new_all'){
		include_once 'db.php';
		$uid = intval($_POST['user_id']);
		$query = "SELECT `mid` FROM `ims` WHERE `sid` = {$uid} ORDER BY `time` LIMIT 1";
		$result = mysqli_query($mysqli_link, $query);
		$last_msg_id = '';
		while($row = mysqli_fetch_array($result)){

		}
	}
	// CHECK USING FID
	if($_REQUEST['act'] == 'check_for_new_by_fid'){
		include_once 'db.php';
		$uid = intval($_POST['user_id']);
		$fid = intval($_POST['friend_id']);
		$query = "SELECT `mid` FROM `ims` WHERE `sid` = {$uid} AND `uid` = {$fid} ORDER BY `time` LIMIT 1";
		$result = mysqli_query($mysqli_link, $query);
		$last_msg_id = '';
		while($row = mysqli_fetch_array($result)){
			$last_msg_id = $row[0];
		}
	}
	if($_REQUEST['act'] == 'set_read'){
		include_once 'db.php';
		$uid = intval($_POST['uid']);
		$sid = intval($_POST['sid']);
		$query = "UPDATE `ims` SET `checked` = 1 WHERE `sid` = {$sid} AND `uid` = {$uid}";
		$result = mysqli_query($mysqli_link, $query);
		echo !$result ? '0' : '1';
	}
	/* //////////////////Users////////////////// */
// DECLINE INCOMING FRIEND QUERY
	if ($_REQUEST['act'] == 'friend_decline') {
		include_once 'db.php';
		$uid = intval($_POST['user_id']);
		$fid = intval($_POST['friend_id']);
		$query = "DELETE FROM `friends` WHERE `uid` = {$uid} AND `fid` = {$fid}";
		$result = mysqli_query($mysqli_link, $query);
		if ($result) {
			echo '1';
		} else {
			echo '0';
		}
	}
// ACCEPT INCOMING FRIEND QUERY
	if ($_REQUEST['act'] == 'friend_accept') {
		include_once 'db.php';
		$uid = intval($_POST['user_id']);
		$fid = intval($_POST['friend_id']);
		$query = "UPDATE `friends` SET `approved` = 1 WHERE `fid` = {$fid} AND `uid` = {$uid}";
		$result = mysqli_query($mysqli_link, $query);
		if ($result) {
			echo '1';
		} else {
			echo '0';
		}
	}
// REMOVE FRIEND
	if ($_REQUEST['act'] == 'friend_rmv') {
		include_once 'db.php';
		$uid = intval($_POST['user_id']);
		$fid = intval($_POST['friend_id']);
		$query = "UPDATE `friends` SET `approved` = 0 WHERE `fid` = {$fid} AND `uid` = {$uid}";
		$result = mysqli_query($mysqli_link, $query);
		if ($result) {
			echo '1';
		} else {
			echo '0';
		}
	}
// CANCEL EXTERNAL QUERY
if ($_REQUEST['act'] == 'friend_ext_query_cancel') {
    include_once 'db.php';
    $uid = intval($_POST['user_id']);
    $fid = intval($_POST['friend_id']);
    $query = "DELETE FROM `friends` WHERE `fid` = {$uid} AND `uid` = {$fid} AND `approved` = 0";
    $result = mysqli_query($mysqli_link, $query);
    if ($result) {
        echo '1';
    }
}
// CREATE QUERY
if($_REQUEST['act'] == 'add_to_friends'){
    include_once 'db.php';
    $uid = intval($_POST['user_id']);
    $fid = intval($_POST['friend_id']);
    $query = "INSERT INTO `friends`(`fid`, `uid`) VALUES({$uid}, {$fid})";
    $result = mysqli_query($mysqli_link, $query);
    if($result <> false){
        echo '1';
    } else {
        echo '0';
    }
}
// CREATE POST
if($_REQUEST['act'] == 'wall_post'){
    include_once 'db.php';
    $uid = intval($_POST['user_id']);
    $msg = $_POST['msg'];
    $time = time();
	if($_POST['file_uploaded'] != 'false'){
		$img = $_POST['file_uploaded'];
		$query = "INSERT INTO `wall`(`uid`, `content`, `time`, `img`) VALUES({$uid}, '{$msg}', {$time}, '{$img}')";
	} else {
		$query = "INSERT INTO `wall`(`uid`, `content`, `time`) VALUES({$uid}, '{$msg}', {$time})";
	}

    mysqli_query($mysqli_link, $query);
    echo '1';
}
// DELETE WALL POST
if($_REQUEST['act'] == 'wall_delete'){
    include_once 'db.php';
    $pid = intval($_POST['post_id']);
    $query = "DELETE FROM `wall` WHERE `id` = {$pid}";
    $result = mysqli_query($mysqli_link, $query);
    echo '1';
}
// ASYNC. LOAD WALL DATA
if($_REQUEST['act'] == 'wall_load'){
    include_once 'db.php';
    $uid = intval($_POST['user_id']);
    $offset = intval($_POST['offset']);
    $owner = ($_POST['owner'] <> 1) ? false : true;
    $query = "SELECT * FROM `wall` JOIN (SELECT `id` FROM `wall` WHERE `uid` = {$uid} ORDER BY `time` DESC LIMIT {$offset}, 30)
    AS `b` ON `b`.`id` = `wall`.`id`";
    $result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_assoc($result)){
        $msg = rawurldecode(base64_decode($row['content']));
        $time = date('c', $row['time']);
        $r_time = date('jS F o H:i', $row['time']);
        $wall = <<<WALL_POST
<div class="wall_post" onmouseover="$('#additional{$row['id']}').show()" onmouseout="$('#additional{$row['id']}').hide()" id="{$row['id']}">
<div class="wall_post_additional" id="additional{$row['id']}">
<div class="pull-left help-block"><time datetime="{$time}" data-livestamp="{$row['time']}" title="{$r_time}">{$r_time}</time></div>
WALL_POST;
        if($owner){
           $wall .= <<<ADDIT
<button type="button" class="close" id="wall_delete" onclick="wall_delete({$row['id']})"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
ADDIT;
        }
        $wall .= <<<FOOTER
</div>
<div class="wall_text">
{$msg}
</div>
</div>
FOOTER;
if(trim($wall) == ''){
    echo '0';
} else {
    echo $wall;
}
    }

}

	/* //////////////////// ONLINE /////////////////////*/
// ONLINE SYSTEM
if($_REQUEST['act'] == 'user_online'){
	include_once 'db.php';
	$uid = intval($_POST['user_id']);
	$time = time();
	$query = "INSERT INTO `online`(`id`, `time`) VALUES ({$uid}, {$time}) ON DUPLICATE KEY UPDATE `time` = {$time}";
	mysqli_query($mysqli_link, $query);
	echo '1';
}
if($_REQUEST['act'] == 'is_online'){
	include_once 'db.php';
	$uid = intval($_POST['user_id']);
	$time = time();
	$query = "SELECT * FROM `online` WHERE `id` = {$uid}";
	$result = mysqli_query($mysqli_link, $query);
	if($result <> FALSE){
		while($row = mysqli_fetch_assoc($result)){
			if(($time - $row['time'] > 900) or empty($row)){
				echo '0';
			} else {
				echo '1';
			}
		}
	} else {
		echo '0';
	}
}
if($_REQUEST['act'] == 'get_all_online_friends'){
	include_once 'db.php';
	header('Content-Type: application/json');
	$uid = intval($_REQUEST['user_id']);
	$query = "SELECT * FROM `online` WHERE `id` IN (SELECT `fid` FROM `friends` WHERE `uid` = {$uid})";
	$result = mysqli_query($mysqli_link, $query);
	$answer = [];
	$output = [];
	while($row = mysqli_fetch_assoc($result)){
		$answer[] = $row;
	}
	if(!empty($answer)){
		foreach($answer as $value){
			if(time() - $value['time'] <= 900){
				$output[]['id'] = $value['id'];
			}
		}
	}
	echo json_encode($output);

}
	// ADDITIONAL
	if($_REQUEST['act'] == 'send_verification'){
		include_once 'db.php';
		$email = preg_replace('/\s+/', '', trim($_REQUEST['username']));
		$hash = md5(time().$email);
		$sep = sha1(date('r', time()));
		$img = chunk_split(base64_encode(file_get_contents('res/twitter-bootstrap.jpg')));
		$subject = 'Verifying registration';
		$headers = "From: robot@jenott.net\r\n
		Content-Type: multipart/mixed; boundary=\"PHP-mixed-{$sep}\"";
		$body = <<< LETTER
--PHP-mixed-{$sep}
Content-Type: multipart/alternative; boundary="PHP-alt-{$sep}"
--PHP-alt-{$sep}
Content-Type: text/plain
Hello, my friend.
You have used that email address for registration on Jenott.net.
Are you siriously? If you are - just visit the link below:
http://jenott.net/engine.php?act=verify_activate&email={$email}&hash={$hash}

If you haven't do it - just ignore this email ^^

Jenott,
Good luck!
--PHP-alt-{$sep}
Content-Type: multipart/related; boundary="PHP-related-{$sep}"
--PHP-related-{$sep}
Content-Type" text/html

<img src="cid:PHP-CID-{$sip}" /><br>
<h2>Hello, my friend</h2>
<p>You have used that email address for registration on Jenott.net.<br>
Are you siriously? If you are - just visit the link below:<br>
<a href="http://jenott.net/engine.php?act=verify_activate&email={$email}&hash={$hash}">I'm link</a><br><br>

If you haven't do it - just ignore this email ^^<br><br>

Jenott,<br>
Good luck!</p>

--PHP-related-{$sep}
Content-Type:image/jpg
Content-Transfer-Encoding: base64
Content-ID: <PHP-CID-{$sep}>

{$img}
--PHP-related-{$sep}--
--PHP-alt-{$sep}--
--PHP-mixed-{$sep}--
LETTER;
		mail($email, $subject, $body, $headers);
		$query = "INSERT INTO `verifications` VALUES('{$hash}', '{$email}') ON DUPLICATE KEY UPDATE `hash` = '{$hash}'";
		mysqli_query($mysqli_link, $query);
	}
	if($_REQUEST['act'] == 'verify_activate'){
		include_once 'db.php';
		$email = preg_replace('/\s+/', '', trim($_GET['email']));
		$hash = preg_replace('/\s+/', '', trim($_GET['hash']));
		$query = "SELECT * FROM `verifications` WHERE `email` = '{$email}' LIMIT 1";
		$result = mysqli_query($mysqli_link, $query);
		if(empty($result) or !$result){
			header('Location: /wbr/'.'index.php?error=7');
		}
		$verif = [];
		while($row = mysqli_fetch_assoc($result)){
			$verif = $row;
		}
		if($verif['hash'] == $hash){
			$query = "UPDATE `profiles` SET `active` = 1 WHERE `email` = '{$email}'";
			mysqli_query($mysqli_link, $query);
			$query = "DELETE FROM `verifications` WHERE `email` = {$email}";
			mysqli_query($mysqli_link, $query);
			header('Location: /wbr/'.'index.php?error=10'); // NOT ERROR!!
		} else {
			header('Location: /wbr/'.'index.php?error=8');
		}
	}
	if($_REQUEST['act'] == 'check_email_exists'){
		include_once 'db.php';
		$email = $_POST['email']; //@TODO make an email verification
		$query = "SELECT * FROM `profiles` WHERE `email` = '{$email}'";
		$result = mysqli_fetch_assoc(mysqli_query($mysqli_link, $query));
		if(empty($result)){
			echo '1';
		} else {
			echo '0';
		}
	}
	//
	//----------------REGISTRATION---------------//
	if($_REQUEST['act'] == 'register'){
		$email = $_POST['email'];
		$password = new_password_generate($email, $_POST['password']);
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$birth = strtotime($_POST['birth']);
		$timezone = $_POST['timezone'];
		$username = md5($email);
		$gender = (!empty($_POST['gender'])) ? $_POST['gender'] : '';
		$query = "INSERT INTO `profiles`(`email`, `password`, `first_name`, `second_name`, `timezone`, `username`)
		VALUES('{$email}', '{$password}', '{$first_name}', '{$last_name}', '{$timezone}', '{$username}')";
		//CREATE NEW USER IN USERDATA
		$id_query = "SELECT `id` FROM `profiles` WHERE `email` = '{$email}' LIMIT 1";
		include_once 'db.php';
		$result = mysqli_query($mysqli_link, $query) or die(mysqli_error($mysqli_link));
		$id_res = mysqli_query($mysqli_link, $id_query) or die(mysqli_error($mysqli_link));
		$id = mysqli_fetch_array($id_res);
		$userdata_query = "INSERT INTO `userdata`(`uid`, `birth`, `gender`) VALUES('{$id[0]}', '{$birth}', '{$gender}')";

		$userdata_result = mysqli_query($mysqli_link, $userdata_query) or die(mysqli_error($mysqli_link));
		if(!$result or !$userdata_result){
			header('Location: /wbr/index.php?error=');
		} else {
			header('Location: /wbr/index.php?success=2');
		}
	}

	//

function compare_hash($login, $password, $db_password){
	$login_length = strlen($login);
	$additional_bit = abs(round($login_length / exp(1), 0));
	$password_array = str_split(md5($password));
	unset($password_array[$additional_bit]);
	$password_array[] = $additional_bit;
	$result_password = md5(implode('', $password_array));
	return ($result_password == $db_password)? true : false;
}
function new_password_generate($login, $password){
	$password = md5($password);
	$pass_array = str_split($password);
	$login_length = strlen($login);
	$chr_rem =  abs(round($login_length / exp(1), 0));
	unset($pass_array[intval($chr_rem)]);
	$pass_array[] = $chr_rem;
	$result_password = implode('', $pass_array);
	return md5($result_password);
}