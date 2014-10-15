<?php

	include 'config.php'; //has database connection related global constants defined in it
	
	if (isset($_GET['term']))
	{
		$return_arr = array();
	
		try
		{
			$conn = new PDO("mysql:host=".DB_SERVER.";port=3306;dbname=".DB_NAME, DB_USER, DB_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
			$stmt = $conn->prepare('SELECT memberid,fname,lname,city,zip FROM member WHERE memberid LIKE :term or fname LIKE :term or lname LIKE :term
		  or city LIKE :term or zip LIKE :term');
			$stmt->execute(array('term' => '%'.$_GET['term'].'%'));
  
			while($row = $stmt->fetch())
			{
			  //  $return_arr[] =  $row['country'];
				$return_arr[] = array(
				  'label' => $row['memberid'] . ' - ' . $row['fname'] . ' - ' . $row['lname'] . ' - ' . $row['city'] . ' - ' . $row['zip'],
				  'value' => $row['memberid'].' '.$row['fname'].' '.$row['lname'] 
				);
			}
		}
		catch(PDOException $e)
		{
			 echo 'ERROR: ' . $e->getMessage();
		}
		 /* Toss back results as json encoded array. */
		 echo json_encode($return_arr);
	}

?>