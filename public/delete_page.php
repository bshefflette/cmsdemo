<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php

	$current_page = find_page_by_id($_GET["page"], false);
	if (!$current_page) {
		// subject ID was missing or invalid or couldnt be found in db
		redirect_to("manage_content.php");
	}


	$id = $current_page["id"];
	$query = "DELETE FROM pages WHERE id = {$id} LIMIT 1";
	$result = mysqli_query($db, $query);
		// Test for query error.
		if($result && (mysqli_affected_rows($db) == 1)) {
			$_SESSION["message"] = "Page deleted.";
			redirect_to("manage_content.php");
		} else{
			$_SESSION["message"] = "Page deletion failed.";
			redirect_to("manage_content.php?page={$id}");
		}
?>