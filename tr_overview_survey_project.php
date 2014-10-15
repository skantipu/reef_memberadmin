<?php
	//$a=$_GET['id']; is NOT required as this file is included in tr_overview.php
	
	
	//Query 1a: Date First Survey
	$result = mysqli_query($con,"select regioncode from region"); //dynamically pulling table names from region table and not hard-coding
	$column = array(); //creating an empty array
	while($row = mysqli_fetch_array($result)){
		 $column[] = $row['regioncode'];  //assigning regioncode (TWA,HAW,CIP etc) values to 'column' array
	}
	//print_r ($column); --> output: Array ( [0] => CIP [1] => HAW [2] => NE [3] => PAC [4] => SAS [5] => SOP [6] => TEP [7] => TWA ) 
	
	$text="CREATE OR REPLACE VIEW minview AS ";
	
	foreach($column as $regioncode){
		$text.="select min(date) date from $regioncode"."surveys where memberid=$a union ";  //appending using . operator
	}
	
	/* echo $text; Output: CREATE OR REPLACE VIEW minview AS select min(date) date from CIPsurveys where memberid=11 union
	select min(date) date from HAWsurveys where memberid=11 union select min(date) date from NEsurveys where memberid=11
	union select min(date) date from PACsurveys where memberid=11 union select min(date) date from SASsurveys where
	memberid=11 union select min(date) date from SOPsurveys where memberid=11 union select min(date) date from TEPsurveys
	where memberid=11 union select min(date) date from TWAsurveys where memberid=11 union  */
	
	//Now we've to trim last extra 'union' and space at the end
	
	$text=rtrim($text); //trims any trailing spaces (at the end)
	$lastSpacePosition = strrpos($text, ' '); //strrpos (mind the double r) returns the position of the last occurrence of ' ' in $text, which is the space before last union
	
	$query1a = substr($text, 0, $lastSpacePosition); //extracts substring from first (0) to the position until last space - this does not contain last union
	
	$query1b="select min(date) date from minview;";

	/* Date First Survey - Below refers previous query where table names are hard-coded. It will be a problem if a new *surveys
	 * table is added, which is the reason behind dynamically pulling table names from 'region' table. Refer above.
	
	$query1a=
	"CREATE OR
	REPLACE VIEW minview AS
	select min(date) date from cipsurveys where memberid='$a' 
	union
	select min(date) from hawsurveys where memberid='$a'
	union
	select min(date) from nesurveys where memberid='$a'
	union
	select min(date) from pacsurveys where memberid='$a'
	union
	select min(date) from sassurveys where memberid='$a'
	union
	select min(date) from sopsurveys where memberid='$a'
	union
	select min(date) from tepsurveys where memberid='$a'
	union
	select min(date) from twasurveys where memberid='$a';";

	$query1b="select min(date) date from minview;";  when aliasing, 'as' is optional */
	
	
	
	//Query 2a: Total no. of surveys so far
	$text2="CREATE OR REPLACE VIEW totalview AS ";
	foreach($column as $regioncode){
		$text2.="select COUNT(*) c from $regioncode"."surveys where memberid=$a UNION ALL ";
	}
	$UNIONPosition2 = strrpos($text2, 'UNION'); //Find last UNION position
	$query2a = substr($text2, 0, $UNIONPosition2); 
	$query2b="select sum(c) total_surveys from totalview;";
	
/* old version - hardcoded table names
	$query2a=
	"CREATE OR
	REPLACE VIEW totalview AS
	SELECT COUNT(*) c
	FROM cipsurveys
	WHERE memberid='$a' UNION ALL
	SELECT COUNT(*) c
	FROM hawsurveys
	WHERE memberid='$a' UNION ALL
	SELECT COUNT(*) c
	FROM nesurveys
	WHERE memberid='$a' UNION ALL
	SELECT COUNT(*) c
	FROM pacsurveys
	WHERE memberid='$a' UNION ALL
	SELECT COUNT(*) c
	FROM sassurveys
	WHERE memberid='$a' UNION ALL
	SELECT COUNT(*) c
	FROM sopsurveys
	WHERE memberid='$a' UNION ALL
	SELECT COUNT(*) c
	FROM tepsurveys
	WHERE memberid='$a' UNION ALL
	SELECT COUNT(*) c
	FROM twasurveys
	WHERE memberid='$a';";
	
	$query2b="select sum(c) total_surveys from totalview;";
*/	

