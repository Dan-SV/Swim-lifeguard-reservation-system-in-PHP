<?php
	ini_set('max_execution_time', 200);
	if(!isset($_SESSION))
		session_start();
	if(!isset($_SESSION['clientID']))
		header("Location: login.php");
?>
<html>
	<head>
		<title>Account- Great North Aquatics</title>
		<link rel="stylesheet" type="text/css" href="css/client.css"/>
		<link rel="stylesheet" type="text/css" href="css/header.css">
	</head>
	<body>
		<div id="head"><a style="float:left;" href="index.php"><img src="images/gna.jpg"/></a>
			<div id="nav">
			  <ul>
				<li>Shopping
					<ul>
					  <li><a href="calendar.php">Calendar</a></li>
					  <li><a href="view_course.php">Course List</a></li>
					</ul>
				</li>
				<li>My Account
					<ul>
						<li><a href='account.php'>Current Registrations</a></li>
						<li><a href='changePasswordForm.php'>Change Password</a></li>
					</ul>
				</li>
				<li><a href="contact.php">Contact</a></li>
				<li><a href="cart.php">
					<?php
						$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
						die("error".mysqli_error($link));
						if($query=mysqli_query($conn,"SELECT * FROM cart WHERE session_id='".$_SESSION['ID']."'"))
						{
							if($items=mysqli_num_rows($query))
								echo "Cart (".$items." items)";
							else
								echo "Your cart is empty";
						}
					?>
				</a></li>
				<li><a href='logout.php'>Log Out</a></li>
			  </ul>
			</div>
			<br><br><br>
			<h1>GREAT NORTH AQUATICS</h1>
			<h2>Lifeguarding, Swimming Lessons, First-Aid Training</h2>
		</div>
		<div class="container">
			<div class="wideContent">
				<?php
					echo "Welcome ".$_SESSION['email'];
					if($items)
					{
						echo "<br><a href='cart.php'><button style='width: 350px;'>You have items in your cart that require your attention. Please register promptly to guarantee your spot.</button></a>";
						//echo "<iframe width='50%' src='cart.php'></iframe>";
					}
					if($myCourses = mysqli_query($conn, "SELECT * FROM studentlist WHERE clientID=".$_SESSION["clientID"]));
					{
						if(mysqli_num_rows($myCourses))
						{
							//echo "<iframe width='50%' src='view_registrations.php'></iframe>";
							include "view_registrations.php";
						}
						else
						{
							echo "You are not currently registered in any courses.<br>";
						}
					}
				?>
			</div>
		</div>
		<div id='hide' onclick="hide()"><p>[X] Click in shaded area to close</p></div>
		<div id='load'></div>
	</body>
</html>