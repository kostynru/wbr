<?php
	if (!defined('INTERNAL')) {
		die('Access denied');
	}
	include_once 'db.php';
	if ($page <> 'register' and $page <> 'index' and $page <> 'credits') {
		if (!isset($_COOKIE['PHPSESSID'])) {
			$query = "SELECT * FROM `profiles` WHERE `id` = '{$_COOKIE['j_id']}'";
			$result = mysqli_query($mysqli_link, $query);
			$sess_data = [];
			while ($row = mysqli_fetch_assoc($result)) {
				$sess_data = $row;
			}
		}
		session_start();
		$_SESSION = (!empty($sess_data) ? $sess_data : $_SESSION);
		if (empty($_SESSION) and $page <> 'register' and $page <> 'index' and $page <> 'credits') {
			$logged_in = FALSE;
		}
		session_write_close();
	}
	session_start();
	if (!isset($_COOKIE['j_id']) or empty($_COOKIE['j_id'])) {
		setcookie('j_id', $_SESSION['id'], 0);
	}
	//////////////
	if (!isset($_COOKIE['j_lch']) or (base64_decode($_COOKIE['j_lch']) > 180)) {
		if (isset($_COOKIE['session'])) {
			$uid = $_SESSION['id'];
			if (empty($_SESSION['id'])) {
				$logged_in = FALSE;
			} else {
				$query = "SELECT *, `sessions`.`id` AS `sid` FROM `sessions` JOIN `profiles` ON `profiles`.`id` = `sessions`.`uid` WHERE `uid` = {$uid} LIMIT 1";
				$result = mysqli_query($mysqli_link, $query);
				if (empty($result) or !$result) {
					$logged_in = FALSE;
				} else {
					$session_inf = [];
					while ($row = mysqli_fetch_assoc($result)) {
						$session_inf = $row;
					}
					if ($_COOKIE['session'] <> $session_inf['sid'] and (time() >= $session_inf['expire'])) {
						// Now, try to restore cookie if ip is the same


						if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
							$ip = $_SERVER['HTTP_CLIENT_IP'];
						} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
							$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
						} else {
							$ip = $_SERVER['REMOTE_ADDR'];
						}


						$restored_id = md5($session_inf['email'] . $ip . strlen($session_inf['email']));
						if ($restored_id <> $session_inf['sid']) {
							$logged_in = FALSE;
						} else {
							$time2store = $session_inf['expire'];
							setcookie('session', $restored_id, $time2store);
							$logged_in = TRUE;
						}
					} else {
						$logged_in = TRUE;
					}
				}
			}

		} else {
			$logged_in = FALSE;
		}
		setcookie('j_lch', base64_encode(time()));
	} else {
		setcookie('j_lch', base64_encode(time()));
		$logged_in = TRUE;
	}