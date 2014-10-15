<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Log</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="csslocal.css"/>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> <!--using locally saved font-awesome icons -->
	<script type="text/javascript" src="jquery/jquery.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script>
		function showConfirm() {
			return confirm("Please confirm your action:\nPress 'OK' below if you want to update the database.");
		}
	</script>
</head>
<?php

	include 'config.php'; //has database connection related global constants defined in it
	
	$a=$_GET['id']; //memberid
	$hid=$_GET['hid']; //hhrecordID in household_members table
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
	if (mysqli_connect_errno()) // Check connection
	{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if(isset($_POST['update'])) //if button 'Update' is clicked
	{
		$comments=$_POST['comments'];
		$query="UPDATE household_members SET comments='$comments' WHERE hhrecordID=$hid;";
		$result = mysqli_query($con,$query) or die("Failed to connect to MySQL");
		header("Location: /a1/tr_household.php?id=$a");
	}
	$query1= 
		"SELECT h.*,m.fname,m.lname
		FROM household_members h
		INNER JOIN member m ON h.memberid=m.memberid
		WHERE h.hhrecordID='$hid';";
	$result = mysqli_query($con,$query1) or die("Failed to connect to MySQL");
	$row = @mysqli_fetch_array($result);
	
?>
<body>
	<div class="row pad-left pad-top">
		<?php include 'header.php';?>
	</div>
	<div class="row">
		<div class="span2 pad-left">
			<?php include 'sidebar.php';?>
		</div>
		<div class="container">
			<div class="span10 pad-left">
				<div class="row">
					<?php include 'sub_header.php';?>	
				</div>
				<div class="row">
					<fieldset>
						<form action="<?php $_PHP_SELF ?>" method="POST" class="form-horizontal" onsubmit="return showConfirm()">
							<legend>Edit Comment</legend>
							<b>Member ID: </b><?php echo $row['memberid'];?><br>
							<b>Member name: </b><?php echo $row['fname']." ".$row['lname'];?><br><br>
							<b>Edit Comment: </b><input type="text" style="width:400px" name="comments" value="<?php echo $row['comments'];?>"/>
							<br><br><button class="btn btn-primary" name="update"><i class="fa fa-arrow-up"></i> Update</button>
						</form>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
<?php mysqli_close($con);?>
</body>
</html>
