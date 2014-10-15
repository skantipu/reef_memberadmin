<?php
	$a=$_GET['id'];
	include 'config.php';
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
	if (mysqli_connect_errno()) // Check connection
	{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$result = mysqli_query($con,"SELECT fname,lname,mem_since FROM member WHERE memberid = '$a'");
	$row = mysqli_fetch_array($result);
	
	$query1=  //YTD - Year to Date - Total donation in the current year
	"SELECT SUM(amount) amt
	FROM contrib
	WHERE memberid=$a and YEAR(datereceived)= YEAR(NOW());";  //NOW() returns the current date and time.

	$query2=  /*Total Contribution amount in the last 12 months*/
	"SELECT SUM(amount) amt
	FROM contrib
	WHERE memberid=$a AND datereceived < NOW() AND datereceived > DATE_ADD(NOW(), INTERVAL- 12 MONTH)
	GROUP BY memberid;";
	
	$query3=  /*Total Contribution amount overall*/
	"SELECT SUM(amount) amt
	FROM contrib
	WHERE memberid=$a;";
	
	$query4=  /*Average Contribution amount overall*/
	"SELECT TRUNCATE(AVG(amount),1) amt
	FROM contrib
	WHERE memberid=$a;";
	
	$query5=  /*Number of donations*/
	"SELECT COUNT(*) count
	FROM contrib
	WHERE memberid=$a;";
	
	$query6= /* Months from last donation */
	"SELECT FLOOR(DATEDIFF(CURDATE(), MAX(datereceived))/30) months
	FROM contrib
	WHERE memberid=$a;";
	
	$query7= //Total by Year
	"SELECT YEAR(datereceived) year, SUM(amount) sum, COUNT(*) number
	FROM contrib
	WHERE memberid=$a
	GROUP BY year
	ORDER BY year desc;";
	
	$query8= /* More details related*/
	"SELECT c.*,s.contribdescription source,m.name
	FROM contrib c
	INNER JOIN contrib_source s ON c.contribsourceid=s.contribsourceid
	INNER JOIN contribmethod m ON c.contribmethod=m.id
	WHERE c.memberid=$a
	ORDER BY c.datereceived DESC;";	
?>

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
	<script>
		jQuery(document).ready(function($) {
			$(".clickableRow").click(function() {
					window.document.location = $(this).attr("href");
			});
		});
	</script>
	<style>
		tr.clickableRow:hover {
		cursor: pointer;
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

				<div class="row">
					<fieldset>
						<legend>Basic</legend>
						<table class="span10">
							<tbody>
								<tr>
									<td><b>Year To Date ($): </b>
										<?php
											$result = mysqli_query($con,$query1);
											$row = mysqli_fetch_array($result);
											if(!$row || $row['amt']==NULL)
												echo '<span class="pre">no data</span>';
											else
												echo $row['amt'];
										?>
									</td>
									<td><b>Total Amount Last 12 months ($): </b>
										<?php
											$result = mysqli_query($con,$query2);
											$row = mysqli_fetch_array($result);
											if(!$row || $row['amt']==NULL)
												echo '<span class="pre">no data</span>';
											else
												echo $row['amt'];
										?>
									</td>
									<td><b>Total Overall ($): </b>
										<?php
											$result = mysqli_query($con,$query3);
											$row = mysqli_fetch_array($result);
											if(!$row || $row['amt']==NULL)
												echo '<span class="pre">no data</span>';
											else
												echo $row['amt'];
										?>
									</td>
								</tr>
								<tr>
									<td><b>Average Donation ($): </b>
										<?php
											$result = mysqli_query($con,$query4);
											$row = mysqli_fetch_array($result);
											if(!$row || $row['amt']==NULL)
												echo '<span class="pre">no data</span>';
											else
												echo $row['amt'];
										?>
									</td>
									<td><b>Number of Donations: </b>
										<?php
											$result = mysqli_query($con,$query5);
											$row = mysqli_fetch_array($result);
											if(!$row || $row['count']==NULL)
												echo '<span class="pre">no data</span>';
											else
												echo $row['count'];
										?>
									</td>
									<td><b>Months From Last Donation: </b>
										<?php
											$result = mysqli_query($con,$query6);
											$row = mysqli_fetch_array($result);
											if(!$row || $row['months']==NULL)
												echo '<span class="pre">no data</span>';
											else
												echo $row['months'];
										?>
									</td>
								</tr>
							</tbody>	 
						</table>
					</fieldset>
					<br>
					<fieldset>
						<legend>Total By Year</legend>
						<div class="span5">
							<table class="table table-condensed">
								<thead>
									<th>Year</th>
									<th>Amount ($)</th>
									<th>#Donations</th>
								</thead>
								<tbody>
								<?php
								$result = mysqli_query($con,$query7);
								while($row = mysqli_fetch_array($result)){
								?>
									<tr>
										<td><?php echo $row['year'];?></td>
										<td><?php echo $row['sum'];?></td>
										<td><?php echo $row['number'];?></td>
									</tr>
								<?php
								}
								?>
								</tbody>
							</table>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="row span12">
				<fieldset>
					<legend>More details <i class="fa fa-edit" title="Click on a row to edit it"></i></legend> 
					<table class="table table-hover table-condensed">
						<thead>
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
							$result = mysqli_query($con,$query8);
							while($row = mysqli_fetch_array($result)){
								$b=$row['contribid'];
								$c=$row['datereceived'];
								$d=$row['amount'];
								$e1=$row['contribsourceid'];
								$e2=$row['source'];
								$f1=$row['contribmethod'];
								$f2=$row['name'];
								$g=$row['comments'];
							echo "<tr class='clickableRow' href='sidebar_add_donation.php?id=$a&cid=$b&amount=$d&sourceid=$e1
							&source=$e2&date=$c&methodid=$f1&method=$f2&comment=$g'>";
							?>
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
							<tfoot>
								<tr><td><?php echo "<a href='sidebar_add_donation.php?id=$a'>";?><i class="fa fa-plus"></i><b> Add Donation</b></a></td></tr>
							</tfoot>
						</tbody>
					</table>
				</fieldset>
			</div>
		</div>
	</div>
<?php mysqli_close($con);?>
</body>
</html>