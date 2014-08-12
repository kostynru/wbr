<?php
	define('INTERNAL', true);
$page = 'im';
//************************************************
	include_once 'is_logged.php';
	if(!$logged_in){
		header('Location: /wbr/engine.php?act=logout&error=3');
	}
//**************************************************
include_once 'header.php';
if (empty($_GET['show'])) {
    ?>
<div class="col-md-2"></div>
    <div class="col-md-8">
    <a href="#" data-toggle="modal" data-target="#im_send_form">New message</a><a href="#" class="pull-right" onclick="mark_all_as_read(<?php echo $_SESSION['id'] ?>)">Mark all as read</a>
    <?php
    /*$query = "SELECT * FROM `ims` JOIN `profiles` ON `ims`.`sid` = `profiles`.`id` WHERE `ims`.`uid` = {$_SESSION['id']}";*/
	    $query = "
	    SELECT * FROM (SELECT * FROM `ims` JOIN `profiles` ON `profiles`.`id` = `ims`.`uid` or `profiles`.`id` = `ims`.`sid`
	    WHERE (`uid` = {$_SESSION['id']} OR `sid` = {$_SESSION['id']}) AND `profiles`.`id` <> {$_SESSION['id']} ORDER BY `ims`.`uid`, `ims`.`time` DESC, `ims`.`mid`) x
	     WHERE (x.`uid` =  {$_SESSION['id']} AND x.`sid` <> {$_SESSION['id']}) OR (x.`uid` <> {$_SESSION['id']} AND x.`sid` = {$_SESSION['id']})
	     GROUP BY `id`";
    $result1 = mysqli_query($mysqli_link, $query) or die(mysqli_error($mysqli_link));
    while ($row = mysqli_fetch_assoc($result1)) {
        $grav_url = md5($row['email']);
        if (strlen($row['text']) > 50) {
            $msg = substr($row['text'], 0, 50) . '...';
        } else {
            $msg = $row['text'];
        }
        $time = date('j F Y H:i', ($row['time']));
        $unread = (($row['checked'] == 0) and $row['sid'] <> ($_SESSION['id']))? '<span class="glyphicon glyphicon-comment unread"></span>' : '';
        echo <<<EOT
<div class="media">
  <a class="pull-left" href="/wbr/dialog/{$row['id']}">
    <img class="media-object" src="http://gravatar.com/avatar/{$grav_url}?d=mm&s=64" alt="">
  </a>
  <div class="media-body">
    <h4 class="media-heading" id="friend{$row['id']}"><a href="/wbr/show/{$row['id']}">{$row['first_name']} {$row['second_name']}</a> {$unread}</h4>
    <div class="text-muted">{$time}</div>
EOT;
	    if($row['sid'] == $_SESSION['id']){
            echo '<a href="/wbr/dialog/'.$row['id'].'" class="external_msg msg_inline">'.$msg.'</a>';
	    } else {
		    echo '<a href="/wbr/dialog/'.$row['id'].'" class="msg_inline">'.$msg.'</a>';
	    }
	    echo '</div></div>';

    } print_r($res);
} elseif(!empty($_GET['show'])) {
	$id = intval($_GET['show']);
    ?>
    <div class="col-md-2"></div>
    <div class="col-md-8">
	<div class="msg_input">
		<div class="input_area">
			<input type="text" id="msg_input_text" class="msg_input_group form-control" />
		</div>
			<br>
		<button id="msg_send_im" class="btn btn-info pull-right" onclick="dialog_send_im(<?php echo $_SESSION['id'].','.$id ?>)">Send</button>
	</div><br><br>
    <div class="dialog">
	<input type="hidden" id="cid" value="<?php echo $id; ?>">
    <?php
    $query = "SELECT * FROM `ims` JOIN `profiles` ON `profiles`.`id` = `ims`.`sid` WHERE
((`ims`.`sid` = {$id} AND `ims`.`uid` = {$_SESSION['id']}) OR (`ims`.`uid` = {$id} AND `ims`.`sid` = {$_SESSION['id']}))
ORDER BY `ims`.`time` DESC";
    /*$query = "(SELECT * FROM `ims` JOIN `profiles` ON `profiles`.`id` = {$_SESSION['id']} WHERE `ims`.`sid` = {$id} AND `ims`.`uid` = {$_SESSION['id']}) UNION
(SELECT * FROM `ims` JOIN `profiles` ON `profiles`.`id` = {$id} WHERE `ims`.`uid` = {$id} AND `ims`.`sid`  = {$_SESSION['id']} ) ORDER BY `time`";*/
    $result = mysqli_query($mysqli_link, $query);
    $msg_data = [];
    if($result and !empty($result)){
        while($row = mysqli_fetch_assoc($result)){
            $msg_data[] = $row;
        }

    $msg_prev = false;
    $updated = false;
    foreach($msg_data as $value){
	    $hr = '';
	    if(!$msg_prev){
		    $msg_prev = $value['sid'];
	    } else {
		    if($msg_prev <> $value['sid']){
			    $hr = '<hr>';
		    }
		    $msg_prev = $value['sid'];
	    }
        if(!$updated){
            $query1 = "UPDATE `ims` SET `checked` = 1 WHERE (`uid` = {$_SESSION['id']} AND `sid` = {$value['uid']}) OR
            (`uid` = {$value['uid']} AND `sid` = {$_SESSION['id']})";
            mysqli_query($mysqli_link, $query1) or die(mysqli_error($mysqli_link));
            $updated = true;
        }
        if((date('Y') - date('Y', $value['time'])) <> 0){
            if((date('n') - date('n', $value['time'])) <> 0){
                if((date('j') - date('j', $value['time'])) <> 0){
                    $time = date('j F Y H:i', $value['time']);
                }
            }
        } else {
            $time = date('H:i', $value['time']);
        }
        if($value['sid'] <> $_SESSION['id']){
            $pull = 'pull-left';
            $align = 'left';
        } else {
            $pull = 'pull-right';
            $align = 'right';
        }
        echo $hr.'<div class="media im_list" id="msg'.$value['mid'].'" sid="'.$value['sid'].'">';
            $grav_msg_url = md5($value['email']);
            echo '<a class="'.$pull.'" href="/wbr/show/'.$value['sid'].'">
    <img class="media-object img-circle" src="http://gravatar.com/avatar/'.$grav_msg_url.'?d=mm&s=60" alt="">
  </a>';

        echo '<div class="media-body '.$pull.'">';
        echo '<h4 class="media-heading" style="text-align: '.$align.'" >'.$value['first_name'].'</h4><br>';
        echo '<div class="media-text '.$pull.'" style="text-align: '.$align.'">'.$value['text'].'</div>';

        echo '</div></div>';
        }

              } else {
        echo '<p class="text-center">Your dialog would be shown here ^^</p>';
    }





}
?>
    </div>
    </div>
