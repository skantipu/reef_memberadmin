<?php
	session_start();
	require 'auth1.php'; //checking if user is logged in first
?>


<div class="span14">
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="#">REEF</a>
			<a class="brand" href="javascript:history.back()"><i class="fa fa-chevron-circle-left"></i></a>
			<a class="brand" href="javascript:history.forward()"><i class="fa fa-chevron-circle-right"></i></a>
			<ul class="nav">
				<?php $pieces = explode("?", $_SERVER['REQUEST_URI']);?>
				<li <?php echo ($_SERVER['REQUEST_URI'] == '/a1/homepage.php' || $pieces[0]=='/a1/table_results.php' || $pieces[0]=='/a1/tr_overview.php'
									 || $pieces[0]=='/a1/tr_contact_details.php' || $pieces[0]=='/a1/tr_donations1.php'|| $pieces[0]=='/a1/tr_donations2.php'
									 || $pieces[0]=='/a1/tr_surveys.php' || $pieces[0]=='/a1/sidebar_add_member_exp.php' || $pieces[0]=='/a1/sidebar_add_donation.php'
									 || $pieces[0]=='/a1/sidebar_create_household.php' || $pieces[0]=='/a1/tr_household.php' || $pieces[0]=='/a1/tr_log.php')
									 ? 'class="active"' : '';?>>
					<a href="homepage.php"><i class="fa fa-home"></i> Members</a>
				</li>
				<li <?php echo ($_SERVER['REQUEST_URI'] == '/a1/donations.php') ? 'class="active"' : '';?>>
					<a href="donations.php"><i class="fa fa-dollar"></i> Donations</a>
				</li>
				<li>
					<a href="#"><i class="fa fa-globe"></i> Query Segments/Lists</a>
				</li>
				<li>
					<a href="#"><i class="fa fa-anchor"></i> Survey Project</a>
				</li>
				<li>
					<a href="#"><i class="fa fa-envelope"></i> Mailings</a>
				</li>
				<li>
					<a href="#"><i class="fa fa-bar-chart-o"></i> Reports</a>
				</li>
				<li>
					<a href="signout.php" title="Sign Out" style="color: blue"><i class="fa fa-power-off"></i> <?php echo $_SESSION['firstname'];?></a>
				</li>
			</ul>
		</div>
	</div>
</div>
