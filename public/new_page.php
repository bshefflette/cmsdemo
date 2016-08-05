<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "admin";?>
<?php include("../includes/layouts/header.php"); ?>
<?php find_selected_page(); ?>
<?php if (!$current_subject){
	redirect_to("manage_content.php");
} ?>
	<div id="main">
		<div id="navigation">
			<?php echo navigation($current_subject, $current_page); ?>
		</div>
		<div id="page">
		<?php echo message(); ?>
		<?php $errors = errors(); ?>
		<?php echo form_errors($errors); ?>

		<h2>Create Page</h2>
		<form action="create_page.php" method="post">
		  <p>Menu name:
		    <input type="text" name="menu_name" value="" />
		  </p>
		  <p>Subject ID:

		    <select name="subject_id">
				<?php
					echo list_all_subjects("option"); 
				?>
		    </select>

		  </p>
		  <p>Position:
		    <select name="position">
				<?php
					$page_set = find_pages_for_subject($current_subject["id"]);
					$page_count = mysqli_num_rows($page_set);
					for($count=1; $count <= ($page_count + 1); $count++){
						echo "<option value=\"{$count}\"";
						if ($current_page["position"] == $count){
						echo " selected";
					}
						echo ">{$count}</option>";
					}
				?>
		    </select>
		  </p>

		  <p>Visible:
		    <input type="radio" name="visible" value="0" /> No
		    &nbsp;
		    <input type="radio" name="visible" value="1" checked/> Yes
		  </p>
		  <p>Content:
		  	<input type="textarea" name="content" row="20" columns="80" value="<?php echo htmlentities($current_page["content"]); ?>" />
		  </p>
		  <input type="submit" name="submit" value="Create Page" />
		</form>
		<br />
		<a href="manage_content.php">Cancel</a>
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>
