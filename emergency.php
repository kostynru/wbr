<?php
	if (isset($_POST['key1']) and isset($_POST['key2']) and !empty($_POST['key1']) and !empty($_POST['key2'])) {
		if ($_POST['key1'] == '86708b85b3f1428d5424e3d391c4d69a' and $_POST['key2'] == 'a8d8124f5cd28aeb8cd6fb1dba864ae9') {
			define('ADMITTED', TRUE);
			include_once 'admin.php';
		} else {
			header('Location: emergency.php');
		}
	} else {
	?>
		<!DOCTYPE html>
		<html>
		<head>
			<link href="res/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		</head>
		<body>
		<div class="container">
<form name="emergency" role="form" method="POST" action="emergency.php" style="max-width: 40%">
	<label for="key1" class="control-label">The first key</label>
	<input type="password" name="key1" class="form-control" id="key1">
	<label for="key2" class="control-label">The second key</label>
	<input type="password" name="key2" class="form-control" id="key2"><br>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>
		</div>
		</body>
		</html>
<?php
	}