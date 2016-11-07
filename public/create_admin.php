<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php
if (isset ($_POST['submit'])){
	$username = mysql_prep($_POST["username"]);
	$hashed_password = password_hash($_POST["password"], PASSWORD_BCRYPT, ['cost' => 10]);

	//validations
	$required_fields = array("username", "password");
	validate_presences($required_fields);
	$fields_with_max_lengths = array("username"
		 => 20, "password" => 20);
	validate_max_lengths($fields_with_max_lengths);	


	if(!empty($errors)) {
		$_SESSION["errors"] = $errors;
		redirect_to("new_admin.php");
	} 

	$query = "INSERT INTO admins (";
	$query .= " username, hashed_password ";
	$query .= ") VALUES (";
	$query .= " '{$username}', '{$hashed_password}'";
	$query .= ")";

	$result = mysqli_query($db, $query);
	// Test for query error.
	if($result) {
		// Success
		// redirect_to("somepage.php");
		$_SESSION["message"] = "Admin created.";
		redirect_to("manage_admins.php");
	} else {
		$_SESSION["message"] = "Admin creation failed.";
		redirect_to("new_admin.php");
	}
} else {
	//Probably a GET request
	redirect_to("new_admin.php");
}

?>


<?php
// 5. Close database connection.
if (isset($db)) { mysqli_close($db); }
?>