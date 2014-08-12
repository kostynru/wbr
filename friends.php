<?php
define('INTERNAL', true);
$page = 'friends';
//************************************************
	include_once 'is_logged.php';
	if(!$logged_in){
		header('Location: /wbr/engine.php?act=logout&error=3');
	}
//**************************************************
include_once 'header.php';
$query = "SELECT * FROM `friends` JOIN `profiles` ON `friends`.`fid` = `profiles`.`id` WHERE `uid` = {$_SESSION['id']} AND
`approved` = 1";
$result = mysqli_query($mysqli_link, $query);
$query1 = "SELECT * FROM `friends` JOIN `profiles` ON `profiles`.`id` = `friends`.`fid` WHERE `friends`.`approved` = 0 AND
    `friends`.`uid` = {$_SESSION['id']}";
$result1 = mysqli_query($mysqli_link, $query1);
if ($result1) {
    $queries = [];
    while ($row = mysqli_fetch_assoc($result1)) {
        $queries[] = $row;
    }
} else {
    $queries = [];
}
$nmb_queries = count($queries);
echo '<div class="col-md-3">';
echo '<ul class="nav nav-pills nav-stacked" id="friends_nav">
<li class="active"><a href="#" id="page_list">Friends list</a></li>
<li><a href="#" id="page_queries">Queries';
echo ($nmb_queries > 0) ? '<span class="badge">' . $nmb_queries . '</span>' : '';
echo '</a></li>
<li><a href="#" id="page_ext_qries">External queries</a></li>
</ul>';

echo '</div>';
echo '<div class="col-md-6" id="friends">';
// Friends list wrapper
echo '<div id="friendlist_wrap">';
while ($row = mysqli_fetch_assoc($result)) {
    $grav_url = md5($row['email']);
    if (strlen($row['about']) > 20) {
        $about = substr($row['about'], 0, 20) . '...';
    } else {
        $about = $row['about'];
    }
    echo <<< MSG
<div class="media" onmouseover="$('#im_init{$row['fid']}').show()" onmouseout="$('#im_init{$row['fid']}').hide()" id="friend{$row['fid']}">
  <a class="pull-left" href="show/{$row['fid']}">
    <img class="media-object" src="http://gravatar.com/avatar/{$grav_url}?s=80&d=mm" alt="">
  </a>
  <div class="media-body">
    <h4 class="media-heading" id="friend{$row['fid']}"><a href="show/{$row['fid']}">{$row['first_name']} {$row['second_name']}</a></h4>
    <div class="pull-right" style="display:none;" id="im_init{$row['fid']}"><a href="#" data-toggle="modal" data-target="#im_send"
    onclick="$('#fid').val({$row['fid']})">Write message</a></div>
    <div class="help-block">{$about}<br><div id="rm_fr_controls{$row['fid']}"><a href="#"
    onclick="remove_friend_accept('accepting', {$row['fid']})" class="text-muted">Remove friend</a></div>
    <div id="rm_fr_accept{$row['fid']}" style="display: none;">Are you sure? <a href="#" onclick="remove_friend({$_SESSION['id']}, {$row['fid']});">Yes</a> |
    <a href="#" onclick="remove_friend_accept('decline', {$row['fid']})">Nope!</a></div></div>
  </div>
</div>
MSG;
}
echo '</div>';
// Internal queries wrapper
echo '<div id="queries_wrap" style="display: none">';
if (!empty($queries)) {
    foreach ($queries as $value) {
        $grav_url = md5($value['email']);
        if (strlen($value['about']) > 20) {
            $about = substr($value['about'], 0, 20) . '...';
        } else {
            $about = $value['about'];
        }
        echo <<< QRY
<div class="media">
  <a class="pull-left" href="show/{$value['fid']}">
    <img class="media-object" src="http://gravatar.com/avatar/{$grav_url}?s=80&d=mm" alt="">
  </a>
  <div class="media-body">
    <h4 class="media-heading" id="person{$value['fid']}"><a href="show/{$value['fid']}">{$value['first_name']} {$value['second_name']}</a></h4>
    <div class="help-block">{$about}</div>
    <div id="qris_controls{$value['fid']}"><a href="#" onclick="friend_query_accept({$_SESSION['id']}, {$value['fid']})">Accept</a> or <a href="#"
    onclick="friend_query_decline({$_SESSION['id']}, {$value['fid']})">Decline</a></div>
  </div>
</div>
QRY;
    }
}
echo '</div>';
// EXT queries wrapper
echo '<div id="ext_qries_wrap" style="display: none">';
$ext_query = "SELECT * FROM `friends` JOIN `profiles` ON `friends`.`uid` = `profiles`.`id` WHERE `friends`.`fid`
        = {$_SESSION['id']} AND `friends`.`approved` = 0";
$ext_result = mysqli_query($mysqli_link, $ext_query) or die(mysqli_error($mysqli_link));
if ($ext_result <> false and !empty($ext_result)) {
    while ($row = mysqli_fetch_assoc($ext_result)) {
        $ext_grav_url = md5($row['email']);
        if (strlen($row['about']) > 20) {
            $about = substr($row['about'], 0, 20) . '...';
        } else {
            $about = $row['about'];
        }
        echo <<< EXT_Q
<div class="media">
  <a class="pull-left" href="show/{$row['fid']}">
    <img class="media-object" src="http://gravatar.com/avatar/{$ext_grav_url}?s=80&d=mm" alt="">
  </a>
  <div class="media-body">
    <h4 class="media-heading" id="query{$row['fid']}"><a href="show/{$row['uid']}">{$row['first_name']} {$row['second_name']}</a></h4>
    <div class="help-block">{$about}</div>
    <div id="qris_controls{$row['fid']}"><a href="#" onclick="friend_ext_query_cancel({$_SESSION['id']}, {$row['uid']})">Cancel</a></div>
  </div>
</div>
EXT_Q;
    }
} else {
    echo 'Nothing to show';
}
echo '</div>';
//End of wrappers
echo '</div>';
?>
    <div class="col-md-2">

    </div>
<div class="modal fade" id="im_send">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><span class="sr-only">Close</span>
                <h4 class="modal-title">Type your message here</h4>
            </div>
            <div class="modal-body">
                <textarea class="form-control" id="message"></textarea><br>
                <input type="hidden" id="uid" value="<?php echo $_SESSION['id'] ?>">
                <input type="hidden" id="fid" value="">

                <div class="pull-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="im_send_ctrl">Send</button>
                </div>
            </div>
        </div>
    </div>

<?php

