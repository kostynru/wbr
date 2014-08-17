<?php
define('INTERNAL', true);
$page = 'profile';
//************************************************
	include_once 'is_logged.php';
	if(!$logged_in){
		header('Location: /wbr/engine.php?act=logout&error=3');
	}
//**************************************************
	ob_start();
include_once 'header.php';
?>
<div id="profile">
<?php
if (isset($_GET['show']) and !empty($_GET['show']) and($_GET['show'] <> $_SESSION['id'] and $_GET['show'] <> $_SESSION['username'])) {
    $query = "SELECT * FROM `friends` WHERE `uid` = {$profile['id']} AND `fid` = {$_SESSION['id']}";
    $result = mysqli_query($mysqli_link, $query);
    $fr_status = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $fr_status = $row;
    }
    ?>
    <div class="col-md-3" id="avatar_pr">
        <img src="http://gravatar.com/avatar/<?php echo md5($profile['email']) ?>?d=mm&s=200" alt="<?php echo $profile['first_name'] ?>" id="profile_img"/>
	    <br>
	    <?php
		    $friends_query = "SELECT * FROM `friends` WHERE `uid` = {$profile['id']} AND `approved` = 1";
		    $fr_res = mysqli_query($mysqli_link, $friends_query);
			    $friends = [];
			    while($row = mysqli_fetch_assoc($fr_res)){
				    $friends[] = $row;
			    }
                if(!empty($friends)){
			        echo '<span class="text-muted">'.count($friends).' friends</span>';
                }

	    ?>
	    <br><?php
        if (empty($fr_status)) {
            echo '<br><button type="button" class="btn btn-primary " id="send_query" onclick="add_to_friends(' . $_SESSION['id'] . ', ' . $profile['id'] . ')">Add to friends</button>';
        }
        if ($fr_status['approved'] == '0') {
            echo '<br><button type="button" class="btn btn-primary " id="cancl_query" onclick="cancel_query(' . $_SESSION['id'] . ', ' . $profile['id'] . ')"><span class="glyphicon glyphicon-remove"></span> Cancel query</button>';
        } elseif($fr_status['approved'] == '1') {
            $friend = true;
        }
        if($friend){
            echo '<br><a href="/wbr/im.php?show='.$fr_status['fid'].'" class="btn btn-primary">Send message</a>';
        }
        ?>
    </div>
    <div class="col-md-6" id="wall">
        <input type="hidden" id="user_id" value="<?php echo $profile['id'] ?>" />
        <div id="wall_content">
            <?php
            $wall_pref_q = "SELECT * FROM `wall_preferences` WHERE `uid` = {$profile['id']}";
            $wall_pref_r = mysqli_query($mysqli_link, $wall_pref_q);
            $wall_preferences = [];
            while($row = mysqli_fetch_assoc($wall_pref_r)){
                $wall_preferences = $row;
            }
            if($wall_preferences['enabled'] == '1'){

                $wall_query = "SELECT * FROM `wall` WHERE `uid` = {$profile['id']} ORDER BY `time` DESC LIMIT 30";
                $wall_result = mysqli_query($mysqli_link, $wall_query);
                $wall_array = [];
            while($row = mysqli_fetch_assoc($wall_result)){
                $wall_array[] = $row;
            }
            if(!empty($wall_array)){
                foreach($wall_array as $row){
                    $likes_query = "SELECT * FROM `wall_likes` WHERE `uid` = {$_SESSION['id']} AND `pid` = {$row['id']}";
                    $likes_result = mysqli_query($mysqli_link, $likes_query);
                    if(mysqli_num_rows($likes_result) > 0){
                        $likes = '<div class="like liked" id="like'.$row['id'].'"><span class="glyphicon glyphicon-heart"></span>';
                    } else {
                        $likes = '<div class="like" id="like'.$row['id'].'"><span class="glyphicon glyphicon-heart"></span>';
                    }
                    if($row['likes'] > 0){
                        $likes .= ' <span class="counter">'.$row['likes'].'</span></div>';
                    } else {
                        $likes .= '<span class="counter"></span></div>';
                    }
                    $msg = rawurldecode(base64_decode($row['content']));
                    $time = date('c', $row['time']);
                    $r_time = date('jS F o H:i', $row['time']);
	                if(!empty($row['img'])){
		                $attachment = '<br><img src="/wbr/img_upl/'.$row['img'].'" class="post_attached_image img-rounded" id="img'.$row['id'].'">';
	                } else {
		                $attachment = '';
	                }
                    echo <<<WALL_POST
<div class="wall_post" onmouseover="$('#additional{$row['id']}').show()" onmouseout="$('#additional{$row['id']}').hide()" id="{$row['id']}">
<div class="wall_post_additional" id="additional{$row['id']}">
<div class="pull-left help-block"><time datetime="{$time}" data-livestamp="{$row['time']}" title="{$r_time}">{$r_time}</time></div>
</div>
<div class="wall_text">
{$msg}
{$attachment}
</div>
{$likes}
</div>
WALL_POST;

                }
            } else {
                    echo '<br><p class="help-block text-center">Nothing to show :C</p><div class="tlen" style="display: none">GTFO</div>';
                }
            } else {
                echo '<br><p class="help-block text-center">Nothing to show :C</p><div class="tlen" style="display: none">GTFO</div>';
            }
            ?>
        </div>
    </div>
    <!-- DATA !-->
    <div class="col-md-3" id="info">
        <h2><?php echo $profile['first_name'].' '.$profile['second_name'] ?></h2>
        <?php if (!empty($profile['birth'])) {
            echo '<span class="text-muted">Birth</span>: ' . date('j F Y', $profile['birth']) . '<br>';
        }
        if (!empty($profile['city'])) {
            echo '<span class="text-muted">City</span>: ' . $profile['city'] . '<br>';
        }
         if ($profile['gender'] == '0') {
            echo '<span class="text-muted">Gender</span>: female';
        } elseif ($profile['gender'] == '1') {
            echo '<span class="text-muted">Gender</span>: male';
        }if(!empty($profile['about']) or !empty($profile['skype']) or !empty($profile['twitter'])){
	        ?>
	        <br><a href="#" id="showAdditionalInfo">Show more</a>
	        <div id="additionalInfo"><hr>
		        <?php
			        if(!empty($profile['about'])){
				        echo '<span class="text-muted">About</span>: '.$profile['about'].'<br>';
			        }
			        if (!empty($profile['skype'])) {
				        echo '<span class="text-muted">Skype</span>: <a href="skype:' . $profile['skype'] . '" target="_blank">'.$profile['skype'].'</a><br>';
			        }
			        if (!empty($profile['twitter'])) {
				        echo '<span class="text-muted">Twitter</span>: <a href="http://twitter.com/' . $profile['twitter'] . '" target="_blank">'.$profile['twitter'].'</a><br>';
			        }
		        ?></div>
        <?php } ?>
    </div>
    <?php
    //////////////////////////////////////////
} else {
    $query = "SELECT * FROM `profiles` JOIN `userdata` ON `profiles`.`id` = `userdata`.`uid` WHERE `profiles`.`id` =
	{$_SESSION['id']}";
    $result = mysqli_query($mysqli_link, $query);
    $profile = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $profile = $row;
    }
    ?>

    <!-- AVATAR !-->
    <div class="col-md-3" id="avatar_pr">
        <img src="http://gravatar.com/avatar/<?php echo md5($profile['email']) ?>?d=mm&s=200"
             alt="<?php echo $profile['first_name'] ?>">

    </div>
    <!-- WALL !-->
    <div class="col-md-6" id="wall">
		<div id="post_msg_form">
        <div class="input-group">
            <input type="text" class="form-control" id="wall_msg">
      <span class="input-group-btn">
       <button class="btn btn-default" id="send_wall_post" type="button" onclick="wall_post(<?php echo $profile['id'] ?>)"><span class="glyphicon glyphicon-leaf"></span></button>
      </span>
        </div>
			<div id="attachment"></div>
			<div id="img_input_msg">
				<span class="glyphicon glyphicon-picture" style="color:#d3d2d1; font-size: 35px; left: 45%" id="dropzoneDiv"></span>
				</div>
			</div>
        <div id="wall_content">
            <?php
            $wall_query = "SELECT * FROM `wall` WHERE `uid` = {$profile['id']} ORDER BY `time` DESC LIMIT 30";
            $wall_result = mysqli_query($mysqli_link, $wall_query);
            $wall_array = [];
            while($row = mysqli_fetch_assoc($wall_result)){
                $wall_array[] = $row;
            }
            if(!empty($wall_array)){
            foreach($wall_array as $row){
                //
                $likes_query = "SELECT * FROM `wall_likes` WHERE `uid` = {$_SESSION['id']} AND `pid` = {$row['id']}";
                $likes_result = mysqli_query($mysqli_link, $likes_query);
                if(mysqli_num_rows($likes_result) > 0){
                    $likes = '<div class="like liked" id="like'.$row['id'].'"><span class="glyphicon glyphicon-heart"></span>';
                } else {
                    $likes = '<div class="like" id="like'.$row['id'].'"><span class="glyphicon glyphicon-heart"></span>';
                }
                if($row['likes'] > 0){
                    $likes .= ' <span class="counter">'.$row['likes'].'</span></div>';
                } else {
                    $likes .= '<span class="counter"></span></div>';
                }
                //
                $msg = rawurldecode(base64_decode($row['content']));
                $time = date('c', $row['time']);
                $r_time = date('jS F o H:i', $row['time']);
	            if(!empty($row['img'])){
		            $attachment = '<br><img src="/wbr/img_upl/'.$row['img'].'" class="post_attached_image img-rounded" id="img'.$row['id'].'">';
	            } else {
		            $attachment = '';
	            }
                echo <<<WALL_POST
<div class="wall_post" onmouseover="$('#additional{$row['id']}').show()" onmouseout="$('#additional{$row['id']}').hide()" id="{$row['id']}">
<div class="wall_post_additional" id="additional{$row['id']}">
<div class="pull-left help-block"><time datetime="{$time}" data-livestamp="{$row['time']}" title="{$r_time}">{$r_time}</time></div>
<button type="button" class="close" id="wall_delete" onclick="wall_delete({$row['id']})"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
</div>
<div class="wall_text">
{$msg}
{$attachment}
</div>
{$likes}
</div>
WALL_POST;


            }
            } else {
                echo '<hr><br><p class="help-block text-center">Your posts would be shown here. Try it out ;)</p>';
            }
	            echo '<input type="hidden" id="owner" value="1" />';
            ?>

        </div>
        <input type="hidden" id="user_id" value="<?php echo $profile['id'] ?>" />
    </div>
    <div class="col-md-3" id="info">
        <h2><?php echo $profile['first_name'].' '.$profile['second_name'] ?></h2>
        <?php if (!empty($profile['birth'])) {
            echo '<span class="text-muted">Birth</span>: ' . date('j F Y', $profile['birth']) . '<br>';
        }  if (!empty($profile['city'])) {
            echo '<span class="text-muted">City</span>: ' . $profile['city'] . '<br>';
        }  if ($profile['gender'] == '0') {
            echo '<span class="text-muted">Gender</span>: female';
        } elseif ($profile['gender'] == '1') {
            echo '<span class="text-muted">Gender</span>: male';
        }
        if(!empty($profile['about']) or !empty($profile['skype']) or !empty($profile['twitter'])){
	        ?>
	    <br><a href="#" id="showAdditionalInfo">Show more</a>
	    <div id="additionalInfo"><hr>
		    <?php
		    if(!empty($profile['about'])){
			    echo '<span class="text-muted">About</span>: '.$profile['about'].'<br>';
		    }
			    if (!empty($profile['skype'])) {
				    echo '<span class="text-muted">Skype</span>: <a href="skype:' . $profile['skype'] . '" target="_blank">'.$profile['skype'].'</a><br>';
			    }
			    if (!empty($profile['twitter'])) {
				    echo '<span class="text-muted">Twitter</span>: <a href="http://twitter.com/' . $profile['twitter'] . '" target="_blank">'.$profile['twitter'].'</a><br>';
			    }
		    ?></div>
	    <?php } ?>
    </div>

<?php
}
	ob_flush();
?>
</div>
<div class="image_taint" style="display: none"></div>