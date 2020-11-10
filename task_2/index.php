<?php
ob_start();
session_start();
include 'db/connection.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Keshav Infotech - Task 02</title>
	<link rel="stylesheet" type="text/css" href="style/bootstrap.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
		label{
			font-weight: bold;
		}
		#result p{
			font-weight: bold;
		}
	</style>
</head>
<body>

<?php
	$lastQN = 0;
	$getQN = mysqli_query($conn, "SELECT que_no FROM questions");
	if(mysqli_num_rows($getQN)>0){
		while(mysqli_fetch_assoc($getQN)){
			$lastQN++;
		}
	}else{
		$lastQN = 0;
	}
?>

<div class="container">
	<div>
		<h1>Add Questions</h1>
		<form action="" method="POST" class="col-lg-6">
			<label>Question <?php echo $lastQN+1; ?> </label>
			<br>
			<textarea name="que" rows="5" class="form-control" required></textarea>
			<br>
			<input type="text" name="op1" placeholder="Add Option 1" required class="form-control">
			<br>
			<input type="text" name="op2" placeholder="Add Option 2" required class="form-control">
			<br>
			<input type="text" name="op3" placeholder="Add Option 3" required class="form-control">
			<br>
			<input type="text" name="op4" placeholder="Add Option 4" required class="form-control">
			<br>
			<select name="ans" class="form-control" required>
				<option value="">Choose Answer</option>
				<option value="A">A</option>
				<option value="B">B</option>
				<option value="C">C</option>
				<option value="D">D</option>
			</select>
			<br>
			<input type="submit" name="addQue" class="btn btn-primary" value="ADD QUESTION">
		</form>
	</div>
	<center>
		<a href="?exam#ed"><button class="btn btn-warning">EXAM</button></a>		
	</center>

<?php

if(isset($_GET['exam'])){
	if(isset($_SESSION['right'])){
		unset($_SESSION['right']);
		unset($_SESSION['wrong']);
	}

	$qry = mysqli_query($conn, "SELECT * FROM questions");
	?>
	<div id="ed">
		<hr>
		<center><h1>Online Examination Form</h1></center>
		<form action="" method="POST">
			<?php
			$qn = 0;
			if(mysqli_num_rows($qry)>0){
				while($dataRow = mysqli_fetch_assoc($qry)){
					$qn++;
					?>
					<b><?php echo $qn.") ".$dataRow['que'] ?></b>
					<br>
					<input type="radio" name="<?php echo "que_".$dataRow['que_no'] ?>" value="A" required>
					<span><?php echo $dataRow['op1']; ?></span>
					<br>
					<input type="radio" name="<?php echo "que_".$dataRow['que_no'] ?>" value="B" required>
					<span><?php echo $dataRow['op2']; ?></span>
					<br>
					<input type="radio" name="<?php echo "que_".$dataRow['que_no'] ?>" value="C" required>
					<span><?php echo $dataRow['op3']; ?></span>
					<br>
					<input type="radio" name="<?php echo "que_".$dataRow['que_no'] ?>" value="D" required>
					<span><?php echo $dataRow['op4']; ?></span>
					<br>
					<br>
					<?php		
				}
			}
			?>
			<center><input type="submit" name="submit" value="SUBMIT" class="btn btn-primary"></center>
			<br>
			<br>
		</form>
	</div>
	<?php
}

if(isset($_GET['clear'])){
	unset($_SESSION['right']);
	unset($_SESSION['wrong']);
}

if(isset($_SESSION['right'])){
	$total_marks = $_SESSION['right']-($_SESSION['wrong']*0.25);
	?>
	<div class="col-lg-6 m-auto" id="result">
		<hr>
		<h1>Online Exam Result</h1>
		<p>Correct Answer: <?php echo $_SESSION['right']; ?></p>
		<p>Wrong Answer: <?php echo $_SESSION['wrong']; ?></p>
		<p>Total Marks: <?php echo $total_marks; ?></p>
		<br>
		<a href="?clear"><button class="btn btn-danger">CLEAR</button></a>
		<br><br><br>
	</div>
	<?php
}

?>


</div>

</body>
</html>

<?php

if(isset($_SESSION['success'])){
	?>
	<script>
		alert("Question added successfully.");
	</script>
	<?php
	unset($_SESSION['success']);
}elseif(isset($_SESSION['fail'])){
	?>
	<script>
		alert("Something went wrong, question not added.");
	</script>
	<?php
	unset($_SESSION['fail']);	
}

if(isset($_POST['addQue'])){
	$que = $_POST['que'];
	$op1 = $_POST['op1'];
	$op2 = $_POST['op2'];
	$op3 = $_POST['op3'];
	$op4 = $_POST['op4'];
	$ans = $_POST['ans'];

	$addQ = mysqli_query($conn, "INSERT INTO questions (que_no,que,op1,op2,op3,op4,ans) VALUES('$lastQN'+1,'$que','$op1','$op2','$op3','$op4','$ans')");

	if($addQ){
		$_SESSION['success'] = "success";
		header('Location: index.php');
	}else{
		$_SESSION['fail'] = "fail";
		header('Location: index.php');
	}

}

if(isset($_POST['submit'])){
	$counter = 0;
	$right = 0;
	$wrong = 0;
	$ansQ = mysqli_query($conn, "SELECT * FROM questions");
	if(mysqli_num_rows($ansQ)>0){
		$counter++;
		while($dataRow = mysqli_fetch_assoc($ansQ)){
			if($dataRow['ans']==$_POST['que_'.$counter]){
				$right++;
			}else{
				$wrong++;
			}
		}
		$_SESSION['right'] = $right;
		$_SESSION['wrong'] = $wrong;
		header('Location: index.php#result');
	}else{
		header('Location: index.php#result');
	}
}

?>