<div class="col-md-2"></div>



<?php if(!isset($_GET['show']) or empty($_GET['show'])): ?>
<div class="modal fade" id="im_send_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><span class="sr-only">Close</span>
                <h4 class="modal-title">Type your message here</h4>
            </div>
            <div class="modal-body">
                <textarea class="form-control" id="message"></textarea><br>
	            <div id="select_target_id"></div>
               <!-- --><?php
/*                $query = "SELECT * FROM `friends` JOIN `profiles` ON `profiles`.`id` = `friends`.`fid` WHERE
                        `uid` = {$_SESSION['id']} AND `friends`.`approved` = 1";
                $result = mysqli_query($mysqli_link, $query);
                if (!empty($result) and $result <> false) {
                    echo '<select class="form-control" id="fid">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option id="o' . $row['fid'] . '" value="'.$row['fid'].'">' . $row['first_name'] . ' ' . $row['second_name'] . '</option>';
                    }
                    echo '</select>';
                }
                */?>
                <input type="hidden" id="uid" value="<?php echo $_SESSION['id'] ?>">
            </div><br>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="im_send"  onclick="im_send(<?php echo $_SESSION['id'] ?>)">Send</button>
            </div>
        </div>
    </div>
</div>
<script>
	var ddData = [
	<?php $query = "SELECT * FROM `friends` JOIN `profiles` ON `profiles`.`id` = `friends`.`fid` WHERE
	`uid` = {$_SESSION['id']} AND `friends`.`approved` = 1";
	$result = mysqli_query($mysqli_link, $query);
	if (!empty($result) and $result <> false) {
		while($row = mysqli_fetch_assoc($result)){
		$grav_img = md5($row['email']);
		echo <<< OUT
{
text: "{$row['first_name']} {$row['second_name']}",
description: "{$row['about']}",
value: {$row['fid']},
imageSrc: "http://gravatar.com/avatar/{$grav_img}?s=32&d=mm"
},\n
OUT;
}
	}
	?>
	];
	$('#select_target_id').ddslick({
		data: ddData,
		width: 300,
		selectText: 'Select your friend...'
	})
</script>
<?php endif; ?>