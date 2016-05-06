<?php
	if(!isset($_SESSION))
		session_start();
	if(!isset($_SESSION['ID']))
		$_SESSION['ID']=uniqid();
?>
<html>
	<head>
		<title>New Account- Great North Aquatics</title>
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<link rel="stylesheet" type="text/css" href="css/client.css"/>
	</head>
	<body>
		<div id="head"><a style="float:left;" href="index.php"><img src="images/gna.jpg"/></a>
			<div id="nav">
				<ul>
					<li>Shopping
						<ul>
						  <li><a href="calendar.php">Calendar</a></li>
						  <li><a href="account.php">Course List</a></li>
						</ul>
					</li>
					<li>My Account
						<ul>
							<?php
								if(isset($_SESSION['clientID']))
								{
									echo "<li><a href='view_registrations.php'>Current Registrations</a></li>";
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
				<form action="recoveryemail.php" method="post">
					<p>Email associated with your account</p> <input type="text" name="email"/>
					<input type="submit" value="Send password recovery email"/>
				</form>
			</div>
		</div>
	</body>
</html>