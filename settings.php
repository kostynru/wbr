<?php
	define('INTERNAL', true);
$page = 'settings';
	include_once 'is_logged.php';
	if(!$logged_in){
		header('Location: /wbr/engine.php?act=logout&error=3');
	}
//*******************************************************************
session_start();
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
            ?>
            <form action="settings.php?set=gravataremail" method="POST"><input type="text" name="gravataremail"/>
                <button type="submit">Send</button>
            </form>
        <?php
        } else {
            $email = md5(strtolower($_POST['gravataremail']));
            $mysqli_link = mysqli_connect('localhost', 'root', '', 'wbr');
            $query = "UPDATE `settings` SET `gravatar_id` = '{$email}' WHERE `uid` =
        '{$_SESSION['id']}'";
            $result = mysqli_query($mysqli_link, $query) or die(mysqli_error($mysqli_link));
            header('Location: /wbr/settings.php?target=personal&msg=success');
        }
        break;
}
include_once 'header.php';
if (!isset($_COOKIE['PHPSESSID'])) {
    $query = "SELECT * FROM `profiles` WHERE `id` = '{$session['uid']}'";
    $result = mysqli_query($mysqli_link, $query);
    $sess_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sess_data = $row;
    }
}
session_start();
$_SESSION = (!empty($sess_data) ? $sess_data : $_SESSION);

$mysqli_link = mysqli_connect('localhost', 'root', '', 'wbr');


if ($_GET['msg'] == 'success') {
    echo "Preferences applied successful<br>";
}
//***************************************************************
$query = "SELECT `gravatar_id` FROM `settings` WHERE `uid` = '{$_SESSION['id']}'";
$result = mysqli_query($mysqli_link, $query); //TODO Fix
$settings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $settings = $row;
}
?>
<br>
<h4>Profile</h4>
<hr>
<div id="profile_stg">
    <?php
    if ($settings['gravatar_id'] <> md5(strtolower($_SESSION['email']))) {
        ?>
        <h5>
            <small>Gravatar preferences</small>
        </h5>
        We can load your personal data and avatar from Gravatar using email.<br>
        Does <strong><?php echo $_SESSION['email']; ?></strong> your email on Gravatar? <a
            href="settings.php?set=gravatarget" target="_self">Yes</a>
        or <a href="settings.php?set=gravataremail">No, I want to set another one</a>
    <?php } else { ?>
        We'd already loaded your avatar from Gravatar
    <?php } ?>
</div>
<hr>
<h4>Messages</h4>
<hr>
<div id="im_stg">

</div>
<hr>
<h4>Wall</h4>
<hr>
<div id="wall_stg">

</div>