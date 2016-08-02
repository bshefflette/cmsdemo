<div id="footer">Copyright <?php echo date("Y"); ?>, Widget Corp</div>
</body>
</html>
<?php
	// 5. Close database connection.
	 if (isset($db)) { mysqli_close($db); }
?>