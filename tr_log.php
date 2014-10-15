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
	
	$a=$_GET['id'];
	
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
	if (mysqli_connect_errno()) // Check connection
	{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if(isset($_POST['update'])) //if button 'Update' is clicked
	{
		$itemtype=$_POST["itemtype"];
		$date=$_POST["date"];
		$user=$_POST["user"];
		$description=$_POST["description"];
		$result = mysqli_query($con,"INSERT INTO member_log (memberid,date_entered,user_entered,log_typeID,log_description) values ($a,'$date','$user',$itemtype,'$description');");
		if(! $result )
		{
			die('Could not update data. ' . mysql_error());
		}
		$message = "Updated data successfully!";
		echo "<script type='text/javascript'>alert('$message');</script>";
		
	}
	
	$result = mysqli_query($con,"SELECT * FROM member WHERE memberid = '$a'");
	$row = mysqli_fetch_array($result);
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
				<div class="container">
					<strong style="padding-left: 200px;font-size: large;"><?php echo $row['fname']." ".$row['lname']." (".$a.")";?><span style="font-weight: normal; font-size: 14px;">, member since </span> <?php echo $row['mem_since'];?></strong>
				</div><br>
				<form action="<?php $_PHP_SELF ?>" method="POST" class="form-horizontal" onsubmit="return showConfirm()">
					<div class="row">
						<div class="span5">
							<table>
								<tr>
									<td>Item Type: </td>
									<td>
										<select name="itemtype">
										<?php
											$result = mysqli_query($con,"SELECT log_typeID,log_type_description FROM log_type;");
											while($row = mysqli_fetch_array($result)){
										?>
										<option value=<?php echo $row['log_typeID'];?>><?php echo $row['log_type_description']." (".$row['log_typeID'].")";?></option>
										<?php
											}
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Date: </td>
									<td><input type="date" name="date" required/></td>
								</tr>
								<tr>
									<td>User: </td>
									<td><input type="text" name="user" value="<?php echo $_SESSION['firstname'];?>"/></td>
								</tr>
							</table>
						</div>
						<div class="span5">
							<table>
								<tr>
									<td>Description: </td>
									<td><textarea class="span4" rows="5" name="description"></textarea></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="container row">
						<button class="btn btn-primary" name="update"><i class="fa fa-arrow-up"></i> Update</button>
					</div>
				</form>
				<div class="container span10">
					<table class="table">
						<thead>
							<th>Item Type</th>
							<th>Description</th>
							<th>Date</th>
							<th>User</th>
						</thead>
						<tbody>
								<?php
									$result = mysqli_query($con,"SELECT m.date_entered,m.log_description,m.user_entered,l.log_type_description FROM member_log m inner join log_type l on m.log_typeID=l.log_typeID where m.memberid=$a;");
									while($row = mysqli_fetch_array($result)){
								?>
								<tr>
								<td><?php echo $row['log_type_description'];?></td>
								<td><?php echo $row['log_description'];?></td>
								<td><?php echo $row['date_entered'];?></td>
								<td><?php echo $row['user_entered'];?></td>
								</tr>
								<?php
									}
								?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
