<?php
include_once 'db.php';
	if($page <> 'index' and $page <> 'register' and $page <> 'credits'){
		if(!isset($_COOKIE['j_timezone']) or empty($_COOKIE['j_timezone'])){
			$query = "SELECT `timezone` FROM `profiles` WHERE `id` = {$_SESSION['id']}";
			$result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_array($result)){
				date_default_timezone_set(htmlspecialchars_decode($row[0]));
				setcookie('j_timezone', htmlspecialchars($row[0]));
			}
		} else {
			date_default_timezone_set(htmlspecialchars_decode($_COOKIE['j_timezone']));
		}
	}
if($page == 'profile'){
    if (isset($_GET['show']) and !empty($_GET['show']) and($_GET['show'] <> $_SESSION['id'] and $_GET['show'] <> $_SESSION['username'])) {
	    $uid = preg_replace('/\s+/', '', $_GET['show']);
        $query = "SELECT * FROM `profiles` JOIN `userdata` ON `profiles`.`id` = `userdata`.`uid` WHERE (`profiles`.`id` =
	'{$uid}' OR `profiles`.`username` = '{$uid}') LIMIT 1";
        $result = mysqli_query($mysqli_link, $query);
        if(!$result){
            header('Location: /wbr/profile.php');
        }
        $profile = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $profile = $row;
        }
        if(empty($profile)){
            header('Location: /wbr/profile.php');
        }
    }
}
?>
    <!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Jenott <?php
            switch ($page) {
                case 'settings':
                    echo '| Settings';
                    break;
                case 'im':
                    echo '| Messages';
                    break;
                case 'friends':
                    echo '| Friends';
                    break;
                case 'profile':
                    if (isset($_GET['show']) and !empty($_GET['show']) and($_GET['show'] <> $_SESSION['id'] and $_GET['show'] <> $_SESSION['username'])) {
                        echo '| '.$profile['first_name'].' '.$profile['second_name'];
                    }
                    break;
            }
            ?></title>
        <link rel="icon" type="image/png" href="<?php echo '/wbr/'; ?>res/favicon16.png" />
        <link href="/wbr/res/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <?php if ($page == 'register') {
            ?>
            <link href="/wbr/res/register.css" rel="stylesheet">
        <?php
        }
        ?>
		<!--Main scripts-->
        <script src="/wbr/res/jquery/jquery-2.1.1.min.js"></script>
        <script src="/wbr/res/jquery/jquery-ui-1.10.4.custom.min.js"></script>
        <script src="/wbr/res/bootstrap/js/bootstrap.min.js"></script>
        <script src="/wbr/res/waypoints.min.js"></script>
		<script src="/wbr/res/al_j.js"></script>
	    <script src="/wbr/res/moment.js"></script>
	    <script src="/wbr/res/livestamp.min.js"></script>
		<!--Main  scripts-->
	<?php if (($page == 'index' and $page <> 'register' and $page <> 'credits') and !isset($_GET['agreement'])) { ?>
		<link href="/wbr/res/style-login.css" rel="stylesheet">
		<script src="/wbr/res/login.js"></script>
	<?php
	}
	?>
		<style>
			@font-face {
				font-family: Magneto;
				src: url('/wbr/res/MAGNETOB.TTF');
			}

			.navbar-brand {
				/*font-family: Magneto, sans-serif;
				font-size: 130%;
				text-shadow: 0 0 1px rgba(51,51,51,0.2);*/
			}
		</style>
		<script src="/wbr/res/upload/jquery.MultiFile.js" type="text/javascript" language="javascript"></script>
        <?php if ($page == 'im') {
            ?>
            <link href="/wbr/res/im.css" rel="stylesheet">
            <script src="/wbr/res/im.js"></script>
            <script src="/wbr/res/jquery.ddslick.min.js"></script>
        <?php } ?>


        <?php
        if ($page == 'register')
            echo '<script src="/wbr/res/register.js"></script>';
        if ($page == 'friends')
            echo '<script src="/wbr/res/friends.js"></script>';
        if ($page == 'profile'){
            echo '<script src="/wbr/res/profile.js"></script>';
            echo '<link href="/wbr/res/profile.css" rel="stylesheet">';
        }
	    if  ($page == 'search'){
		    echo '<script src="/wbr/res/search.js"></script>';
		    echo '<link href="/wbr/res/search.css" rel="stylesheet">';
	    }
        if($page <> 'index' and $page <> 'register' and $page <> 'credits'){
        ?>
	    <link href="/wbr/res/j_style.css" rel="stylesheet">
		<script>
			function user_online(){
				$.ajax({url: "/wbr/engine.php?act=user_online",
					data: {
						user_id: '<?php echo $_SESSION['id'] ?>'
					},
					type: "POST"
				});
			}
			$(function() {
				user_online();
			});
			var timerID = setInterval(user_online, 900000);
		</script>
	<?php }
	        if($page == 'im' or $page == 'friends'){
		?>
	<script>
		$(function () {
			$.ajax({url: "/wbr/engine.php?act=get_all_online_friends",
				data: {
					user_id: '<?php echo $_SESSION['id'] ?>'
				},
				type: "POST",
				dataType: 'text'
			}).done(function (msg) {
					var friends_online = JSON.parse(msg);
					$.each(friends_online, function(index, value) {
						$('#person'+value.id+',#friend'+value.id+',#query'+value.id).append(' <span class="glyphicon glyphicon-stop" style="color: #6fba70; vertical-align: top"></span>');
					})
				})
		})
	</script>
	<?php
	}
		?>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
