<?php
	$uid = $_GET['uid'];
	$hash = $_GET['hash'];
	if ($hash == md5($uid)) {
		?>
		<script>
			localStorage.clear();
			localStorage.setItem('uid', '<?php echo $uid; ?>');
		</script>
	<?php
	} else {
		header('Location: /wbr/index.php');
	}