<?php
	
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_SCHEMA', 'rms');
	
	if (!$GLOBALS['DB'] = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD)) {
		die('Error: Unable to connect to the database server.');
	}
	if (!mysqli_select_db($GLOBALS['DB'], DB_SCHEMA)) {
		mysqli_close($GLOBALS['DB']);
		die('Error: Unable to select the database schema.');
	}
	
	
	echo "ok";
?>