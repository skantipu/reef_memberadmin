<?php
	//$a=$_GET['id']; is NOT required as this file is included in tr_overview.php
	 $query_oh_1=
		  "SELECT hm.householdID,h.informal_hhaddressee
		  FROM household_members hm
		  INNER JOIN household h ON hm.householdID=h.householdID
		  WHERE hm.memberid = $a;";
	 $result = mysqli_query($con,$query_oh_1) or die("Failed to connect to MySQL");
	 $row = @mysqli_fetch_array($result);  //should not use die() as if there is no data, then die is evaluated
	 $householdID_oh=$row['householdID'];
	 echo "<b> HouseholdID : </b> $householdID_oh<br>";
	 echo "<b> Informal Addressee : </b>".$row['informal_hhaddressee']."<br>"; //used . operator as ' ' are used
	 
	 $result = mysqli_query($con,"SELECT memberid from household_members where householdID='$householdID_oh'");// or die("Failed to connect to MySQL");
	 $column_oh = array(); //creating an empty array
	 while($row = @mysqli_fetch_array($result)){
		  $column_oh[] = $row['memberid']; 
	 }
	 $text_oh="";
	 foreach($column_oh as $memberid_oh){
		$text_oh.=$memberid_oh.",";
	 }
	 //echo $text_oh; (for memberid_11)-> 11,12,70,71,72,
	 //Now we've to trim the trailing extra ,
	 $text_oh=rtrim($text_oh,","); // trims trailing comma
	 //echo $text_oh; 11,12,70,71,72
	 echo "<b> Household Member IDs : </b> $text_oh<br>";
	 
	 $query_oh_2=  //YTD - Year to Date - Total donation in the current year
		  "SELECT SUM(amount) amt
		  FROM contrib
		  WHERE memberid IN ($text_oh) and YEAR(datereceived)= YEAR(NOW());";
	 $result = mysqli_query($con,$query_oh_2);// or die("Failed to connect to MySQL");
	 $row = @mysqli_fetch_array($result);  //should not use die() as if there is no data, then die is evaluated
	 echo "<b> Household Contribution YTD : </b>";
	 if(!$row || $row['amt']==NULL)
		  echo '<span class="pre">no data</span>';
	 else
		  echo '$'.$row['amt'];
	 echo "<br>";
	 
	 
	 
//View household contrib history - START

	 $query_oh_3= /* More details related*/
	 "SELECT c.*,s.contribdescription source,m.name
	 FROM contrib c
	 INNER JOIN contrib_source s ON c.contribsourceid=s.contribsourceid
	 INNER JOIN contribmethod m ON c.contribmethod=m.id
	 WHERE c.memberid in ($text_oh)
	 ORDER BY c.datereceived DESC;";
?>
	 <a href="#myModal" data-toggle="modal">View Household Contrib Hisotry</a>
	  
	 <!-- Modal -->
	 <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h3 id="myModalLabel">Household Contrib History</h3>
		  </div>
		  <div class="modal-body">
				<table class="table table-condensed">
					 <thead>
						 <th>memberid</th>
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
						  $result = mysqli_query($con,$query_oh_3);
						  while($row = @mysqli_fetch_array($result)){
								$b=$row['contribid'];
								$c=$row['datereceived'];
								$d=$row['amount'];
								$e1=$row['contribsourceid'];
								$e2=$row['source'];
								$f1=$row['contribmethod'];
								$f2=$row['name'];
								$g=$row['comments'];
						  ?>
						   <tr>
								<td><?php echo $row['memberid'];?></td>
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
					 </tbody>
				</table>
		  </div>
	 </div>
