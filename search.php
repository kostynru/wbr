<?php
	define('INTERNAL', TRUE);
	$page = 'search';
	//************************************************
	include_once 'is_logged.php';
	if (!$logged_in) {
		header('Location: /wbr/engine.php?act=logout&error=3');
	}
	//**************************************************
	include_once 'header.php';
	session_start();
	//**************************************************
?>
	<div class="col-md-3" id="search_opts">
		<div class="form-group">
			<label for="opt_city">City</label>
			<input type="text" class="form-control" id="opt_city">
		</div>
		<div class="form-group">
			<label for="use_add_opt">Additional data</label>
			<input type="checkbox" id="use_add_opt">
		</div>
		<div id="search_add_opt">
			<div class="form-group">
				<label for="opt_gender">Gender</label>
				<select id="opt_gender">
					<option value="2">Male</option>
					<option value="1">Female</option>
				</select>
			</div>
		</div>
	</div>
	<!--Search results-->
	<div class="col-md-6">
		<div class="input-group">
			<input type="text" class="form-control" id="srch_str" placeholder="Enter the first AND last name" role="search">
      <span class="input-group-btn">
       <button class="btn btn-default" id="start_srch" type="button"><span class="glyphicon glyphicon-search"></span>
       </button>
      </span>
		</div>
		<hr>
		<div id="search_results">
			<?php
				$search_str = $_REQUEST['search_str'];
				if (empty($search_str)) {
					echo '<p class="text-center">Type the first or last name in the input above</p>';
				}
			?>
		</div>
	</div>
	<!--Params-->
	<div class="col-md-3">

	</div>
<?php
	if (!empty($search_str)) {
		echo <<<SCRIPT
<script>
search_get_results(['{$search_str}']);
</script>
SCRIPT;

	}
?>