//Query 3a: Date last survey
	$text3="CREATE OR REPLACE VIEW maxview AS ";
	foreach($column as $regioncode){
		$text3.="select max(date) date from $regioncode"."surveys where memberid=$a UNION ";
	}
	$text3=rtrim($text3); 
	$lastSpacePosition3 = strrpos($text3, ' ');
	$query3a = substr($text3, 0, $lastSpacePosition3); 
	$query3b="select max(date) date from maxview;";
/*
	//Date last survey
	$query3a=
	"CREATE OR
	REPLACE VIEW maxview AS
	select max(date) date from cipsurveys where memberid='$a' 
	union
	select max(date) from hawsurveys where memberid='$a'
	union
	select max(date) from nesurveys where memberid='$a'
	union
	select max(date) from pacsurveys where memberid='$a'
	union
	select max(date) from sassurveys where memberid='$a'
	union
	select max(date) from sopsurveys where memberid='$a'
	union
	select max(date) from tepsurveys where memberid='$a'
	union
	select max(date) from twasurveys where memberid='$a';";
	
	$query3b="select max(date) date from maxview;";
*/

//Query 4a- Number of surveys in last 12 months from *survey tables combined
	$text4="CREATE OR REPLACE VIEW countview AS ";
	foreach($column as $regioncode){
		$text4.="select COUNT(*) c from $regioncode"."surveys where memberid=$a AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL ";  
	}
	$UNIONPosition4 = strrpos($text4, 'UNION');
	$query4a = substr($text4, 0, $UNIONPosition4);  
	$query4b="select sum(c) surveys_last_12_months from countview;";
	
/* Query 4a old version	
	//Number of surveys in last 12 months from *survey tables combined
	$query4a=
	"CREATE OR
	REPLACE VIEW countview AS
	SELECT COUNT(*) c
	FROM cipsurveys
	WHERE memberid='$a' AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
	SELECT COUNT(*) c
	FROM hawsurveys
	WHERE memberid='$a' AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
	SELECT COUNT(*) c
	FROM nesurveys
	WHERE memberid='$a' AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
	SELECT COUNT(*) c
	FROM pacsurveys
	WHERE memberid='$a' AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
	SELECT COUNT(*) c
	FROM sassurveys
	WHERE memberid='$a' AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
	SELECT COUNT(*) c
	FROM sopsurveys
	WHERE memberid='$a' AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
	SELECT COUNT(*) c
	FROM tepsurveys
	WHERE memberid='$a' AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
	SELECT COUNT(*) c
	FROM twasurveys
	WHERE memberid='$a' AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH);";
	
	$query4b="select sum(c) surveys_last_12_months from countview;";
*/	

	//experience levels - showing distinct region level combo orderd by latest date
	//notes - if you are aliasing, 'as' keyword is optional
	$query5=
	"SELECT region, EXP AS level, max(date) date 
	FROM member_exp
	WHERE memberid='$a'
	GROUP BY region,level
	ORDER BY DATE DESC;";
?>

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
			<b>Number of surveys in last 12 months:</b>
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

<b>Experience levels:</b><br>
<table class="table table-condensed">
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
		<tr>
			<td><?php echo $row['region']."<br>";?></td>
			<td><?php echo $row['level']."<br>";?></td>
			<td><?php echo $row['date']."<br>";?></td>
		</tr>
	</tbody>
	<?php
		}//end of while loop
	?>
</table>