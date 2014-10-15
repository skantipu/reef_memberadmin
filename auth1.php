<?php   //this file is included in header.php
if(!isset($_SESSION['username'])) //if user is not logged in, as session[username] is not set
	header('Location: /a1/auth2.php');
?>