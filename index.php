<?php
	session_start(); 
	if (isset($_SESSION['clientID'])){
		include "account.php";
		exit;
	}
	else if(isset($_POST['request']))
	{
		switch($_POST['request'])
		{
			case "login":
			{
				$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
				die("error".mysqli_error($link));
				{
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
					else{
						$em=$_POST["email"];
						if($query=mysqli_query($conn,"SELECT clientID, auth FROM client where email='$em' LIMIT 1"))
						{   
							if($pw=mysqli_fetch_assoc($query))
								if($pw['auth']== $_POST["password"])
								{
									$_SESSION["email"]=$em;
									$_SESSION["clientID"]=$pw['clientID'];
									header("Location: ".$_POST['target']);
									$valid=true;
								}
						}
						$_SESSION['Error'] = "Incorrect email address or password";
					}
				}
				if(!$valid)
					include "login.php";
			}
			break;
			case "newuser":
			{				if(isset($_POST["password"])&&isset($_POST["fname"])&&isset($_POST["lname"])&&isset($_POST["DOB"])&&isset($_POST["email"])&&isset($_POST["phone"])&&isset($_POST["address"])&&isset($_POST["postal"]))
				{
					$valid=true;
					if(empty($_POST["password"])||empty($_POST["fname"])||empty($_POST["lname"])||empty($_POST["DOB"])||empty($_POST["email"])||empty($_POST["phone"])||empty($_POST["address"])||empty($_POST["postal"]))
					{
						$_SESSION['Error']= "Please complete all fields";
						$valid=false;
					}
					else{
						$_SESSION['Error']="Error: ";
						$em=$_POST["email"];
						if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
							$_SESSION['Error'].= "Invalid email format";
							$valid=false;
						}
						if(!preg_match("/^[a-zA-Z ]*$/",$_POST["fname"])){
							$_SESSION['Error'].= "Invalid first name";
							$valid=false;
						}
						if(!preg_match("/^[a-zA-Z ]*$/",$_POST["lname"])){
							$_SESSION['Error'].= "Invalid last name";
							$valid=false;
						}
						$phoneNum=preg_replace("/[^0-9]/", "", $_POST["phone"]);
						if(!preg_match('/^[0-9]{10,11}$/', $phoneNum)){
							$_SESSION['Error'].= "Invalid phone number";
							$valid=false;
						}
						if(!preg_match('/^[A-Z]\d[A-Z][ ]?\d[A-Z]\d$/', $_POST["postal"])){
							$_SESSION['Error'].= "Invalid phone number";
							$valid=false;
						}
						$dob=$_POST["DOB"];
						//DateTime::createFromFormat('m/d/Y', $_POST["DOB"]);
						//$dob=$dob->format('d/m/Y');
						$address=preg_replace("/[^0-9A-Za-z\s]/", "", $_POST["address"]);
						if($valid)
						{
							$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
							die("error".mysqli_error($link));
							if($query=mysqli_query($conn,"SELECT auth FROM client where email='$em'"))
							{
								if(mysqli_num_rows($query))
								{
									$_SESSION['Error'] = "Email already exists";
								}
								else
								{
									$pw=$_POST["password"];
									$fn=$_POST["fname"];
									$ln=$_POST["lname"];
									$pc=$_POST["postal"];
									
									//$ph=$_POST["phone"];
									//$ad=$_POST["address"];
									$query="INSERT INTO client(auth, firstName, lastName, DOB, email, phone, address, postalCode)
										values('$pw', '$fn', '$ln', '$dob', '$em', '$phoneNum', '$address', '$pc')";
									mysqli_query($conn, $query) or die(mysqli_error($conn));
									$_SESSION['clientID']=mysqli_insert_id($conn);
									$_SESSION["email"]=$em;
									include "account.php";
									exit;
								}
							}
						}
					}
				}
				else
				{
					$_SESSION['Error'] .= "Please complete all fields";
				}
				include "newaccount.php";
			}
			break;
		}
	}
	else
	{
		include "mainpage.php";
	}
?>