<!DOCTYPE html>
<html lang="en">
<head>
	<title>login page</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" href="csslocal.css"/>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> <!--using locally saved font-awesome icons -->
</head>
<body style="background-color: #f7f7f7">
	<?php
	include 'config.php'; //has database connection related global constants defined in it
	if(isset($_POST['change'])) //if button 'Change' is clicked
	{
		$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) // Check connection
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$uname=$_POST["username"];
		$pwd=$_POST["password"]; //existing pwd
		$epwd=md5($pwd); //encrypting existing pwd to check with the database save pwd
		$pwd1=$_POST["password1"]; //new pwd
		$pwd2=$_POST["password2"]; //retyped new pwd
		$result = mysqli_query($con,"select * from user_table where username='$uname'");
		$row = mysqli_fetch_array($result);
		$count=mysqli_num_rows($result);
		if($count<=0){
			echo "<script type='text/javascript'>alert('Username does not exist');</script>";
		}
		else if ($epwd!=$row['password']){
			echo "<script type='text/javascript'>alert('Incorrect existing password');</script>";
		}
		else if ($pwd1!=$pwd2){
			echo "<script type='text/javascript'>alert('New password and retyped new password do not match...Please check');</script>";
		}
		else if ($pwd==$pwd1){
			echo "<script type='text/javascript'>alert('New password is same as old password');</script>";
		}
		else{
			$pwd1=md5($pwd1); //ecrypting new password using md5
			$result = mysqli_query($con,"update user_table set password='$pwd1' where username='$uname'");
			echo "<script type='text/javascript'>alert('Successfully changed password! Click Login Page link to go back');</script>";
		}
		
	}	
	?>
	<div class="modal" id="myModal">
		<div class="modal-header">
			<h3>REEF Password Change</h3>
		</div>
		<div class="modal-body" style="padding-left: 25%">
			<form method="post">
				<input type="text" class="span4" name="username" placeholder="User Name" pattern="[A-Za-z0-9]{4,}" required /><br>
				<input type="password" class="span4" name="password" placeholder="Exisiting Password" pattern=".{4,}" required /><br>
				<input type="password" class="span4" name="password1" placeholder="New Password" pattern=".{4,}" required /><br>
				<input type="password" class="span4" name="password2" placeholder="Retype New Password" pattern=".{4,}" required /><br>
				
				<button type="submit" class="btn btn-primary" name="change">Change Password</button>
				<br><br><a href="index.php"><b>Login Page </b><i class="fa fa-sign-in"></i></a>
			</form>
		</div>
	</div>
</body>
</html>