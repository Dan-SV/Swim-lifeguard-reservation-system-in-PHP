<?php
	if(!isset($_SESSION))
	{
		session_start();
	}
	if(!isset($_SESSION['ID']))
	{
		$_SESSION['ID']=uniqid();
	}
	ini_set('max_execution_time', 120);
?>
<html>
	<head>
		<title>Kamloops First Aid Login</title>
		<link rel="stylesheet" type="text/css" href="css/client.css"/>
		<link rel="stylesheet" type="text/css" href="css/header.css"/>
		<script type="text/javascript" src="JS/digest.js">
		</script>
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
								echo "<li><a href='view_registrations.php'>Current Registrations</a></li>";
								echo "<li><a href='changePasswordForm.php'>Change Password</a></li>";
							}
							else
							{			
								echo "<li>Sign In</li>";
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
		<h2>Sign in</h2>
		<?php
			if(isset($_SESSION['Error']))
			{
				echo $_SESSION['Error'];
				$_SESSION['Error']="";
			}
		?>
		<p>Email address:</p>
		<form id="safeForm" method="post" action="index.php">
			<input type="text" id="email" name="email"></input>
			<input type="hidden" id="postDigest" name="password"/>
			<input type="hidden" name="request" value="login"/>
		</form>
		<p>Password:</p>
		<input type="password" id="preDigest" name="password"></input>
		<button type="button" onclick="digest()">Sign in</button>
		<br><br>
		<a href="newaccount.php"><button>Create a new account</button></a>
		<a href="forgotpassword.php"><button>Forgot password</button></a>
	</div>
</div>
</body>
</html>