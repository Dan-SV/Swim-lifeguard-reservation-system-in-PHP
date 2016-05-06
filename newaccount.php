<?php
	ini_set('max_execution_time', 200);
	if(!isset($_SESSION))
		session_start();
	if(!isset($_SESSION['ID']))
		$_SESSION['ID']=uniqid();
?>
<html>
	<head>
		<title>New Account- Great North Aquatics</title>
			<link rel="stylesheet" type="text/css" href="css/header.css">
		<link rel="stylesheet" type="text/css" href="css/newaccount.css"/>
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
			<div class="wideContent">
				<h2>Create new account</h2>
				<?php
					if(!isset($_SESSION)) 
						session_start(); 
					if(isset($_SESSION['Error']))
					{
						echo $_SESSION['Error'];
						$_SESSION['Error']="";
						unset($_SESSION['Error']);
					}
				?>
				<table>
					<form id="safeForm" method="post" action="index.php">
						<tr>
							<td>Email address:<input type="text" id="email" name="email"></input></td>
							<td><p>First name:</p></td><td><input type="text" name="fname"/></td>
							<td><p>Last name:</p></td><td><input type="text" name="lname"/></td>
						</tr>
						<tr>
							<td><p>Date of birth:</p></td><td><input id="datepicker" type="date" name="DOB"></input></td>
							<td></td>
							<td><p>Address:</p></td><td><input type="text" name="address"/></td>
						</tr>
						<tr>
							<td><p>Postal code:</p></td><td><input type="text" name="postal"/></input></td>
							<td></td>
							<td><p>Phone:</p></td><td><input type="tel" name="phone"/></input></td>
						</tr>
						<tr>
							<td></td>
						</tr>
						<input type="hidden" id="postDigest" name="password"/>
						<input type="hidden" name="request" value="newuser"/>
					</form>
				<tr>
					<td><p>Password:</p></td><td><input type="password" id="preDigest"/></td>
					<td><p>Confirm Password:</p></td><td><input type="password" name="confirm"/></td>
				</tr>
				</table>
				<button type="button" onclick="checkPasswords()">create account</button>
				<br>
			</div>
		</div>
		<a href="login.php"><button>Login to existing account</button></a>
	</body>
</html>
<link rel="stylesheet" href="JS/jquery_ui.css">
<script src="JS/jquery.js"></script>
<script src="JS/jquery_ui.js"></script>
<script src="JS/digest.js"></script>
<script>
function checkPasswords(){
	if($("#preDigest").val().length<8)
		alert("Must enter a password, minimum 8 characters.")
	else if($("input[name=confirm]").val()!=$("#preDigest").val())
		alert("Password and password confirmation must match.")
	if($("input[name=confirm]").val()==$("#preDigest").val())
		digest();
}
$(function() {
    $( "#datepicker" ).datepicker({
		minDate: "-110Y",
		maxDate: "-1D",
		changeMonth: true,
		changeYear: true,
		yearRange: '-100y:c+nn'
    });
});
</script>