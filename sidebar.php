
	<?php
		$pieces = explode("?", $_SERVER['REQUEST_URI']); // echo $pieces[0]; outputs part before ? in the url
	?>
	<ul class="nav nav-pills nav-stacked">
		<li><a href="#">Add New Member</a></li>
		<li <?php echo ($pieces[0]=='/a1/sidebar_add_donation.php')? "class='active'" : " ";?>><a href="sidebar_add_donation.php">Add Donation</a></li>
		<li><a href="#">Add To List</a></li>
		<li><a href="#">Add Log Entry</a></li>
		<li <?php echo ($pieces[0]=='/a1/sidebar_add_member_exp.php')? "class='active'" : " ";?>><a href="sidebar_add_member_exp.php">Add Member Exp</a></li>
		<li <?php echo ($pieces[0]=='/a1/sidebar_create_household.php')? "class='active'" : " ";?>><a href="sidebar_create_household.php">Create Household</a></li>
	</ul>
