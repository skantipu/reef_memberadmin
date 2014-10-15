<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Add/Edit Donation</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="csslocal.css"/>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> <!--using locally saved font-awesome icons -->
	<script type="text/javascript" src="jquery/jquery.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script>
		function showConfirm() {
			return confirm("Please confirm your action:\nPress 'OK' below if you want to update the database.\n\n Also note that, 'comments' can be added/edited via Household tab in sub navigation bar.");
		}
	</script>
</head>
<!-- This page works for both ways - clicking directly on Add Donation in sidebar link and from table in sub-header Donations tab -->
<body>
<?php
	include 'config.php'; //has database connection related global constants defined in it
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
	if (mysqli_connect_errno()) // Check connection
	{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if(isset($_POST['go'])){
		$members=$_POST['members'];
		$query1="SELECT fname,lname from member where memberid in ($members);";
		$result = mysqli_query($con,$query1);// or die("Failed to connect to MySQL");
		$text1="";  //initializing $text1 - "firstname1 firstname2 ..." of all the entered member ids
		$text2=""; //"firstname1 lastname1 firstname2 lastname2 ..." of all the entered member ids
		while($row = @mysqli_fetch_array($result)){
			$text1.=$row['fname']." and ";
			$text2.=$row['fname']." ".$row['lname']." and ";
		}
		$text1=trim($text1); //trimming trailing and starting white spaces
		$text1=rtrim($text1,"and"); //trimming trailing "and"
		
		$text2=trim($text2);
		$text2=rtrim($text2,"and");
	}
	if(isset($_POST['save'])) //if button 'Save' is clicked
	{
		$ihs=$_POST['ihs'];
		$iha=$_POST['iha'];
		$fhs=$_POST['fhs'];
		$fha=$_POST['fha'];
		$members=$_POST['hidden_members'];  //since this is a different form, we used hidden input tag to access members variable value 
		
		//trimming starting and trailing spaces
		$ihs=trim($ihs);
		$iha=trim($iha);
		$fhs=trim($fhs);
		$fha=trim($fha);
		
		$query2="INSERT INTO household (informal_hhsalutation,informal_hhaddressee,formal_hhsalutation,formal_hhaddressee)
		VALUES ('$ihs','$iha','$fhs','$fha');";
		$result=mysqli_query($con,$query2) or die("Could not insert household values");
		$result=mysqli_query($con,"select householdID from household where informal_hhsalutation='$ihs' and informal_hhaddressee='$iha'");
		$row = @mysqli_fetch_array($result);
		$hid=$row['householdID'];
		$htext=explode(",",$members); //$htext array contains all the entered member ids
		foreach ($htext as $item) { //$item has each member id
			$result=mysqli_query($con,"insert into household_members (householdID,memberid) values ($hid,$item)") or die ("Could not insert household_members values");
		}
		$message = "Updated data successfully  in household and household_members tables!";
		echo "<script type='text/javascript'>alert('$message');</script>";
		unset($members);
		mysqli_close($con);
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
			<FIELDSET> 
				<LEGEND>Create Household</LEGEND>
				<form action="<?php $_PHP_SELF ?>" method="POST" class="form-horizontal">
					<table>
						<tbody>
							<tr>
								<td>Who do you want to add: </td>
								<td><input type="text" name="members" style="width:400px" required pattern="[0-9]+(,[0-9]+)*"
											  placeholder="Enter member ids seperated by comma - Ex: 11,12,70,71,72"
											  title="Only allowed characters - digits and comma (no spaces)"
											  value="<?php echo (isset($members)?$members:"");?>"/>
								&nbsp;&nbsp;<button class="btn btn-primary" name="go">Go</button></td>
							</tr>
						</tbody>
					</table>
				</form>
				<form action="<?php $_PHP_SELF ?>" method="POST" class="form-horizontal" onsubmit="return showConfirm()">
					<table>
						<tbody>
							<tr>
								<td>Informal Salutation: </td>
								<td><input type="text" style="width:400px" name="ihs" value="<?php echo (isset($text1)?$text1:"");?>" required /></td> 
							</tr>
							<tr>
								<td>Informal Addressee:</td>
								<td><input type="text" style="width:400px" name="iha" value="<?php echo (isset($text2)?$text2:"");?>" required /></td> 
							</tr>
							<tr>
								<td>Formal Salutation:</td>
								<td><input type="text" style="width:400px" name="fhs" value="<?php echo (isset($text1)?$text1:"");?>" required /></td>
							</tr>
							<tr>
								<td>Formal Addressee:</td>
								<td><input type="text" style="width:400px" name="fha" value="<?php echo (isset($text2)?$text2:"");?>" required /></td>
							</tr>
							<input type="hidden" name="hidden_members" value="<?php echo (isset($members)?$members:"");?>">
						</tbody>	 
					</table>
				<br>
				<button class="btn btn-primary" name="save"><i class="fa fa-save"></i> Save</button>
				</form>
			</FIELDSET>
		</div>
	</div>
</body>
</html>