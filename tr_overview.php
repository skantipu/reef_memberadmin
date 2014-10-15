<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Overview</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="csslocal.css"/>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> <!--using locally saved font-awesome icons -->
	<script type="text/javascript" src="jquery/jquery.min.js"></script> <!-- required for Donations tab dropdown-->
	<script src="js/bootstrap.js"></script> <!-- required for Donations tab dropdown -->
	<style>
		.panel-body{
		font-family:sans-serif;
		font-size: 14px;}
		.pre{
		font-family: monospace;
		color: grey;
		}
		.small
		{
			font-size: small;
		}
		body .modal
		{
			/* new custom width */
			width: 800px;
			/* must be half of the width, minus scrollbar on the left (30px) */
			
		}
		.small td{
			font-size: 12px;
		}
	</style>
</head>
<body>
	<?php
		include 'config.php'; //has database connection related global constants defined in it
		$a=$_GET['id']; //accessing form parameter through $_GET 
		$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) // Check connection
		{
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$result = mysqli_query($con,"SELECT * FROM member WHERE memberid = '$a'");
		$row = mysqli_fetch_array($result);
		$set=0;
		if($row['notes']!=NULL){
			$set=1; //this member has some comments in notes column
			$notes=$row['notes'];
		}
	//	echo $row['fname']." ".$row['lname'];
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
				<div class="row pad-left">
					<div class="row">
						<div class="span10">
							<div class="container">
								<strong style="padding-left: 200px;font-size: large;"><?php echo $name=$row['fname']." ".$row['lname']." (".$a.")";?><span style="font-weight: normal; font-size: 14px;">, member since </span> <?php echo $row['mem_since'];?></strong>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="span5">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h4>Contact Info</h4>
								</div>
								<div class="panel-body">
							      
								  <b>Email : </b><a href="mailto:<?php echo $row['email'];?>"><?php echo $row['email'];?></a><br>
								  <b>Phone : </b><?php echo $row['home'];?> <br>
								  <b>Address : </b><?php echo $row['address2'].", ".$row['city'].", ".$row['state'].", ".$row['country']." ".$row['zip'];?>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h4>Household Info</h4>
								</div>
								<div class="panel-body">
								  <?php include 'tr_overview_household.php'; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="span10">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h4>Donations</h4>
								</div>
								<div class="panel-body">
									<?php
									$result = mysqli_query($con,"SELECT c.datereceived,c.comments,c.amount,cs.contribdescription FROM contrib c inner join contrib_source cs
													   on c.contribsourceid=cs.contribsourceid WHERE c.memberid = '$a' order by datereceived desc limit 3");
									if(! $result )
									{
										die('Could not load data: ' . mysql_error());
									}
									?>
									<strong>3 most <i>recent</i> contributions of <?php echo $name;?> :</strong><br>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Date</th>
												<th>Amount ($)</th>
												<th>Comments</th>
												<th>Source</th>
											</tr>
										</thead>
										<?php
										
										while($row = mysqli_fetch_array($result))
										{
										?>
											<tbody>
												<tr class="small">
													<td><?php echo $row['datereceived']."<br>";?></td>
													<td><?php echo $row['amount']."<br>";?></td>
													<td><?php echo $row['comments']."<br>";?></td>
													<td><?php echo $row['contribdescription']."<br>";?></td>
													
												</tr>
											</tbody>
										<?php
										} 
										?>
									</table>

									<table style="width:740px">
										<tr>
											<?php
												$result = mysqli_query($con,"select year(datereceived) as yr,sum(amount) as total from contrib
																   where memberid='$a' group by yr order by datereceived desc limit 1;");
												if(! $result )
												{
													die('Could not load data: ' . mysql_error());
												}
												$lsya = mysqli_fetch_array($result);
											?>
											<td><strong>Last donated year & amount:</strong>
											<?php
												if(!$lsya)
													echo '<span class="pre">no data</span>';
												else
													echo $lsya['yr'].", "."$".$lsya['total'];
											?>
											</td>
											<?php
												$result = mysqli_query($con,"select sum(amount) as amt1 from contrib where memberid='$a' and datereceived < Now() and
																   datereceived > DATE_ADD(Now(), INTERVAL- 12 MONTH)group by memberid;");
												if(! $result )
												{
													die('Could not load data: ' . mysql_error());
												}
												$row = mysqli_fetch_array($result);
											?>
											<td><strong>Last 12 months amount & Donor type:</strong>
											<?php
												if(!$row && !$lsya) //no amount contributed in last 12 months and no last contributed year (prev. result) 
													echo '<span class="pre">no data</span>'; 
												elseif(!$row && $lsya) //no amount contributed in last 12 months, but some contribution in past
													echo "$0";
												else  //amount contributed in last 12 months
												{
													$amt=$row['amt1'];
													echo "$".$amt.", ";
													if($amt<500)
														echo '<span title="DONOR: $499 and less&#13;CONTRIBUTOR: $500 - $999&#13;SUSTAINER: $1000 - $2499&#13;BENEFACTOR: $2500 and above" style="background-color:#e8e8e8;">Donor</span>';
													else if ($amt>=500 && $amt<=999)
														echo '<span title="DONOR: $499 and less&#13;CONTRIBUTOR: $500 - $999&#13;SUSTAINER: $1000 - $2499&#13;BENEFACTOR: $2500 and above" style="background-color:#e8e8e8;">Contributor</span>';
													else if ($amt>=1000 && $amt<=2499)
														echo '<span title="DONOR: $499 and less&#13;CONTRIBUTOR: $500 - $999&#13;SUSTAINER: $1000 - $2499&#13;BENEFACTOR: $2500 and above" style="background-color:#e8e8e8;">Sustainer</span>';
													else
														echo '<span title="DONOR: $499 and less&#13;CONTRIBUTOR: $500 - $999&#13;SUSTAINER: $1000 - $2499&#13;BENEFACTOR: $2500 and above" style="background-color:#e8e8e8;">Benefactor</span>';
												}
													
											?>
											</td>	
										</tr>
										
										<tr>
											<?php
												$result = mysqli_query($con,"select sum(amount) as amt2 from contrib where memberid='$a' group by memberid;");
												if(! $result )
												{
													die('Could not load data: ' . mysql_error());
												}
												$row = mysqli_fetch_array($result);
											?>
											<td><strong>Total amount donated:</strong>
											<?php
												if(!$row)
													echo '<span class="pre">no data</span>';
												else
													echo "$".$row['amt2'];
											?>
											</td>
											<?php
												$result = mysqli_query($con,"select datereceived,amount from contrib where memberid='$a' order by datereceived limit 1;");
												$row = mysqli_fetch_array($result);
											?>
											<td><strong>First contribution date and amount:</strong>
											<?php
												if(!$row)
													echo '<span class="pre">no data</span>';
												else
													echo $row['datereceived'].", "."$".$row['amount'];
											?>
											</td>	
										</tr>
									</table>
									
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="span10">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h4>Survey Project</h4>
								</div>
								<div class="panel-body">
									<?php include 'tr_overview_survey_project.php'; ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="span5">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h4>Other Accounts</h4>
								</div>
								<div class="panel-body">
							      
								  Email : you@example.com <br>
								  Mobile : +92123456789 <br>
								  
									<h4><small>payment should be mabe by Bank Transfer</h4>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h4>Email Lists</h4>
								</div>
								<div class="panel-body">
							      
								  Email : you@example.com <br>
								  Mobile : +92123456789 <br>
								  
									<h4><small>payment should be mabe by Bank Transfer</h4>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="span5">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h4>Connections</h4>
								</div>
								<div class="panel-body">
							      
								  Email : you@example.com <br>
								  Mobile : +92123456789 <br>
								  
									<h4><small>payment should be mabe by Bank Transfer</h4>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h4>Lists</h4>
								</div>
								<div class="panel-body">
							      
								  Email : you@example.com <br>
								  Mobile : +92123456789 <br>
								  
									<h4><small>payment should be mabe by Bank Transfer</h4>
								</div>
							</div>
						</div>
					</div>
					<?php
					if($set==1){   //conditionally showing comments panel only if member has comments
					echo
					"<div class='row'>
						<div class='span10'>
							<div class='panel panel-success'>
								<div class='panel-heading'>
									<h4>Comments</h4>
								</div>
								<div class='panel-body'>".$notes.
								"</div>
							</div>
						</div>
					</div>";
					}
					?>
				</div>
			</div>
		</div>
	</div>
	
	
</body>
</html>
