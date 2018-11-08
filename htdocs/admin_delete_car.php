<?php   session_start();  ?>
<?php
	// If session is not set or user is not admin then redirect to Login Page
	if (!isset($_SESSION['use'])) 
	{
		header("Location: /carpool");  
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<style>
	a {
		text-decoration: none;
	}
	td a { 
		display: block; 
	}
</style>
</head>
<body>
	<div class="w3-container w3-black w3-top">
		<a href="/carpool/home" style="float:left;"><h1>Car Pooling</h1></a>
		<a href="logout.php" style="float:right;padding-top: 45px">Log Out</a>
	</div>
	<?php 
		include_once ('includes/admin_sidenav.php');
	?>
	<div style="margin-left: 10%; margin-top:74px;">
		<div class="w3-container">
			<?php 
			include_once ('includes/config.php');
			$db = pg_connect($conn_str);
			$result = pg_query($db, "SELECT * FROM car WHERE 
						plate_number='$_GET[delete_id]'");
			if (pg_num_rows($result) == 0) {
				echo "<h1>Car not found!<br></h1>";
				echo "<a class=\"w3-button w3-black\" href='admin_car.php'>Go Back</a>";
			} else if (!result) {
				echo pg_last_error($db)."<br>";
			} else {
			?>
				<h1>Are you sure you want to delete this row?</h1>
				<form class="w3-container" method="POST">
					<a class="w3-button w3-black" href='admin_car.php'>Go Back</a>
					<input class="w3-button w3-black" type="submit" name="delete" value="Delete">
				</form>
			<?php } ?>
				<!-- Delete from database -->
				<h1>
					<?php 

					function delete_car($db) {
					$query = "DELETE FROM car WHERE 
										plate_number='$_GET[delete_id]';";
					return pg_query($db, $query);
				}
				
				if (!empty($_POST['delete'])) {
					if (delete_car($db)) { // affected rows > 0
						echo "Car successfully deleted!<br>";
						// header("Location: /carpool/admin_home");
					} else {
						echo "Error deleting car!<br>";
						echo pg_last_error($db)."<br>";
					}
				}
				?>
			</h1>


		</div>
	</div>
</body>
</html>
