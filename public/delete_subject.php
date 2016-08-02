<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php

	$current_subject = find_subject_by_id($_GET["subject"]);
	if (!$current_subject) {
		// subject ID was missing or invalid or couldnt be found in db
		redirect_to("manage_content.php");
	}

	$pages_set = find_pages_for_subject($current_subject["id"]);
	if (mysqli_num_rows($pages_set) > 0) {
		$_SESSION["message"] = "Can't delete a subject with pages.";
		redirect_to("manage_content.php?subject={$current_subject["id"]}");
	}

	$id = $current_subject["id"];
	$query = "DELETE FROM subjects WHERE id = {$id} LIMIT 1";
	$result = mysqli_query($db, $query);
		// Test for query error.
		if($result && (mysqli_affected_rows($db) >= 0)) {
			$_SESSION["message"] = "Subject deleted.";
			redirect_to("manage_content.php");
		} else{
			$_SESSION["message"] = "Subject deletion failed.";
			redirect_to("manage_content.php?subject={$id}");
		}
?>