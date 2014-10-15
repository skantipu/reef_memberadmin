<!DOCTYPE html>
<html lang="en">
<head>
	<title>login page</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" href="csslocal.css"/>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> <!--using locally saved font-awesome icons -->
	</script>
</head>
<body style="background-color: #f7f7f7">
	<?php
	include 'config.php'; //has database connection related global constants defined in it
	if(isset($_POST['register'])) //if button 'Register' is clicked
	{
		$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) // Check connection
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$uname=$_POST["username"];
		$pwd=$_POST["password"];
		$pwd1=$_POST["password1"];
		$email=$_POST["email"];
		$fname=$_POST["firstname"];
		$lname=$_POST["lastname"];
		$result = mysqli_query($con,"select * from user_table where username='$uname'");
		$row = mysqli_fetch_array($result);
		$count=mysqli_num_rows($result);
		if($count>0){
			echo "<script type='text/javascript'>alert('Username already exists');</script>";
		}
		else if ($pwd!=$pwd1){
			echo "<script type='text/javascript'>alert('Both passwords do not match');</script>";
		}
		else{
			$pwd=md5($pwd); //ecrypting password using md5
			$result = mysqli_query($con,"INSERT into user_table (firstname,lastname,email,username,password)
										  VALUES ('$fname','$lname','$email','$uname','$pwd')");
			echo "<script type='text/javascript'>alert('Successfully user registered! Click Login Page link to go back');</script>";
		}
		
	}	
	?>
	<div class="modal" id="myModal">
		<div class="modal-header">
			<h3>REEF User Registration</h3>
		</div>
		<div class="modal-body" style="padding-left: 25%">
			<form method="post">
				<input type="text" class="span4" name="firstname" placeholder="First Name" pattern="[A-Za-z]{2,}" required /><br>
				<input type="text" class="span4" name="lastname" placeholder="Last Name" pattern="[A-Za-z]{2,}" required /><br>
				<input type="email" class="span4" name="email" placeholder="Email"  required /><br>
				<input type="text" class="span4" name="username" placeholder="Enter User Name" pattern="[A-Za-z0-9]{4,}" required /><br>
				<input type="password" class="span4" name="password" placeholder="Enter Password" pattern=".{4,}" required /><br>
				<input type="password" class="span4" name="password1" placeholder="Re-Enter Password" pattern=".{4,}" required /><br>
				
				<button type="submit" class="btn btn-primary" name="register">Register</button>
				<br><br><a href="index.php"><b>Login Page </b><i class="fa fa-sign-in"></i></a>
			</form>
		</div>
	</div>
</body>
</html>