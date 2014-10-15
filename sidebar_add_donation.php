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
			return confirm("Please confirm your action:\nPress 'OK' below if you want to update the database.");
		}
	</script>
</head>
<!-- This page works for both ways - clicking directly on Add Donation in sidebar link and from table in sub-header Donations tab -->
<body>
<?php
	include 'config.php'; //has database connection related global constants defined in it
	$set=0; //set=0 indicates no URL parameters passed; set=1 indicates all URL parameters passed; set=2 indicates only memberid is passed in the URL by clicing 'Add Donation' link from tr_donations1.php page
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
	if (mysqli_connect_errno()) // Check connection
	{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if (strpos($_SERVER['REQUEST_URI'],'?') == true && isset($_GET['cid'])) //if URL has ? and cid is passed - meaning all paramerters are passed in it when clicked from Donations Main tab in sub_header
	{
		$set=1; //make $set to 1, if above condition holds true. It is helpful later to check if set=1
		$s_id=$_GET['id']; //memberid
		$s_cid=$_GET['cid']; //contribid
		$s_amount=$_GET['amount'];
		$s_sourceid=$_GET['sourceid']; //contribsourceid
		$s_source=$_GET['source'];
		$s_date=$_GET['date'];
		$s_methodid=$_GET['methodid']; //contribmetod id
		$s_method=$_GET['method']; 
		$s_comment=$_GET['comment']; 
	}
	else if(strpos($_SERVER['REQUEST_URI'],'?') == true) //this is executed only if above 'if' block condition fails; i.e., if cid is not passed - meaning only memberid is passed by clicking 'Add Donation' link from Donations Main page
	{
		$s_id=$_GET['id']; 
	}
 
	if(isset($_POST['update'])) //if button 'Update' is clicked
	{
		$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) // Check connection
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		//edited values after user clicked 'update' button
		$id=$_POST['id'];  //memberid; Note: Contribid is not editable, so we're using $s_cid in the query
		$amount=$_POST['amount'];
		$sourceid=$_POST['sourceid']; //contribsourceid
		$date=$_POST['date'];
		$methodid=$_POST['methodid'];
		$comment=$_POST['comment'];
	//	echo "<br>".$id." ".$amount." ".$sourceid." ".$date." ".$methodid." ".$comment;
		if($set==1){ //meaning- a row is clicked for edit by passing all parameters in the URL
			$sql="UPDATE contrib set memberid=$id,datereceived='$date',amount=$amount,contribsourceid=$sourceid,
			contribmethod=$methodid,comments='$comment' where contribid=$s_cid;";
		}
		else{  //No parameters passed and user enters all the information
			$sql = "INSERT into contrib (memberid,datereceived,amount,contribsourceid,contribmethod,comments) values ($id,'$date',$amount,$sourceid,$methodid,'$comment');";
		 //While inserting, '' for $date is mandatory
		}
		$result = mysqli_query($con,$sql);
		if(! $result )
		{
			die('Could not update data. ' . mysql_error());
		}
		$message = "Updated data successfully!";
		echo "<script type='text/javascript'>alert('$message');</script>";
		if($set==1)
			header("Location: /a1/tr_donations1.php?id=$id"); //A single quoted string in PHP is treated as a string literal, which is not parsed for variables. Double quoted strings are parsed for variables
			
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
						<LEGEND>Add/Edit Donation</LEGEND>
						<table>
							<tbody>
								<tr>
									<td>Contrib Id: </td>
									<?php
										if($set==0)  //if it is not set; it will be set to 1 if parameters passed in the URL
											echo '<td><i>Auto-generated</i></td>';
										else
											echo '<td>'.$s_cid.'</td>';
									?>
								</tr>
								<tr>
									<td>Member Id:</td>
									<td><input type="text" name="id" maxlength="8" pattern="[0-9]{1,7}" value="<?php echo (isset($s_id)?$s_id:'');?>" required /></td> <!--$s_id (memberid) will be printed if $set==1 or 2-->
								</tr>
								<tr>
									<td>Date Received:</td>
									<td><input type="date" name="date" value="<?php echo ($set==0?'':$s_date);?>" required /></td>
								</tr>
								<tr>
									<td>Amount:</td>
									<td><input type="text" name="amount" maxlength="15" pattern="[0-9]{1,15}" value="<?php echo ($set==0?'':$s_amount);?>" required /></td>
								</tr>
								<tr>
									<td>Contrib Source:</td>
									<td>
										<select name="sourceid" required>  <!--form dropdown value is accessed via 'name' attribute in select
										tag: $_GET['sourceid']; It returns option tag's value (here it's sourceid)-->
											<?php
											$query="select contribsourceid,contribdescription from contrib_source order by contribsourceid desc;";
											$result=mysqli_query($con,$query);
											while($row=mysqli_fetch_array($result)){
												$sel=0;
												if(isset($s_sourceid))
													if($s_sourceid==$row['contribsourceid'])
														$sel=1;
											?>
												<option value=<?php echo $row['contribsourceid'];?> <?php echo ($sel==1?' selected':'');?>><?php echo $row['contribdescription']." (".$row['contribsourceid'].")"; ?></option>;
											<?php
												} //close of while loop
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Contrib Method:</td> 
									<td>
										<select name="methodid" required>
											<?php
											$query="select id,name from contribmethod;";  //newly created table: contribmethod
											$result=mysqli_query($con,$query);
											while($row=mysqli_fetch_array($result)){
												$sel=0;
												if(isset($s_method))
													if($s_method==$row['name'])
														$sel=1;
											?>
												<option value=<?php echo $row['id'];?> <?php echo ($sel==1?' selected':'');?>><?php echo $row['name']." (".$row['id'].")"; ?></option>;
											<?php
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Comment:</td>
									<td><textarea rows="5" name="comment"><?php echo ($set==0?'':$s_comment);?></textarea></td>
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