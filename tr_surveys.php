<?php

	include 'config.php'; //has database connection related global constants defined in it
	
	$a=$_GET['id'];
	
	$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
	if (mysqli_connect_errno()) // Check connection
	{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	//Query 1a: Date First Survey
	$result = mysqli_query($con,"select regioncode from region"); //dynamically pulling table names from region table and not hard-coding
	$column = array(); //creating an empty array
	while($row = mysqli_fetch_array($result)){
		 $column[] = $row['regioncode'];  //assigning regioncode (TWA,HAW,CIP etc) values to 'column' array
	}
	$text="CREATE OR REPLACE VIEW minview AS ";
	foreach($column as $regioncode){
		$text.="select min(date) date from $regioncode"."surveys where memberid=$a union ";  //appending using . operator
	}
	$text=rtrim($text); //trims any trailing spaces (at the end)
	$lastSpacePosition = strrpos($text, ' '); //strrpos (mind the double r) returns the position of the last occurrence of ' ' in $text, which is the space before last union
	$query1a = substr($text, 0, $lastSpacePosition); //extracts substring from first (0) to the position until last space - this does not contain last union
	$query1b="select min(date) date from minview;";

	
	//Query 2a: Total no. of surveys so far
	$text2="CREATE OR REPLACE VIEW totalview AS ";
	foreach($column as $regioncode){
		$text2.="select COUNT(*) c from $regioncode"."surveys where memberid=$a UNION ALL ";
	}
	$UNIONPosition2 = strrpos($text2, 'UNION'); //Find last UNION position
	$query2a = substr($text2, 0, $UNIONPosition2); 
	$query2b="select sum(c) total_surveys from totalview;";

//Query 3a: Date last survey
	$text3="CREATE OR REPLACE VIEW maxview AS ";
	foreach($column as $regioncode){
		$text3.="select max(date) date from $regioncode"."surveys where memberid=$a UNION ";
	}
	$text3=rtrim($text3); 
	$lastSpacePosition3 = strrpos($text3, ' ');
	$query3a = substr($text3, 0, $lastSpacePosition3); 
	$query3b="select max(date) date from maxview;";

//Query 4a- Number of surveys in last 12 months from *survey tables combined
	$text4="CREATE OR REPLACE VIEW countview AS ";
	foreach($column as $regioncode){
		$text4.="select COUNT(*) c from $regioncode"."surveys where memberid=$a AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL ";  
	}
	$UNIONPosition4 = strrpos($text4, 'UNION');
	$query4a = substr($text4, 0, $UNIONPosition4);  
	$query4b="select sum(c) surveys_last_12_months from countview;";
	

	//experience levels - showing distinct region level combo orderd by latest date
	//notes - if you are aliasing, 'as' keyword is optional
	$query5=
	"SELECT expid,region, EXP AS level, max(date) date 
	FROM member_exp
	WHERE memberid='$a'
	GROUP BY region,level
	ORDER BY DATE DESC;";
	
	$result = mysqli_query($con,"SELECT * FROM member WHERE memberid = '$a'");
	$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Surveys</title>
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
			font-size: small;
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
						<legend>Surveys data</legend>
						<table style="width:900px">
							<tr>
								<td>
									<?php
									$result=mysqli_query($con,$query1a);
									$result=mysqli_query($con,$query1b);
									$row = mysqli_fetch_array($result);
									?>
									<b>Date first survey:</b>
									<?php
									if(!$row)
										echo '<span class="pre">no data</span>';
									echo $row['date'];
									?>
								</td>
								<td>
									<?php
									$result=mysqli_query($con,$query2a);
									$result=mysqli_query($con,$query2b);
									$ts = mysqli_fetch_array($result);
									?>
									<b>Total number of surveys so far:</b>
									<?php
									if(!$row)
										echo '<span class="pre">no data</span>';
									echo $ts['total_surveys'];
									?>
								</td>
							</tr>
							<tr>
								<td>
									<?php
									$result=mysqli_query($con,$query3a);
									$result=mysqli_query($con,$query3b);
									$row = mysqli_fetch_array($result);
									?>
									<b>Most recent/latest survey:</b>
									<?php
									if(!$row)
										echo '<span class="pre">no data</span>';
									echo $row['date'];
									?>
								</td>
								<td>
									<?php
									$result=mysqli_query($con,$query4a);
									$result=mysqli_query($con,$query4b);
									$row = mysqli_fetch_array($result);
									?>
									<b>Number of survyes in last 12 months:</b>
									<?php
									if(!$row and $ts)
										echo '0';
									else if(!$row and !$ts)
										echo '<span class="pre">no data</span>';
									echo $row['surveys_last_12_months'];
									?>
								</td>
							</tr>
						</table>
					</fieldset>
				</div><br>
				<div class="row">
					<div class="span5">
						<b>Experience levels:</b> <i class="fa fa-edit fa-lg" title="Click on a row to edit it"></i>
						<table class="table table-hover">
							<thead>
								<th>Region</th>
								<th>Level</th>
								<th>Date</th>
							</thead>
							<?php
								$result=mysqli_query($con,$query5);
								while($row = mysqli_fetch_array($result)){
							?>
							<tbody>
								<?php
									$b=$row['region'];
									$c=$row['level'];
									$d=$row['date'];
									$e=$row['expid'];
									echo "<tr class='clickableRow' href='sidebar_add_member_exp.php?id=$a&region=$b&level=$c&date=$d&expid=$e'>";
									//refer for more info on passing multiple parameters in URL: http://html.net/tutorials/php/lesson10.php
								?>
									<td><?php echo $row['region']."<br>";?></td>
									<td><?php echo $row['level']."<br>";?></td>
									<td><?php echo $row['date']."<br>";?></td>
								</tr>
							</tbody>
							<?php
								}//end of while loop
							?>
							<tfoot>
									<tr><td><?php echo "<a href='sidebar_add_member_exp.php?id=$a'>";?><i class="fa fa-plus"></i><b> Add Exp</b></a></td></tr>
							</tfoot>
						</table>
					</div>
					<div class="span5">
						<b>Survey Overview:</b><br>
						<table class="table">
							<thead>
								<th>Region</th>
								<th>Total # Surveys</th>
								<th>Date of Last Survey</th>
							</thead>
							<tbody>
							<?php
								foreach($column as $regioncode){
									$query="select count(*) c from $regioncode"."surveys where memberid=$a";
									$result = mysqli_query($con,$query);
									$row=mysqli_fetch_array($result);
									$query="select max(date) d from $regioncode"."surveys where memberid=$a";
									$result = mysqli_query($con,$query);
									$row1=mysqli_fetch_array($result);
							?>
							<tr>
								<td><?php echo $regioncode?></td>
								<td><?php echo $row['c'];?></td>
								<td>
									<?php
										if(!isset($row1['d']))
											echo "-";
										echo $row1['d'];
										?>
								</td>
							</tr>
							<?php
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<b>Trips Overview:</b><br>  <!-- Still under development -->
					<div class="span5">
						<table class="table table-condensed">
							<thead>
								<th>Destination</th>
								<th>Start_Date</th>
							</thead>
							<tbody>
							<?php
								$query="SELECT t.destination,t.start_date FROM trips t INNER JOIN trip_participants tp
								ON t.tripID=tp.tripID where tp.memberid=$a";
								$result = mysqli_query($con,$query);
								while($row=mysqli_fetch_array($result)){
							?>
							<tr>
								<td><?php echo $row['destination'];?></td>
								<td><?php echo $row['start_date'];?></td>
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
	</div>
</body>
</html>
