<?php
	include 'config.php';
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
	if (mysqli_connect_errno()) // Check connection
	{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
		
	$query1=  //YTD for all members - Year to Date - Total donation in the current year
	"SELECT SUM(amount) amt from contrib WHERE YEAR(datereceived)= YEAR(NOW());";

	$query2=  /*Total Contribution amount for all members in the last 12 months*/
	"SELECT SUM(amount) amt from contrib WHERE datereceived < NOW() AND datereceived > DATE_ADD(NOW(), INTERVAL- 12 MONTH);";
	
	$query3=  /*YTD for top 10 donors*/
	"SELECT c.memberid, m.fname, m.lname, c.datereceived, SUM(c.amount) amt
	FROM contrib c INNER JOIN member m ON c.memberid=m.memberid
	WHERE YEAR(c.datereceived)= YEAR(NOW()) GROUP BY c.memberid ORDER BY amt DESC  LIMIT 10;";
	
	$query4=  /*Top 10 donors - Last 12 months contrib amount*/
	"SELECT c.memberid, m.fname, m.lname, c.datereceived, SUM(c.amount) amt
	FROM contrib c INNER JOIN member m ON c.memberid=m.memberid
	WHERE c.datereceived < NOW() AND c.datereceived > DATE_ADD(NOW(), INTERVAL- 12 MONTH) GROUP BY c.memberid ORDER BY amt DESC  LIMIT 10;";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Donations</title>
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
	<div class="container">
		<div class="row">
			<div class="span2">
				<fieldset>
					<legend>Quick Stats</legend>
					<strong>YTD Total Donations: </strong>
					<?php
						$result = mysqli_query($con,$query1);
						$row = mysqli_fetch_array($result);
						echo "$".number_format($row['amt']);
					?><br><br>
					<strong>Last 12 month Total Donations: </strong>
					<?php
						$result = mysqli_query($con,$query2);
						$row = mysqli_fetch_array($result);
						echo "$".number_format($row['amt']);
					?>
				</fieldset>
			</div>
			<div class="span5">
				<fieldset>
					<legend>Top 10 Donors - YTD</legend>
					<table class="table table-condensed">
						<thead>
							<th>Member ID</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Date Received</th>
							<th>Amount ($)</th>
						</thead>
					<?php
						$result = mysqli_query($con,$query3);
						while($row = mysqli_fetch_array($result)){
					?>
						<tbody>
							<tr>
								<td><?php echo $row['memberid'];?></td>
								<td><?php echo $row['fname'];?></td>
								<td><?php echo $row['lname'];?></td>
								<td class="small"><?php echo $row['datereceived'];?></td>
								<td><?php echo $row['amt'];?></td>
							</tr>
					<?php
						}
					?>
						</tbody>
					</table>
				</fieldset>
			</div>
			<div class="span5">
				<fieldset>
					<legend>Top 10 Donors - Last 12 Months</legend>
					<table class="table table-condensed">
						<thead>
							<th>Member ID</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Date Received</th>
							<th>Amount ($)</th>
						</thead>
					<?php
						$result = mysqli_query($con,$query4);
						while($row = mysqli_fetch_array($result)){
					?>
						<tbody>
							<tr>
								<td><?php echo $row['memberid'];?></td>
								<td><?php echo $row['fname'];?></td>
								<td><?php echo $row['lname'];?></td>
								<td><?php echo $row['datereceived'];?></td>
								<td><?php echo $row['amt'];?></td>
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
	
<?php mysqli_close($con);?>
</body>
</html>