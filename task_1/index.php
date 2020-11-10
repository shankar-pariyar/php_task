<?php
ob_start();
date_default_timezone_set("Asia/Kolkata");
include 'db/connection.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Keshav Infotech - Task 01</title>
	<link rel="stylesheet" type="text/css" href="style/bootstrap.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
		label{
			font-weight: bold;
		}
	</style>
</head>
<body>
	<div class="container">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-12">
			<h1>Add Images</h1>
			<p>
				Allow user to add as many images as he wants.
			</p>
			<div>
				<form action="" method="POST" enctype="multipart/form-data">
					<label>User Name:</label>
					<br>
					<input type="text" name="userName" required>
					<br>
					<br>
					<label>Upload Photo</label>
					<br>
					<input type="file" name="userPhoto" required>
					<br>
					<br>
					<label>Add Photo Title</label>
					<br>
					<input type="text" name="photoTitle" required>
					<br>
					<br>
					<input type="submit" name="addPhoto" value="ADD PHOTO" class="btn btn-primary">
				</form>
			</div>
		</div>



		<div class="col-lg-6 col-md-6 col-sm-12">
			<h1>Choose User</h1>
			<div>
				<form action="" method="GET">
					<label>Choose User</label>
					<br>
					<select name="userName" required>
						<option value="">Choose User</option>
						<?php
						$userListQuery = mysqli_query($conn, "SELECT DISTINCT user_name FROM user_data");
						if(mysqli_num_rows($userListQuery)>0){
							while($dataRow = mysqli_fetch_assoc($userListQuery)){
								?>
								<option value="<?php echo $dataRow['user_name']?>"><?php echo $dataRow['user_name']?></option>
								<?php
							}
						}
						?>
					</select>
					<br>
					<br>
					<input type="submit" name="viewImages" value="VIEW IMAGES" class="btn btn-primary">
				</form>
			</div>
		</div>
	</div>
	<br>
	<div>
		<table width="100%" cellpadding="10">
			
			<?php
			$photoArray = array();

			if(isset($_GET['viewImages'])){
				$userName = $_GET['userName'];
				$userImageQuery = mysqli_query($conn, "SELECT photo,photo_title FROM user_data WHERE user_name='$userName' ");
				if(mysqli_num_rows($userImageQuery)>0){
					while($dataRow = mysqli_fetch_assoc($userImageQuery)){
						array_push($photoArray, $dataRow);
					}
					
					$rowPhotoArray = array_chunk($photoArray, 3);
					$mainArrayLength = sizeof($rowPhotoArray);
					for($i=0;$i<$mainArrayLength;$i++){
						$subArrayLength = sizeof($rowPhotoArray[$i]);
						?>
						<tr>
							<?php
								for($j=0;$j<$subArrayLength;$j++){
									?>
									<td>
										<center>
										<img src="<?php echo "images/".$rowPhotoArray[$i][$j]['photo']; ?>" width="200" height="200">
										<p>
											<?php echo $rowPhotoArray[$i][$j]['photo_title']; ?>
										</p>
										</center>
									</td>
									<?php
								}
							?>
						</tr>
						<?php
					}
				}
			}

			?>

		</table>
	</div>
	
	</div>
</body>
</html>

<?php

if(isset($_SESSION['success'])){
	?>
	<script>
		alert("Data added.");
	</script>
	<?php
	session_unset();
}elseif(isset($_SESSION['fail'])){
	?>
	<script>
		alert("Something went wrong, data not added.");
	</script>
	<?php
	session_unset();	
}

if(isset($_POST['addPhoto'])){
	$userName = $_POST['userName'];
	$file = $_FILES['userPhoto'];
	$photoName = $_FILES['userPhoto']['name'];
	$photoTmpName = $_FILES['userPhoto']['tmp_name'];
	$photoTitle = $_POST['photoTitle'];
	$createdDate = date('d-m-Y h:i:s');

	$add_query = mysqli_query($conn, "INSERT INTO user_data (user_name,photo,photo_title,created_date) VALUES('$userName','$photoName','$photoTitle','$createdDate')");

	if($add_query){
		move_uploaded_file($photoTmpName, "images/".$photoName);
		$_SESSION['success'] = 'success';
		header('Location: index.php');
	}else{
		$_SESSION['fail'] = 'fail';
	}

}

?>