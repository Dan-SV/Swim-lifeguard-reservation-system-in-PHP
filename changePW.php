<?php
	session_start();
	if(!isset($_SESSION["email"]))
	{
		include "index.php";
	}
	$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
	die("error".mysqli_error($link));
	{
		$valid=false;
		if(empty($_POST["oldPassword"])){
			echo "Old password not entered!";
		}
		elseif(empty($_POST["newPassword"])){
			echo "New password not entered!";
		}
		else
		{
			$em=$_SESSION["email"];
			if($cidquery=mysqli_query($conn,"SELECT clientID, auth FROM client where email='$em' LIMIT 1"))
			{
				if($clientQ=mysqli_fetch_assoc($cidquery))
				{
					$_SESSION["email"]=$em;
					$_SESSION["clientID"]=$clientQ['clientID'];
					$newPW=$_POST["newPassword"];
					if($_POST["oldPassword"]==$clientQ['auth'])
					{
						$query="UPDATE client SET auth='$newPW' WHERE email='$em'";
						mysqli_query($conn, $query) or die(mysqli_error($conn));
						echo "Password successfully changed";
						header("Location: index.php");
						exit;
						//$valid=true;
					}
					else
						echo "Incorrect old password";
				}
				else
					echo "Account retrieval error";
			}
			else
				echo "Authentication Error";
		}
		include "changePasswordForm.php";
	}
?>