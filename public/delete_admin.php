<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php

	$current_admin = find_admin_by_id($_GET["admin"]);
	if (!$current_admin) {
		// subject ID was missing or invalid or couldnt be found in db
		redirect_to("manage_admins.php");
	}


	$id = $current_admin["id"];
	$query = "DELETE FROM admins WHERE id = {$id} LIMIT 1";
	$result = mysqli_query($db, $query);
		// Test for query error.
		if($result && (mysqli_affected_rows($db) >= 0)) {
			$_SESSION["message"] = "Admin deleted.";
			redirect_to("manage_admins.php");
		} else{
			$_SESSION["message"] = "Admin deletion failed.";
			redirect_to("manage_admins.php");
		}
?>