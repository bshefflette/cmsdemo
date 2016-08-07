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
	$required_fields = array("username", "hashed_password");
	validate_presences($required_fields);
	$fields_with_max_lengths = array("username"
		 => 30, "hashed_password" => 30);
	validate_max_lengths($fields_with_max_lengths);	


	if(empty($errors)) {

			// Perform Update
		
		$id = $current_admin["id"];
		$username = mysql_prep($_POST["username"]);
		$password = mysql_prep($_POST["hashed_password"]);

		$query = "UPDATE admins SET ";
		$query .= "username = '{$username}', ";
		$query .= "hashed_password = '{$password}' ";
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
		    <input type="password" name="hashed_password" value="<?php echo htmlentities($current_admin["hashed_password"]); ?>" />
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
