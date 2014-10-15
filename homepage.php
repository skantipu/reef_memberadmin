<!DOCTYPE html>
<html lang="en">
<head>
	<title>REEF Member Admin - Home Page</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="csslocal.css"/>  <!-- defined pad-left pad-top-->
	<link rel="stylesheet" href="css/jquery-ui.min.css"/> <!-- for nice display of search results of auto suggest feature -->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> <!--using locally saved font-awesome icons -->
	<script type="text/javascript" src="jquery/jquery.min.js"></script> <!-- for auto-suggest feature -->
	<script type="text/javascript" src="jquery/jquery-ui.min.js"></script> <!-- for dropdown in auto-suggest feature -->
	<!-- removed external link dependance and saved everything on the server, avoiding network roundtripping problems -->
	<script type="text/javascript">
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
		<div class="span10">
			<div class="row">
				<?php include 'search_autocomplete.html';?>	
			</div>
		</div>
	</div>
</body>
</html>