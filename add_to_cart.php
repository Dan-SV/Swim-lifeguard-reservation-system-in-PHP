<?php
	session_start();
	if(!isset($_SESSION['ID']))
	{
		$_SESSION['ID']=uniqid();
	}
	$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
	die("error".mysqli_error($link));
	if(isset($_POST['courseid']))
	{
		$query="insert into cart (course_id, hold_expiry, quantity, session_id)
			values(".$_POST['courseid'].", NOW() , ".$_POST['participants'].", '".$_SESSION['ID']."')";
		mysqli_query($conn, $query) or die(mysqli_error($conn));
		header("Location: cart.php");
	}
	else {
		echo "Error: please select a course";
		include("view_course.php");
	}
?>