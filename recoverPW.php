<?php
	session_start();
	$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
	die("error".mysqli_error($link));
	{
		$_SESSION['Error'] = "Incorrect email address or token";
		$valid=false;
		if(empty($_POST["email"])){
			$_SESSION['Error'] = "No email address entered!";
		}
		elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$_SESSION['Error'] = "Invalid email format";
		}
		elseif(empty($_POST["password"])){
			$_SESSION['Error'] = "No password entered!";
		}
		elseif(empty($_POST["token"])){
			$_SESSION['Error'] = "No token entered!";
		}
		else
		{
			$em=$_POST["email"];
			if($query=mysqli_query($conn,"SELECT token FROM recoverytokens where email='$em' LIMIT 1")) //, expiry
			{   
				if($tokens=mysqli_fetch_assoc($query))
				{
					if($tokens['token']== $_POST["token"])
					{						
						if($cidquery=mysqli_query($conn,"SELECT clientID FROM client where email='$em' LIMIT 1"))
						{
							if($clientQ=mysqli_fetch_assoc($cidquery))
							{
								$_SESSION["email"]=$em;
								$_SESSION["clientID"]=$clientQ['clientID'];
								$pw=$_POST["password"];
								$query="UPDATE client SET auth='$pw' WHERE email='$em'";
								mysqli_query($conn, $query) or die(mysqli_error($conn));
								$query="DELETE FROM recoverytokens WHERE email='$em'";
								mysqli_query($conn, $query) or die(mysqli_error($conn));
								$_SESSION['Error']="Password successfully reset";
								header("Location: index.php");
								exit;
							}
						}
					}
				}
			}
		}
	}
	include("recovery.php")
?>