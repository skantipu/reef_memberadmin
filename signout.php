<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> <!--using locally saved font-awesome icons -->
<html>
	<head>
		<title>
			Logout page
		</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<div>
			<h1>You have signed out...Thank you!</h1>
			<h2><a href="index.php">REEF Login <i class="fa fa-sign-in fa-2x"></i></a></h2>
		</div>
	</body>
</html>

