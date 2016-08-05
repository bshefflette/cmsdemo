<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php
if (isset ($_POST['submit'])){
		$id = $current_page["id"];
		$subject_id = (int) $_POST["subject_id"];
		$menu_name = mysql_prep($_POST["menu_name"]);
		$position = (int) $_POST["position"];
		$visible = (int) $_POST["visible"];
		$content = mysql_prep($_POST["content"]);

	//validations
	$required_fields = array("menu_name", "subject_id", "position", "visible", "content");
	validate_presences($required_fields);
	$fields_with_max_lengths = array("menu_name"
		 => 30);
	validate_max_lengths($fields_with_max_lengths);	


	if(!empty($errors)) {
		$_SESSION["errors"] = $errors;
		redirect_to("new_page.php?subject={$subject_id}");
	} 

	$query = "INSERT INTO pages (";
	$query .= " menu_name, subject_id, position, visible, content";
	$query .= ") VALUES (";
	$query .= " '{$menu_name}', {$subject_id}, {$position}, {$visible}, '{$content}'";
	$query .= ")";

	$result = mysqli_query($db, $query);
	// Test for query error.
	if($result) {
		// Success
		// redirect_to("somepage.php");
		$_SESSION["message"] = "Page created.";
		redirect_to("manage_content.php");
	} else {
		$_SESSION["message"] = "Page creation failed.";
		redirect_to("new_page.php?subject=" . urlencode($subject_id));
	}
} else {
	//Probably a GET request
	redirect_to("new_page.php?subject={$subject_id}");
}

?>


<?php
// 5. Close database connection.
if (isset($db)) { mysqli_close($db); }
?>