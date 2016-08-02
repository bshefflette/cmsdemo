<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php
if (isset ($_POST['submit'])){
	$menu_name = mysql_prep($_POST["menu_name"]);
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];

	//validations
	$required_fields = array("menu_name", "position", "visible");
	validate_presences($required_fields);
	$fields_with_max_lengths = array("menu_name"
		 => 30);
	validate_max_lengths($fields_with_max_lengths);	


	if(!empty($errors)) {
		$_SESSION["errors"] = $errors;
		redirect_to("new_subject.php");
	} 

	$query = "INSERT INTO subjects (";
	$query .= " menu_name, position, visible";
	$query .= ") VALUES (";
	$query .= " '{$menu_name}', {$position}, {$visible}";
	$query .= ")";

	$result = mysqli_query($db, $query);
	// Test for query error.
	if($result) {
		// Success
		// redirect_to("somepage.php");
		$_SESSION["message"] = "Subject created.";
		redirect_to("manage_content.php");
	} else {
		$_SESSION["message"] = "Subject created failed.";
		redirect_to("new_subject.php");
	}
} else {
	//Probably a GET request
	redirect_to("new_subject.php");
}

?>


<?php
// 5. Close database connection.
if (isset($db)) { mysqli_close($db); }
?>