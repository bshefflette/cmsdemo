<?php
	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}

	function mysql_prep($string) {
		global $db;
		$escaped_string = mysqli_real_escape_string($db, $string);
		return $escaped_string;
	}

	function confirm_query($result_set) {
		if (!$result_set){
			die("Database query failed.");
		}
	}

	function find_all_subjects($public=true) {
		global $db;
		$query = "SELECT * ";
		$query .= "FROM subjects ";
		if ($public) {
		$query .= "WHERE visible = 1 ";	
		}
		$query .= "ORDER BY position ASC";
		$subject_set = mysqli_query($db, $query);
		confirm_query($subject_set);
		return $subject_set;
	}

	function find_all_admins(){
		global $db;
		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "ORDER BY id ASC";
		$admin_set = mysqli_query($db, $query);
		confirm_query($admin_set);
		return $admin_set;
	}

	function password_encrypt(){
		$hash_format = "$2y$10$"; // Tells PHP to use blowfish with a cost of 10
		$salt_length = 22; // blowfish salts should be 22-chars or more
		$salt = generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}

	function generate_salt($length){
		$unique_random_string = md5(uniqid(mt_rand(), true));
		//not 100% unique or random but returns 32-chars from md5

		$base64_string = base64_encode($unique_random_string);
		//valid chars for a salt are [a-zA-Z0-9./]

		$modified_base64_string = str_replace('+', '.', $base64_string);
		//but not '+' which is valid in base64 encoding

		$salt = substr($modified_base64_string, 0, $length);

		return $salt;
	}


	function attempt_login($username, $password) {
		$admin = find_admin_by_username($username);
		if ($admin) {
			
			//found admin, now check password
			if (password_verify($password, $admin["hashed_password"])) {
				// password matches
				return $admin;
			} else {
				//password does not match
				return false;
			}
		} else {
			//admin not found
			return false;
		}

	}

	function confirm_logged_in() {
		if(!logged_in()) {
		redirect_to("login.php");
		}
	}

	function logged_in(){
		return isset($_SESSION['admin_id']);
	}

	function list_admins_by_id(){
		$output = "<ul class=\"adminusername\"><li>Username</li>";
			$admin_set = find_all_admins();
			while($admin = mysqli_fetch_assoc($admin_set)){
				$output .= "<li>";
				$output .= htmlentities($admin["username"]);
				$output .= "</li>";
			}
			
			$output .= "</ul>";
			$output .= "<ul class=\"adminactions\"><li>Actions</li>";
			$admin_set = find_all_admins();
			while($adminz = mysqli_fetch_assoc($admin_set)){
				$output .= "<li>";
				$output .= "<a href=\"edit_admin.php?admin=";
				$output .= urlencode($adminz["id"]);
				$output .= "\">Edit</a>";
				$output .= "&nbsp;&nbsp;";
				$output .= "<a href=\"delete_admin.php?admin=";
				$output .= urlencode($adminz["id"]);
				$output .= "\" onclick=\"return confirm('Are you sure?')\">Delete</a>";
				$output .= "</li>";
			}
			$output .= "</ul>";
			mysqli_free_result($admin_set);
			return $output;
	}

	function find_admin_by_id($admin_id){
		global $db;
		$admin_set = find_all_admins();

		$safe_admin_id = mysqli_real_escape_string($db, $admin_id);

		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "WHERE id = {$safe_admin_id} ";
		$query .= "LIMIT 1";
		$admin_set = mysqli_query($db, $query);
		confirm_query($admin_set);
		if ($admin = mysqli_fetch_assoc($admin_set)) {
		return $admin;
		} else {
			return null;
		}
	}

	function find_admin_by_username($username){
		global $db;

		$safe_username = mysqli_real_escape_string($db, $username);

		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "WHERE username = '{$safe_username}' ";
		$query .= "LIMIT 1";
		$az = mysqli_query($db, $query);
		confirm_query($az);
		if ($adminz = mysqli_fetch_assoc($az)) {
		return $adminz;
		} else {
			return null;
		}
	}
	function find_pages_for_subject($subject_id, $public=true) {
		global $db;
		$safe_subject_id = mysqli_real_escape_string($db, $subject_id);
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE subject_id = {$safe_subject_id} ";
		if ($public) {
		$query .= "AND visible = 1 ";
		}
		$query .= "ORDER BY position ASC";
		$page_set = mysqli_query($db, $query);
		confirm_query($page_set);
		return $page_set;
	}

	function find_subject_by_id($subject_id, $public=true){
		global $db;

		$safe_subject_id = mysqli_real_escape_string($db, $subject_id);

		$query = "SELECT * ";
		$query .= "FROM subjects ";
		$query .= "WHERE id = {$safe_subject_id} ";
		if($public) {
			$query .= "AND visible = 1 ";
		}
		$query .= "LIMIT 1";
		$subject_set = mysqli_query($db, $query);
		confirm_query($subject_set);
		if ($subject = mysqli_fetch_assoc($subject_set)) {
		return $subject;
		} else {
			return null;
		}
	}

	function find_page_by_id($page_id, $public=true){
		global $db;

		$safe_page_id = mysqli_real_escape_string($db, $page_id);

		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE id = {$safe_page_id} ";
		if($public) {
			$query .= "AND visible = 1 ";
		}
		$query .= "LIMIT 1";
		$page_set = mysqli_query($db, $query);
		confirm_query($page_set);
		if ($page = mysqli_fetch_assoc($page_set)) {
		return $page;
		} else {
			return null;
		}
	}

	function find_default_page_for_subject($subject_id) {
		$page_set = find_pages_for_subject($subject_id);
			if ($first_page = mysqli_fetch_assoc($page_set)) {
			return $first_page;
		} else {
			return null;
		}
	}

	function find_selected_page($public=false) {
		global $current_subject;
		global $current_page;
			if (isset($_GET["subject"])) {
			$current_subject = find_subject_by_id($_GET["subject"], $public);
			if ($current_subject && $public) {
				$current_page = find_default_page_for_subject($current_subject["id"]);
			} else{
			$current_page = null;
			}
		}	elseif (isset($_GET["page"])) {
			$current_page = find_page_by_id($_GET["page"], $public);
			$current_subject = null;
		} 	else {
			$current_subject = null;
			$current_page = null;
		}
	}

	function navigation($subject_array, $page_array) {
		$output = "<ul class=\"subjects\">";
			$subject_set = find_all_subjects(false);
			while($subject = mysqli_fetch_assoc($subject_set)) {
				$output .= "<li"; 
					if ($subject_array && $subject["id"] == $subject_array["id"]) {
					$output .= " class=\"selected\"";
						}
				$output .=	">"; 
				$output .=	"<a href=\"manage_content.php?subject="; 
				$output .=	urlencode($subject["id"]);
				$output .=	"\">";
				$output .=	htmlentities($subject["menu_name"]); 
				$output .=	"</a>";
				$page_set = find_pages_for_subject($subject['id'], false);
				$output .=	"<ul class=\"pages\">";
				while($page = mysqli_fetch_assoc($page_set)) {
					
				$output .=	"<li"; 
					if ($page_array && $page["id"] == $page_array["id"]) {
				$output .=" class=\"selected\"";
					}
				$output .=	">"; 
				$output .=	"<a href=\"manage_content.php?page=";
				$output .=	urlencode($page["id"]); 
				$output .=	"\">";
				$output .= htmlentities($page["menu_name"]); 
				$output .=	"</a></li>";
				}
				mysqli_free_result($page_set); 	
				$output .=	"</ul></li>";
			}

				mysqli_free_result($subject_set);	
				$output .=	"</ul>";
				return $output;
	}

	function public_navigation($subject_array, $page_array) {
		$output = "<ul class=\"subjects\">";
			$subject_set = find_all_subjects();
			while($subject = mysqli_fetch_assoc($subject_set)) {
				$output .= "<li"; 
					if ($subject_array && $subject["id"] == $subject_array["id"]) {
					$output .= " class=\"selected\"";
						}
				$output .=	">"; 
				$output .=	"<a href=\"index.php?subject="; 
				$output .=	urlencode($subject["id"]);
				$output .=	"\">";
				$output .=	htmlentities($subject["menu_name"]); 
				$output .=	"</a>";

				if ($subject_array["id"] == $subject["id"] || $page_array["subject_id"] == $subject["id"]) {
				$page_set = find_pages_for_subject($subject['id']);
				$output .=	"<ul class=\"pages\">";
				while($page = mysqli_fetch_assoc($page_set)) {
					
				$output .=	"<li"; 
					if ($page_array && $page["id"] == $page_array["id"]) {
				$output .=" class=\"selected\"";
					}
				$output .=	">"; 
				$output .=	"<a href=\"index.php?page=";
				$output .=	urlencode($page["id"]); 
				$output .=	"\">";
				$output .= htmlentities($page["menu_name"]); 
				$output .=	"</a></li>";
				}
				$output .=	"</ul>";
				mysqli_free_result($page_set); 	
			}
				
				$output .= "</li>"; //end of subject li
			}

				mysqli_free_result($subject_set);	
				$output .=	"</ul>";
				return $output;
	}

	function form_errors($errors=array()) {
		$output = "";

		if(!empty($errors)) {
			$output .= "<div class=\"error\">";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach ($errors as $key => $error) {
				$output .= "<li>";
				$output .= htmlentities($error);
				$output .= "</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";	
		}
		return $output;
	}

	function list_all_subjects($tag) {
			$output = "";
			$subject_set = find_all_subjects();
			while($subject = mysqli_fetch_assoc($subject_set)) {
				$output .= "<{$tag}"; 
				$output .= " value=\"{$subject["id"]}\"";
				$output .=	">"; 
				$output .=	htmlentities($subject["menu_name"]); 
				$output .=	"</{$tag}>";
				
			}

				mysqli_free_result($subject_set);	
				return $output;
	}
?>