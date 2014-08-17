<?php
	define('INTERNAL', TRUE);
$page = 'settings';
	include_once 'is_logged.php';
	if(!$logged_in){
		header('Location: /wbr/engine.php?act=logout&error=3');
	}
//*******************************************************************
include_once 'header.php';
session_start();/*
switch ($_GET['set']) {
    case 'gravatarget':
        $mysqli_link = mysqli_connect('localhost', 'root', '', 'wbr');
        $query = "UPDATE `settings` SET `gravatar_id` = '" . md5(strtolower($_SESSION['email'])) . "' WHERE `uid` =
        '{$_SESSION['id']}'";
        $result = mysqli_query($mysqli_link, $query) or die(mysqli_error($mysqli_link));
        header('Location: /wbr/settings.php?target=personal&msg=success');
        break;
    case 'gravataremail':
        if (!isset($_POST['gravataremail'])) {
            */?><!--
            <form action="settings.php?set=gravataremail" method="POST"><input type="text" name="gravataremail"/>
                <button type="submit">Send</button>
            </form>
        --><?php
	/*        } else {
				$email = md5(strtolower($_POST['gravataremail']));
				$mysqli_link = mysqli_connect('localhost', 'root', '', 'wbr');
				$query = "UPDATE `settings` SET `gravatar_id` = '{$email}' WHERE `uid` =
			'{$_SESSION['id']}'";
				$result = mysqli_query($mysqli_link, $query) or die(mysqli_error($mysqli_link));
				header('Location: /wbr/settings.php?target=personal&msg=success');
			}
			break;
	}*/
	//***************************************************************

	$query = "SELECT * FROM `profiles` JOIN `settings` ON `settings`.`uid` = `profiles`.`id`
JOIN `userdata` ON `userdata`.`uid` = `profiles`.`id` WHERE `profiles`.`id` = {$_SESSION['id']} LIMIT 1";
	$settings = [];
	$result = mysqli_query($mysqli_link, $query);
	$settings = mysqli_fetch_assoc($result);
	/*
	 * Settings structure
	[id]
	[username]
	[password]
	[first_name]
	[second_name]
	[email]
	[about]
	[timezone]
	[gravatar_id]
	[wall_everybody]
	[messages_everybody]
	[birth]
	[city]
	[gender]
	[skype]
	[twitter]
	 */
	$gravatar_id = md5($settings['email']);
?>
<div class="col-md-2"></div>
<div class="col-md-8">
	<ul class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#st_personal" role="tab" data-toggle="tab">Personal data</a></li>
		<li><a href="#st_wall" role="tab" data-toggle="tab">Wall</a></li>
		<li><a href="#st_privacy" role="tab" data-toggle="tab">Privacy</a></li>
		<li><a href="#st_additional" role="tab" data-toggle="tab">Additional</a></li>
	</ul>

	<div class="tab-content">
		<!-- Personal settings -->
		<br>
		<div id="avatar_group">
			<img src="http://gravatar.com/avatar/<?php echo $gravatar_id ?>?d=mm&s=200">
		</div><br>
		<div class="tab-pane active" id="st_personal">
			<div class="form-group" id="first_name_group">
				<label for="first_name">First name</label>
				<input type="text" id="first_name" class="form-control" value="<?php echo $settings['first_name']; ?>">
			</div>
			<div class="form-group" id="second_name_group">
				<label for="second_name">Second name</label>
				<input type="text" id="second_name" class="form-control" value="<?php echo $settings['second_name']; ?>">
			</div>
			<div class="form-group" id="skype_group">
				<label for="skype">Skype</label>
				<input type="text" id="skype" class="form-control" value="<?php echo $settings['skype']; ?>">
			</div>
			<div class="form-group" id="twitter_group">
				<label for="twitter">Twitter</label>
				<input type="text" id="twitter" class="form-control" value="<?php echo $settings['twitter']; ?>">
			</div>
			<div class="form-group" id="birthday_group">
				<label for="birthday">Birthday</label>
				<input type="date" id="birthday" class="form-control" name="birth" value="<?php echo date('Y-m-d', $settings['birth']) ?>">
			</div>
			<hr>
			<button class="btn btn-info" id="personal_st_save">Save</button>
		</div>

		<!-- Wall settings -->
		<br>

		<div class="tab-pane" id="st_wall">
			<div class="checkbox">
				<label>
					<input type="checkbox"> Everybody can see my wall (if not - only friends)
				</label>
			</div>
			<hr>
			<button class="btn btn-info" id="wall_st_save">Save</button>

		</div>

		<!-- Privacy settings -->
		<div class="tab-pane" id="st_privacy">
			<div class="checkbox">
				<label>
					<input type="checkbox"> Only friends can send me messages
				</label>
			</div>
			<hr>
			<button class="btn btn-info" id="privacy_st_save">Save</button>
		</div>

		<!-- Additional settings -->
		<div class="tab-pane" id="st_additional">
			<hr>
			<button class="btn btn-info" id="additional_st_save">Save</button>
		</div>
	</div>
</div>
<div class="col-md-2"></div>