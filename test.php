<?php
	$verb = $_SERVER["REQUEST_METHOD"];

	if ($verb == 'GET') {
		// some get things
		if (isset($_GET["filename"])) {
			$file_content = file_get_contents($_GET["filename"]);
			echo $file_content;
		} else {
			die("ERROR: PARAMETERS NOT GIVEN!");
		}
	}
?>