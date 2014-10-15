<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Contact Details Update</title>
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
<body>
<?php
	include 'config.php'; //has database connection related global constants defined in it
	
	$a=$_GET['id'];
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL");
	
	if(isset($_POST['update'])) //if button 'Update' is clicked
	{
		$fname=trim($_POST["fname"]);
		$lname=trim($_POST["lname"]);
		$ack=trim($_POST["ack"]);
		$city=trim($_POST["city"]);
		$state=trim($_POST["state"]);
		$zip=trim($_POST["zip"]);
		$country=trim($_POST["country"]);
		$email=trim($_POST["email"]);
		$altemail=trim($_POST["altemail"]);
		$home=trim($_POST["home"]);
		$mobile=trim($_POST["mobile"]);
		$work=trim($_POST["work"]);
		$notes=trim($_POST["comments"]);
		$address=trim($_POST["address2"]);
		$mem_since=$_POST["mem_since"];
		if($mem_since=='') //because default value in db for mem_since is set to 0000-00-00. So, inserting '' would give error
			$mem_since='0000-00-00'; 
		$sql = "UPDATE member SET fname = '$fname', lname = '$lname', acknowledge = '$ack', address2='$address',
		city = '$city', state = '$state', zip = '$zip', country = '$country', email = '$email', alt_email = '$altemail',
		mobile = '$mobile', home = '$home', work='$work', notes='$notes',  mem_since='$mem_since' WHERE memberid = '$a'";
		$result = mysqli_query($con,$sql);
		if(! $result )
		{
			die('Could not update data: ' . mysql_error());
		}
		$message = "Updated data successfully!";
		echo "<script type='text/javascript'>alert('$message');</script>";
		
	}
	$result = mysqli_query($con,"SELECT * FROM member WHERE memberid = '$a'");
	$row = mysqli_fetch_array($result);
	
?>

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
					<strong style="padding-left: 200px;font-size: large;"><?php echo $name=$row['fname']." ".$row['lname']." (".$a.")";?><span style="font-weight: normal; font-size: 14px;">, member since </span> <?php echo $row['mem_since'];?></strong>
				</div>
				<form action="<?php $_PHP_SELF ?>" method="POST" class="form-horizontal" onsubmit="return showConfirm()">
					<div class="row"><br>
						<div class="span5">
							<FIELDSET> 
								<LEGEND>Name</LEGEND>
								<table>
									<tbody>
									<tr>
										<td>First Name: </td>
										<td><input type="text" name="fname" value="<?php echo $row['fname']?>" /></td>
									</tr>
									<tr>
										<td>Last Name:</td>
										<td><input type="text"  name="lname" value="<?php echo $row['lname']?>" /></td>
									</tr>
									<tr>
										<td>Acknowledge as:</td>
										<td><input type="text" name="ack" value="<?php echo $row['acknowledge']?>" /></td>
									</tr>
									<tr>
										<td>Member Since: </td>
										<td><input type="date" name="mem_since" value="<?php echo $row['mem_since']?>" /></td>
									</tr>
									</tbody>	 
								</table>
							</FIELDSET>
						</div>
						<div class="span5">
							<FIELDSET> 
								<LEGEND>Address</LEGEND>
								<table>
									<tbody>
										<tr>
											<td>Address:</td>
											<td><input type="text" name="address2" value="<?php echo $row['address2']?>"  /></td>
										</tr>
										<tr>
											<td>City:</td>
											<td><input type="text" name="city" value="<?php echo $row['city']?>"  /></td>
										</tr>
										<tr>
											<td>State:</td>
											<td><input type="text" name="state" value="<?php echo $row['state']?>"  /></td>
										</tr>
										<tr>
											<td>Zip:</td>
											<td><input type="text" name="zip" value="<?php echo $row['zip']?>"  /></td>
										</tr>
										<tr>
											<td>Country:</td>
											<td><input type="text" name="country" value="<?php echo $row['country']?>"  /></td>
										</tr>
									</tbody>
								</table>
							</FIELDSET>
						</div>	
					</div>
					<br>
					<div class="row">
						<div class="span5">
							<FIELDSET> 
								<LEGEND>Email</LEGEND>
								<table>
									<tbody>
										<tr>
											<td>Email:</td>
											<td><input type="email" name="email" class="span4" value="<?php echo $row['email']?>" /></td>
										</tr>
										<tr>
											<td>Alt Email:</td>
											<td><input type="email" name="altemail" class="span4" value="<?php echo $row['alt_email']?>"/></td>
										</tr>
									</tbody>
								</table>
							</FIELDSET>
						</div>
						<div class="span5">
							<FIELDSET> 
								<LEGEND>Phone</LEGEND>
								<table>
									<tbody>
										<tr>
											<td>Home:</td>
											<td><input type="text" name="home" class="span4" value="<?php echo $row['home']?>" /></td>
										</tr>
										<tr>
											<td>Mobile:</td>
											<td><input type="text" name="mobile" class="span4" value="<?php echo $row['mobile']?>"/></td>
										</tr>
										 <tr>
											<td>Work:</td>
											<td><input type="text" name="work" class="span4" value="<?php echo $row['work']?>"/></td>
									  </tr>
									</tbody>
								</table>
							</FIELDSET>
						</div>
					</div>
					<div class="row">
						<div class="span5">
							<FIELDSET> 
								<LEGEND>Comments</LEGEND>
									<textarea class="span5" rows="5" name="comments"><?php echo $row['notes'];?></textarea>
							</FIELDSET>
						</div>
					</div>
					<div class="row">
						<br>
						<span style="padding-left: 20px"><button class="btn btn-primary" name="update"><i class="fa fa-arrow-up"></i> Update</button></span>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php mysqli_close($con);?>
</body>
</html>