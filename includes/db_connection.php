<?php

	define("DB_SERVER", "localhost");
	define("DB_USER", "widget_cms");
	define("DB_PASS", "secret");
	define("DB_NAME", "widget_corp");
	// 1. Create a database connection
	$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	//Test connection
	if(mysqli_connect_errno()){
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" 
			);
	}
?>