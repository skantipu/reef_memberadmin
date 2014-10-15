<?php
	$a=$_GET['id'];
	include 'config.php';
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
	if (mysqli_connect_errno()) // Check connection
	{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$result = mysqli_query($con,"SELECT fname,lname,mem_since FROM member WHERE memberid = $a");
	$row = mysqli_fetch_array($result);
	
	
	if(isset($_POST['go']))  //if button is pressed
	{  
		$svalue=$_POST["startdate"];
		$evalue=$_POST["enddate"];
		$query1=  //Total amount donated between two dates
		"SELECT SUM(amount) total
		FROM contrib
		WHERE memberid='$a' AND datereceived BETWEEN '$svalue' AND '$evalue';";

		$query2=
		"SELECT COUNT(*) number
		FROM contrib
		WHERE memberid='$a' AND datereceived BETWEEN '$svalue' AND '$evalue';";
		
		$result = mysqli_query($con,$query1);
		$row1 = mysqli_fetch_array($result);
		
		$result = mysqli_query($con,$query2);
		$row2 = mysqli_fetch_array($result);
	}
?>
<!-- input type="date" is not supported in IE, so inorder to have consistency among all browsers, use POLYFILL with JavaScript-->
<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Member Donations Query</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="csslocal.css"/>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> <!--using locally saved font-awesome icons -->
	<script type="text/javascript" src="jquery/jquery.min.js"></script>
	<script src="js/bootstrap.js"></script>
</head>
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
				<div class="container">
					<strong style="padding-left: 200px;font-size: large;"><?php echo $row['fname']." ".$row['lname']." (".$a.")";?><span style="font-weight: normal; font-size: 14px;">, member since </span> <?php echo $row['mem_since'];?></strong>
				</div>
				<form action="<?php $_PHP_SELF ?>" method="POST">
					<div class="row span10"><br>
						<table class="table">
							<thead>
								<tr>
									<th>Start Date</th>
									<th>End Date</th>
									<th></th>
									<th>Amount ($)</th>
									<th># of Donations</th>
								</tr>
							</thead>
							<tbody>
							<tr>
								<td><input type="date" name="startdate" value="<?php echo (isset($_POST['go'])? $svalue :'');?>" required /></td>
								<td><input type="date" name="enddate" value="<?php echo (isset($_POST['go'])? $evalue :'');?>" required /></td>
								<td><button class="btn btn-primary" name="go"><i class="fa fa-filter"></i> Go</button></td>
								<td><b><?php echo (isset($_POST['go'])? $row1['total'] :'');?></b></td>
								<td><b><?php echo (isset($_POST['go'])? $row2['number'] :'');?></b></td>
							</tr>
							</tbody>	 
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php mysqli_close($con);?>
</body>
</html>