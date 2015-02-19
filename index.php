<!--login page-->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>login page</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" href="csslocal.css"/>
	<script src='js/bootstrap.js'></script>
	<style>
		#error{
			color: red;
			font-style: italic;
		}
		label{
			font-weight: bold;
		}
	</style>
</head>
<body style="background-color: #f7f7f7">
	<?php
	$temp=0; // a temporary variable. It will be set for wrong user credentials.temp = 1 if wrong user credentials and temp=2 for inactive user. It is used for displaying appropriate error message later in form body
	include 'config.php'; //config.php has database connection related global constants defined in it
	if(isset($_POST['login'])) //if button 'Login' is clicked
	{
		$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) // Check connection
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		//sanitize user input
		$uname = mysqli_real_escape_string($con, $_POST["username"]);
		$pwd = mysqli_real_escape_string($con, $_POST["password"]);
		
		//encrypt user password using md5 algorithm
		$pwd=md5($pwd);
		
		//check if there is a match in user_table with the entered user credentials. If there is a match, one row (count = 1) should be retreieved 
		$result = mysqli_query($con,"SELECT * FROM user_table WHERE username = '$uname' and password = '$pwd'");
		$row = mysqli_fetch_array($result);
		$count = mysqli_num_rows($result);
		
		//if user name and password matches (count = 1), check if user is active
		if($count==1)
		{
			//if user is not active, initialize temp=2, display appropriate error message and not let him proceed, done later in form body
			if($row['active']!=1)
			{
				$temp=2; 
			}
			//if user is active, initialize all session variables and redirect to homepage
			else
			{
				
				$_SESSION['username'] = $uname;
				$_SESSION['password'] = $pwd;
				$_SESSION['firstname']= $row['firstname'];
				$_SESSION['lastname']= $row['lastname'];
				$_SESSION['id']=$row['id'];
				header('Location: /a1/homepage.php');	
			}
		} //close of outer if 
		//user name and password do not match, set temp variable to 1
		else
		{
			$temp=1;  
		}
	mysqli_close($con);
	}	
	?>
	<div class="modal" id="myModal">
		<div class="modal-header">
			<h3>REEF Login</h3>
		</div>
		<div class="modal-body">
			<form method="post">
				<?php
				if($temp==1){  //wrong user credentials
					echo '<div id="error">Username and Password do not match...Please try again!</div>';
				}
				else if($temp==2){ //inactive user
					echo '<div id="error">Sorry, you are not an active user. Please contact your supervisor for help!</div>';
				}
				?>
				<label>Username: </label>
				<input type="text" class="span4" name="username" required autofocus/><br>
				<label>Password: </label>
				<input type="password" class="span4" name="password" required /><br>
				<button class="btn btn-primary" name="login">Login</button>
				&nbsp;&nbsp;First time logging in?&nbsp;<a href="login_register.php">Register</a>
				&nbsp;&nbsp;Other options: <a href="login_change_password.php">Change Password</a>
			</form>
		</div>
	</div>
</body>
</html>
