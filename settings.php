<?php
	define('INTERNAL', true);
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
    <div class="tab-pane active" id="st_personal"><br><hr>
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
    <div class="tab-pane" id="st_privacy"><br><hr><button class="btn btn-info" id="privacy_st_save">Save</button>
    </div>

    <!-- Additional settings -->
    <div class="tab-pane" id="st_additional"><br><hr><button class="btn btn-info" id="additional_st_save">Save</button>
    </div>
</div>
    </div>
<div class="col-md-2"></div>