<body>

<?php
if ($page <> 'index' and $page <> 'register' and $page <> 'credits') {
    $query = "SELECT * FROM `settings` WHERE `uid` = '{$_SESSION['id']}'";
    $result = mysqli_query($mysqli_link, $query);
    $grid = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $grid = $row['gravatar_id'];
    }
    $grav_url = "http://www.gravatar.com/avatar/{$grid}.png?s=45&r=pg";
    ?>
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a href="/wbr/profile.php">
                    <div class="navbar-brand">Jenott
                        <!--<small><?php
/*                            switch ($page) {
                                case 'settings':
                                    echo 'Settings';
                                    break;
                                case 'im':
                                    echo 'Messages';
                                    break;
                                case 'friends':
                                    echo 'Friends';
                                    break;
                            }
                            */?></small>-->
            </div></a>

            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav" id="navbar">
                    <li><a href="/wbr/im.php" id="ims">Messages<?php
                            $unr = file_get_contents("http://localhost/wbr/engine.php?act=im_count&uid={$_SESSION['id']}");
                            $unread = explode('|', $unr);
			                    if ($unread[0] > 0) {
                                echo ' <span class="badge" id="im_counter">';
                                if ($unread[0] < 99) {
                                    echo $unread[0];
                                } else {
                                    echo '99+';
                                }
                                echo '</span>';
                            }
	if($page <> 'index' and $page <> 'register' and $page <> 'credits'){
		?>
		<script src="/wbr/res/md5.js"></script>
		<script src="/wbr/res/longpolling.js"></script>
		<script>
			$(function(){
				var uid = <?php echo $_SESSION['id'] ?>;
				var lid = <?php echo $unread[1] ?>;
				var current = $('#im_counter').html();
				var lp = LongPolling.im_count(uid, current);
			});
		</script>
		<?php if($page == 'im' and !empty($_GET['show']) and isset($_GET['show'])){
			?>
		<script>
			$(function(){
				var uid = <?php echo $_SESSION['id'] ?>;
				var cid = $('#cid').val();
				var lp_upd = LongPolling.chat_update(uid, cid);
			});
		</script>
			<?php
		}
	}
                            ?>
		                    </a></li>
                    <li><a href="/wbr/friends.php">Friends</a></li>
                </ul>
                <p class="nav navbar-nav navbar-right">
                    <?php
                    $query = "SELECT * FROM `profiles` WHERE `id` = {$_SESSION['id']}";
                    $result = mysqli_query($mysqli_link, $query);
                    $userdata = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $userdata = $row;
                    }
                    ?>
                    <?php
                    if (@get_headers($grav_url . '&d=404')[3] == 'Content-Type: text/html; charset=utf-8') {

                        ?>
                        <a href="/wbr/settings.php?target=personal">
                            <img id="avatar" src="<?php echo $grav_url . '&d=mm'; ?>" alt="avatar"></a>
                    <?php } else { ?>

                <div class="dropdown navbar-right">
                    <a id="avatar" data-toggle="dropdown" href="#">
                        <img src="<?php echo $grav_url . '&d=mm'; ?>" alt="avatar">
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="avatar">
                        <li role="presentation"><a role="menuitem" href="/wbr/settings.php">Settings <span
                                    class="glyphicon glyphicon-wrench pull-right"></span></a></li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" href="/wbr/engine.php?act=logout">Logout <span
                                    class="glyphicon glyphicon-off pull-right"></span></a></li>
                    </ul>
                </div>
	            <?php
		            if($page == 'friends' or $page == 'profile'){
			            ?>
			            <form class="navbar-form navbar-right col-xs-3" role="search" action="/wbr/search.php" method="POST">
				            <div class="form-group">
					            <input type="text" class="form-control" name="search_str" placeholder="Search for users" role="search">
				            </div>
				            <button type="submit" class="btn btn-default">Go!</button>
			            </form>

		            <?php
		            }
	            ?>
                <?php } ?>
                </p>
            </div>
        </div>
    </nav>
<?php }

?>
<div class="container-fluid">