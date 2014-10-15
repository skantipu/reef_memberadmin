<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Add/Edit member experience</title>
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
<!-- This page works for both ways - clicking directly on Add new exp in sidebar link and from table in sub-header Surveys tab -->
<body>
<?php
	include 'config.php'; //has database connection related global constants defined in it
	$set=0;
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
	if (mysqli_connect_errno()) // Check connection
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if (strpos($_SERVER['REQUEST_URI'],'?') == true && isset($_GET['expid'])) //if URL has ? - meaning paramerters are passed in it when clicked from Surveys tab in sub_header
	{
		$set=1; //make $set to 1, if above conditions holds true - you have to check if $set is set later
		$mid=$_GET['id'];
		$mregion=$_GET['region'];
		$mlevel=$_GET['level'];
		$mdate=$_GET['date'];
		$expid=$_GET['expid'];
	}
	else if(strpos($_SERVER['REQUEST_URI'],'?') == true) //if only memberid is passed in the URL
	{
		$mid=$_GET['id'];
	}
 
	if(isset($_POST['update'])) //if button 'Update' is clicked
	{
		$id=$_POST["mid"];
		$region=$_POST["inputregion"];
		$level=$_POST["inputlevel"];
		$date=$_POST["date"];
		
		if($set==1)
			$sql="UPDATE member_exp set memberid=$id,region='$region',exp=$level,date='$date' where expid=$expid;";
		else
			$sql = "INSERT into member_exp (memberid,region,exp,date) values ($id,'$region',$level,'$date');";
		 
		$result = mysqli_query($con,$sql);
		if(! $result )
		{
			die('Could not update data. ' . mysql_error());
		}
		$message = "Updated data successfully!";
		echo "<script type='text/javascript'>alert('$message');</script>";
		if($set==1)
			header("Location: /a1/tr_surveys.php?id=$mid"); //A single quoted string in PHP is treated as a string literal, which is not parsed for variables. Double quoted strings are parsed for variables
			
	}
  
?>

	<div class="row pad-left pad-top">
		<?php include 'header.php';?>
	</div>
	<div class="row">
		<div class="span2 pad-left">
			<?php include 'sidebar.php';?>
		</div>
		<div class="span10 pad-left">
			<div class="row pad-left">
				<form action="<?php $_PHP_SELF ?>" method="POST" class="form-horizontal" onsubmit="return showConfirm()">
					<FIELDSET> 
						<LEGEND>Add/Edit Member Experience</LEGEND>
						<table>
							<tbody>
							<tr>
								<td>Exp Id: </td>
								<?php
									if($set==0)  //if it is not set - it will be set to 1 if parameters passed in the URL
										echo '<td><i>Auto-generated</i></td>';
									else
										echo '<td>'.$expid.'</td>';
								?>
							</tr>
							<tr>
								<td>Member Id:</td>
								<td><input type="text" name="mid" maxlength="7" pattern="[0-9]{1,7}" value="<?php echo (isset($mid)?$mid:'');?>" required /></td>
							</tr>
							<tr>
								<td>Region:</td>
								<td>
								<select name="inputregion" required>
									<?php
									$query="select regioncode from region;";  //newly created table: contribmethod
									$result=mysqli_query($con,$query);
									while($row=mysqli_fetch_array($result)){
										$sel=0;
										if(isset($mregion))
											if(strcmp($mregion,$row['regioncode'])==0)
												$sel=1;
									
									?>
										<option value=<?php echo $row['regioncode'];?> <?php echo ($sel==1?' selected':''); ?>><?php echo $row['regioncode'];?></option>
									<?php
									}
									?>
								</select>
								</td>
							</tr>
							<tr>
								<td>Experience Level:</td>
								<td>
									<select name="inputlevel" required>
									<?php
									$query="select distinct exp from member_exp order by exp;";  //newly created table: contribmethod
									$result=mysqli_query($con,$query);
									while($row=mysqli_fetch_array($result)){
										$sel=0;
										if(isset($mlevel))
											if(strcmp($mlevel,$row['exp'])==0)
												$sel=1;
									
									?>
										<option value=<?php echo $row['exp'];?> <?php echo ($sel==1?' selected':''); ?>><?php echo $row['exp'];?></option>
									<?php
									}
									?>
								</select>
								</td>
							</tr>
							<tr>
								<td>Date:</td>
								<td><input type="date" name="date" value="<?php echo ($set==0?'':$mdate);?>" required/></td>
							</tr>
							</tbody>	 
						</table>
					</FIELDSET>
					<br>
					<button class="btn btn-primary" name="update"><i class="fa fa-arrow-up"></i> Update</button>
				</form>
			</div>
		</div>
	</div>


</body>
</html>