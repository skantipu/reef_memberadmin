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
	<script>
		jQuery(document).ready(function($) {
			$(".clickableRow").click(function() {
					window.document.location = $(this).attr("href");
			});
		});
	</script>
	<style>
		tr:hover {
		cursor: pointer;
		}
		body .modal{
			width: 800px;	
		}
		.pre{
			font-family: monospace;
			color: grey;
		}
		.small td{
			font-size: 12px;
		}
	</style>
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
		$iha1=$_POST['iha'];
		$ihs1=$_POST['ihs'];
		$fhs1=$_POST['fhs'];
		$fha1=$_POST['fha'];
		$hid1=$_POST['hid'];
		
		$iha1=trim($iha1);
		$ihs1=trim($ihs1);
		$fhs1=trim($ihs1);
		$fha1=trim($fha1);
		
		$query="UPDATE household SET informal_hhsalutation='$ihs1',informal_hhaddressee='$iha1',
		formal_hhsalutation='$fhs1',formal_hhaddressee='$fha1' WHERE householdID='$hid1';";
		$result = mysqli_query($con,$query) or die("Failed to connect to MySQL");
		$message = "Updated data successfully!";
		echo "<script type='text/javascript'>alert('$message');</script>";
	}
	
	$query1= 
		"SELECT hm.householdID,h.*
		FROM household_members hm
		INNER JOIN household h ON hm.householdID=h.householdID
		WHERE hm.memberid = $a;";
	$result = mysqli_query($con,$query1) or die("Failed to connect to MySQL");
	$row = @mysqli_fetch_array($result);  //should not use die() as if there is no data, then die is evaluated
	$householdID=$row['householdID'];
	$iha=$row['informal_hhaddressee'];
	$ihs=$row['informal_hhsalutation'];
	$fhs=$row['formal_hhsalutation'];
	$fha=$row['formal_hhaddressee'];
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
				<div class="container" style="padding-left: 200px;">
					<?php
						if(isset($row)){
							echo '<strong style="font-size: large;">';
							echo $iha." - Household ".$householdID;
						}
						else
							echo '<span class="pre">no data - no data - no data (please first create household)</span>'; 
					?>
					</strong>
				</div><br>
				<div class="row">
					<fieldset>
						<legend>Household details</legend>
						<form action="<?php $_PHP_SELF ?>" method="POST" class="form-horizontal" onsubmit="return showConfirm()">
							<table>
								<tbody>
									<tr>
										<td>Informal_hhsalutation: </td>
										<td><input type="text" style="width:400px" name="ihs" value="<?php echo $ihs;?>" required /></td> 
									</tr>
									<tr>
										<td>Informal_hhaddressee:</td>
										<td><input type="text" style="width:400px" name="iha" value="<?php echo $iha;?>" required /></td> 
									</tr>
									<tr>
										<td>Formal_hhsalutation:</td>
										<td><input type="text" style="width:400px" name="fhs" value="<?php echo $fhs;?>" required /></td>
									</tr>
									<tr>
										<td>Formal_hhaddressee:</td>
										<td><input type="text" style="width:400px" name="fha" value="<?php echo $fha;?>" required /></td>
									</tr>
									<input type="hidden" name="hid" value="<?php echo $householdID;?>"/>
								</tbody>
							</table><br>
							<button class="btn btn-primary" name="update"><i class="fa fa-arrow-up"></i> Update</button>
						</form>
					</fieldset>
				</div>
				<div class="row">
					<fieldset>
						<legend>Donations</legend>
						<?php
							$result = mysqli_query($con,"SELECT memberid from household_members where householdID='$householdID'");// or die("Failed to connect to MySQL");
							$text="";
							while($row = @mysqli_fetch_array($result)){
								$text.=$row['memberid'].","; 
							}
							//echo $text; (for memberid_11)-> 11,12,70,71,72,
							//Now we've to trim the trailing extra ,
							$text=rtrim($text,","); // trims trailing comma
							// echo $text; 11,12,70,71,72
							//echo "<b> Household Member IDs : </b> $text<br>";
							
							$query2=  //YTD - Year to Date - Total donation in the current year
								"SELECT SUM(amount) amt
								FROM contrib
								WHERE memberid IN ($text) and YEAR(datereceived)= YEAR(NOW());";  //NOW() returns the current date and time.
							$query3=  /*Total Contribution amount overall*/
								"SELECT SUM(amount) amt
								FROM contrib
								WHERE memberid IN ($text);";
							$result = mysqli_query($con,$query2);// or die("Failed to connect to MySQL");
							$row = @mysqli_fetch_array($result);  //should not use die() as if there is no data, then die is evaluated
							echo "<b> Household Contribution YTD : </b>";
							if(!$row || $row['amt']==NULL)
								echo '<span class="pre">no data</span>';
							else
								echo '$'.$row['amt'];
							echo "<br>";
							
							$result = mysqli_query($con,$query3);// or die("Failed to connect to MySQL");
							$row = @mysqli_fetch_array($result);  //should not use die() as if there is no data, then die is evaluated
							echo "<b> Total Amount Donated : </b>";
							if(!$row || $row['amt']==NULL)
								echo '<span class="pre">no data</span>';
							else
								echo '$'.$row['amt'];
							echo "<br>";
							
					  //View household contrib history - START
					  
							$query4= /* More details related*/
							"SELECT c.*,s.contribdescription source,m.name
							FROM contrib c
							INNER JOIN contrib_source s ON c.contribsourceid=s.contribsourceid
							INNER JOIN contribmethod m ON c.contribmethod=m.id
							WHERE c.memberid in ($text)
							ORDER BY c.datereceived DESC;";
					  ?>
						<a href="#myModal" data-toggle="modal">View Household Contrib Hisotry</a>
						<!-- Modal -->
						<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-header">
								 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								 <h3 id="myModalLabel">Household Contrib History</h3>
							</div>
							<div class="modal-body">
								<table class="table table-condensed">
									<thead>
										<th>memberid</th>
										<th>contribid</th>
										<th>date</th>
										<th>amount</th>
										<th>source</th>
										<th>method</th>
										<th>comments</th>
										<th>ack?</th>
										<th>ackdate</th>
									</thead>
									<tbody class="small">
										<?php
										$result = mysqli_query($con,$query4);
										while($row = @mysqli_fetch_array($result)){
											$b=$row['contribid'];
											$c=$row['datereceived'];
											$d=$row['amount'];
											$e1=$row['contribsourceid'];
											$e2=$row['source'];
											$f1=$row['contribmethod'];
											$f2=$row['name'];
											$g=$row['comments'];
										?>
										<tr>
											<td><?php echo $row['memberid'];?></td>
											<td><?php echo $b;?></td>
											<td><?php echo $c;?></td>
											<td><?php echo $d;?></td>
											<td><?php echo $e2;?></td>
											<td><?php echo $f2;?></td>
											<td><?php echo $g;?></td>
									  </tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</fieldset>
				</div><br>
				<div class="row">
					<fieldset>
						<legend>Members <i class="fa fa-edit" title="Click on a row to edit the COMMENTS"></i></legend>
						<table class="table table-hover">
							<thead>
								<th>memberid</th>
								<th>name</th>
								<th>comments [editable]</th>
							</thead>
							<tbody>
								<?php
									//echo $text; 11,12,70,71,72
									$query5=
									"SELECT h.memberid,m.fname,m.lname,h.comments,h.hhrecordID
									FROM household_members h
									INNER JOIN member m ON h.memberid=m.memberid
									WHERE h.householdID='$householdID';";
									$result = mysqli_query($con,$query5);
									while($row = @mysqli_fetch_array($result)){
										$hid=$row['hhrecordID'];
										echo "<tr class='clickableRow' href='tr_household_comments.php?hid=$hid&id=$a'>";
								?>
										<td><?php echo $row['memberid'];?></td>
										<td><?php echo $row['fname']." ".$row['lname'];?></td>
										<td><?php echo $row['comments'];?></td>
										</tr>
									<?php
									}
									?>
							</tbody>
						</table>	
					</fieldset>
				</div>
			</div>
		</div>
	</div>
<?php mysqli_close($con);?>
</body>
</html>
