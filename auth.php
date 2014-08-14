<?php
	$uid = $_GET['uid'];
	$hash = $_GET['hash'];
	if ($hash == md5($uid)) {
		?>
		Wait for a second...
		<script>
			localStorage.clear();
			localStorage.setItem('uid', '<?php echo $uid; ?>');
			window.location.replace('/wbr/profile.php');
		</script>
	<?php
	} else {
		header('Location: /wbr/index.php');
	}