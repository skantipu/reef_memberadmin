<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Search results</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="csslocal.css"/>
	<link rel="stylesheet" href="css/jquery-ui.min.css"/>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> <!--using locally saved font-awesome icons -->
	<script type="text/javascript" src="jquery/jquery.min.js"></script>
	<script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<style>
		tr:hover {
		cursor: pointer;
		}
	</style>
	<script>
		jQuery(document).ready(function($) {
			$(".clickableRow").click(function() {
					window.document.location = $(this).attr("href");
			});
		});
		$(function() {
			$(".auto").autocomplete({
				source: "search_autocomplete.php",
				minLength: 3,
				delay: 900
			});				
		});
	</script>
</head>
<body>
	<div class="row pad-left pad-top">
		<?php include 'header.php';?>
	</div>
	<div class="row">
		<div class="span2 pad-left">
			<?php include 'sidebar.php';?>
		</div>
		<div class="span14">
			<div class="row">
				<?php include 'search_autocomplete.html';?>	
			</div>
			<div class="row pad-left" style="overflow:auto">
				<table class="table table-hover">
					<thead title="Clickable rows">
						<tr>
							<th>id</th>
							<th>Name</th>
							<th>email</th>
							<th>City</th>
							<th>State</th>
							<th>Country</th>
							<th>Zip</th>
							<th>Join-Date</th>
						</tr>
					</thead>
					<?php
						include 'config.php'; //has database connection related global constants defined in it
						$term=$_GET['search_term'];
						$term=trim($term); //trim(): Strip whitespace (or other characters) from the beginning and end of a string
						$pieces = explode(" ", $term);
						$con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
						// Check connection
						if (mysqli_connect_errno())
						{
						  echo "Failed to connect to MySQL: " . mysqli_connect_error();
						}
						if(is_numeric($pieces[0])) //if first piece is an integer (through auto population)
						{
							$result = mysqli_query($con,"SELECT memberid,fname,lname,email,country,state,city,zip,mem_since FROM member WHERE memberid = '$pieces[0]'"); 
						}
						else
						{
							if(!isset($pieces[1])) //if there is only one word in the search input
								$result = mysqli_query($con,"SELECT memberid,fname,lname,email,country,state,city,zip,mem_since FROM member WHERE fname like '$pieces[0]' or lname like '$pieces[0]'"); 
							else
								$result = mysqli_query($con,"SELECT memberid,fname,lname,email,country,state,city,zip,mem_since FROM member WHERE fname like '%$pieces[0]%' and lname like '%$pieces[1]%'"); 
						}
						while($row = mysqli_fetch_array($result))
						{
							$name=$row['fname'].' '.$row['lname'];
					?>
							<tbody>
								<?php $a=$row['memberid'];?>
								<?php echo "<tr class='clickableRow' href='tr_overview.php?id=$a'>";?>
									<td><?php echo $row['memberid']?></td>
									<td><?php echo $name?></td>
									<td><?php echo $row['email']?></td>
									<td><?php echo $row['city']?></td> 
									<td><?php echo $row['state']?></td>
									<td><?php echo $row['country']?></td>
									<td><?php echo $row['zip']?></td>
									<td><?php echo $row['mem_since']?></td>
								</tr>
							</tbody>
						<?php
						}
							mysqli_close($con);
						?>
				</table>	
			</div>
		</div>
	</div>
</body>
</html>