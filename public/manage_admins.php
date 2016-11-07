<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "admin";?>
<?php include("../includes/layouts/header.php"); ?>
<?php find_all_admins(); ?>
	<div id="main">
		<div id="navigation">
			<br /><a href="admin.php">&laquo; Main Menu</a>
		</div>
		<div id="page">
			<?php echo message(); ?>

				<h2>Manage Admins</h2>
				<?php
					echo list_admins_by_id();
				?>
				<br /><br /><hr style="border: 1px solid #8D0D19;"/><br />
				<a href="new_admin.php">+ Add new admin</a>
				<br />
				
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>
