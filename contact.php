<?php
	if(!isset($_SESSION))
		session_start();
	if(!isset($_SESSION['ID']))
		$_SESSION['ID']=uniqid();
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<link rel="stylesheet" type="text/css" href="css/client.css"/>
		<title>Great North Aquatics</title>
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
						<?php
							if(isset($_SESSION['clientID']))
							{
								echo "<li><a href='account.php'>Current Registrations</a></li>";
								echo "<li><a href='changePasswordForm.php'>Change Password</a></li>";
							}
							else
							{			
								echo "<li><a href='login.php'>Sign In</a></li>";
							}
						?>
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
				<?php
					if(isset($_SESSION['clientID']))
						echo "<li><a href='logout.php'>Log Out</a></li>";
				?>
			  </ul>
			</div>
			<br><br><br>
			<h1>GREAT NORTH AQUATICS</h1>
			<h2>Lifeguarding, Swimming Lessons, First-Aid Training</h2>
		</div>
		<div class="container">
			<div class="main">
				<br>
				<h2>Message us to hire a lifeguard, enquire about swimming lessons, or first aid courses.</h2>
				<a href="mailto: swimkamloops@gmail.com"><h2>swimkamloops@gmail.com</h2></a>
				</br>
			</div>
		</div>
	</body>
</html>