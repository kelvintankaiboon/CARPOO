<?php   session_start();  ?>
<?php
	// If session is not set or user is not admin then redirect to Login Page
if(!isset($_SESSION['use'])) 
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
	<div class="w3-sidebar w3-bar-block w3-dark-gray" style="width:10%"> 
		<a href="/carpool/admin_home" class="w3-bar-item w3-button">app_user</a>
		<a href="/carpool/admin_bid" class="w3-bar-item w3-button">bid</a>
		<a href="/carpool/admin_car" class="w3-bar-item w3-button">car</a>
		<a href="/carpool/admin_drive" class="w3-bar-item w3-button">drive</a>
		<a href="/carpool/admin_offer" class="w3-bar-item w3-button">offer</a>
	</div>
	<div style="margin-left: 10%; margin-top:74px;">
		<div class="w3-container">
			<h1>drive</h1>
			<form class="w3-container" method="POST">
				<table class="w3-table-all w3-hoverable">
					<?php 
					include_once ('includes/config.php');
					$db = pg_connect($conn_str);
					$result = pg_query($db, "SELECT * FROM drive WHERE 
						driver='$_GET[edit_driver]' AND
						car='$_GET[edit_car]'
						");
					if (pg_num_rows($result) == 0) {
						echo "<h6>Row not found!<br></h6>";
						echo "<a class=\"w3-button w3-black\" href='admin_drive.php'>Go Back</a>";
					} else if (!$result) {
						echo pg_last_error($db)."<br>";
					} else {
						$row = pg_fetch_assoc($result);
						?>
						<!-- TODO: Follow index.php -->
						<tr>
							<td><label for="driver"><b>Driver : </b></label></td>
							<?php echo '<td><input type="text" value="'.$row['driver'].'" name="driver_updated"></td>'; ?>
						</tr>
						<tr>
							<td><label for="car"><b>Car : </b></label></td>
							<?php echo '<td><input type="text" value="'.$row['car'].'" name="car_updated"></td>'; ?>
						</tr>
				</table>
				<input type="submit" name="edit" value="Submit">
				<?php }?>
			</form>


			<!-- Update in database -->
			<h1>
				<?php 
				date_default_timezone_set('Asia/Singapore');
				$date_curr = date("Y/m/d");
				$time_curr = date("h/i/sa");

				function isModified() {
					return !empty($_POST[driver_updated]) || 
							!empty($_POST[car_updated]);
				}

				function update_drive($db, $row) {
					$driver = !empty($_POST[driver_updated]) ? $_POST[driver_updated] : $row[driver];
					$car = !empty($_POST[car_updated]) ? $_POST[car_updated] : $row[car];
					
					$query = "UPDATE drive SET 
								driver='$driver', 
								car='$car'
								WHERE driver='$_GET[edit_driver]' AND
										car='$_GET[edit_car]';";

					return pg_query($db, $query);
				}
				
				if (!empty($_POST['edit']) && isModified()) {
					if (update_drive($db, $row)) { // affected rows > 0
						// echo "Bid successfully updated!<br>";
						// $url = 'Location: /carpool/admin_edit_car.php?edit_id='.
						// 		$row['plate_number'];
						$url = 'Location: /carpool/admin_drive';
						header($url);
					} else {
						echo "Error updating drive!<br>";
						echo pg_last_error($db)."<br>";
					}
				}
				?>
			</h1>


		</div>
	</div>
</body>
</html>