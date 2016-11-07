<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php
	if (isset($_GET["admin"])) {
			$current_admin = find_admin_by_id($_GET["admin"]);
		} else { redirect_to("manage_admins.php");}
?>

<?php
if (isset ($_POST['submit'])){
	//validations
	$required_fields = array("username", "password");
	validate_presences($required_fields);
	$fields_with_max_lengths = array("username"
		 => 30, "password" => 60);
	validate_max_lengths($fields_with_max_lengths);	


	if(empty($errors)) {

			// Perform Update
		
		$id = $current_admin["id"];
		$username = mysql_prep($_POST["username"]);
		$hashed_password = password_hash($_POST["password"], PASSWORD_BCRYPT, ['cost' => 10]);

		$query = "UPDATE admins SET ";
		$query .= "username = '{$username}', ";
		$query .= "hashed_password = '{$hashed_password}' ";
		$query .= "WHERE id = {$id} ";
		$query .= "LIMIT 1";

		$result = mysqli_query($db, $query);
		// Test for query error.
		if($result && (mysqli_affected_rows($db) >= 0)) {
			// Success
			// redirect_to("somepage.php");
			$_SESSION["message"] = "Admin updated.";
			redirect_to("manage_admins.php");
		} else {
			$message = "Admin update failed.";
		}
	} 
}else{

}// end: if (isset ($_POST['submit'])){

?>
<?php $layout_context = "admin";?>
<?php include("../includes/layouts/header.php"); ?>
	<div id="main">
		<div id="navigation">
		</div>
		<div id="page">
		<?php if (!empty($message)) {
			echo "<div class=\"message\">" . htmlentities($message) . "</div>";
			} ?>
		<?php echo form_errors($errors); ?>

		<h2>Edit Admin: <?php echo htmlentities($current_admin["username"]); ?></h2>
		<form action="edit_admin.php?admin=<?php echo urlencode($current_admin["id"]); ?>" method="post">
		  <p>Username:
		    <input type="text" name="username" value="<?php echo htmlentities($current_admin["username"]); ?>" />
		  </p>
		  <p>Password:
		    <input type="password" name="password" value="<?php echo htmlentities($current_admin["hashed_password"]); ?>" />
		  </p>
		  <input type="submit" name="submit" value="Edit Admin" />
		</form>
		<br />
		<a href="manage_admins.php?">Cancel</a>
		&nbsp;
		&nbsp;
		<a href="delete_admin.php?admin=<?php echo urlencode($current_admin["id"]); ?>" onclick="return confirm('Are you sure?');">Delete Admin</a>
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>
