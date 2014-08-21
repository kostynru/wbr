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
        <br>
        <!-- Personal settings -->
        <form class="tab-pane active" id="st_personal">
            <div id="avatar_group">
                <img src="http://gravatar.com/avatar/<?php echo $gravatar_id ?>?d=mm&s=200">
                We have linked your Gravatar with <?php echo $settings['email'] ?>
            </div>
            <br>
            <div class="form-group" id="first_name_group">
                <label for="first_name">First name</label>
                <input type="text" id="first_name" class="form-control setting" value="<?php echo $settings['first_name']; ?>">
            </div>
            <div class="form-group" id="second_name_group">
                <label for="second_name">Second name</label>
                <input type="text" id="second_name" class="form-control setting"
                       value="<?php echo $settings['second_name']; ?>">
            </div>
            <div class="form-group" id="city_group">
                <label for="city">City</label>
                <input type="text" id="city" class="form-control setting"
                       value="<?php echo $settings['city']; ?>">
            </div>
            <div class="form-group" id="skype_group">
                <label for="skype">Skype</label>
                <input type="text" id="skype" class="form-control setting" value="<?php echo $settings['skype']; ?>">
            </div>
            <div class="form-group" id="twitter_group">
                <label for="twitter">Twitter</label>
                <div class="input-group">
                    <div class="input-group-addon">@</div>
                    <input type="text" id="twitter" class="form-control setting" value="<?php echo $settings['twitter']; ?>">
                </div>
            </div>
            <div class="form-group" id="birthday_group">
                <label for="birthday">Birthday</label>
                <input type="date" id="birthday" name="birth" class="form-control setting" value="<?php echo date('Y-m-d', $settings['birth']) ?>">
            </div>
            <div class="form-group" id="username_group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control setting" name="username" value="<?php echo $settings['username'] ?>" pattern="[a-zA-Z0-9]{3,20}">
                <span class="text-muted">*Your page might be found by this username at (http://jenott.net/show/{username}).
                It must contain only latin characters and numbers without spaces. Also, the length must have more than four characters.</span>
            </div>
            <hr>
            <button class="btn btn-info" type="submit" id="personal_st_save">Save</button>
        </form>

        <!-- Wall settings -->
        <br>

        <form class="tab-pane" id="st_wall">
            <div class="checkbox">
                <label>
                    <input type="checkbox" class="setting" id="wall_enabled" <?php echo ($settings['wall_enabled'] == '1')? 'checked' : '' ?>> Enable wall
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" class="setting" id="wall_everybody" <?php echo ($settings['wall_everybody'] == '1')? 'checked' : '' ?>> Everybody can see my wall (otherwise - only friends)
                </label>
            </div>
            <hr>
            <button class="btn btn-info" type="submit" id="wall_st_save">Save</button>
        </form>

        <!-- Privacy settings -->
        <form class="tab-pane" id="st_privacy">
            <div class="checkbox">
                <label>
                    <input type="checkbox" class="setting" id="messages_everybody" <?php echo ($settings['messages_everybody'] == '1')? 'checked' : '' ?>> Only friends can send me messages
                </label>
            </div>
            <hr>
            <button class="btn btn-info" type="submit" id="privacy_st_save">Save</button>
        </form>

        <!-- Additional settings -->
        <form class="tab-pane" id="st_additional">
            <div class="page_background">

            </div>
            <hr>
            <button class="btn btn-info" type="submit" id="additional_st_save">Save</button>
        </form>
    </div>
</div>
<div class="col-md-2"></div>
<input type="hidden" id="uid" value="<?php echo $settings['id'] ?>">