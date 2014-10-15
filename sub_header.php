
	<?php
		$pieces = explode("?", $_SERVER['REQUEST_URI']); // echo $pieces[0]; outputs /a1/tr_overview.php which is the part before ? in the url
	?>
	<ul class="nav nav-tabs">
		<li <?php echo ($pieces[0]=='/a1/tr_overview.php')? "class='active'" : " ";?>>  <!--either use " '' " or ' "" ' for echo -->
			<?php echo "<a href='tr_overview.php?id=$a'><i class='fa fa-th-large'></i> Overview</a>";?>
		</li>
		<li <?php echo ($pieces[0]=='/a1/tr_contact_details.php')? 'class="active"' : '';?>>
			<?php echo "<a href='tr_contact_details.php?id=$a'><i class='fa fa-phone'></i> Contact Details</a>";?>
		</li>
		<li <?php echo ($pieces[0]=='/a1/tr_donations1.php' || $pieces[0]=='/a1/tr_donations2.php') ? 'class="dropdown active"' : 'class="dropdown"';?>> <!-- staring nav tag with dropdown-->
			<a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-dollar"></i> Donations
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu">
				<li><?php echo "<a href='tr_donations1.php?id=$a'>Donations Main</a>";?></li>
				<li><?php echo "<a href='tr_donations2.php?id=$a'>Member Donations Query</a>";?></li>
			</ul>
		</li>
		<li <?php echo ($pieces[0]=='/a1/tr_surveys.php')? "class='active'" : " ";?>>
			<?php echo "<a href='tr_surveys.php?id=$a'><i class='fa fa-anchor'></i> Surveys</a>";?>
		</li>
		<li <?php echo ($pieces[0]=='/a1/tr_log.php')? "class='active'" : " ";?>>
			<?php echo "<a href='tr_log.php?id=$a'><i class='fa fa-database'></i> Log</a>";?>
		</li>
		<li <?php echo ($pieces[0]=='/a1/tr_household.php')? "class='active'" : " ";?>>
			<?php echo "<a href='tr_household.php?id=$a'><i class='fa fa-users'></i> Household</a>";?>
		</li>
	<!--	<li>
			<a href="#"><i class="fa fa-history"></i> History</a>
		</li>
	-->
	</ul>

