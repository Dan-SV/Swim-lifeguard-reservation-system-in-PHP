<?php
	if(!isset($_SESSION))
		session_start();
	if(!isset($_SESSION['clientID']))
		header("Location: index.php");
?>
<html>
	<head>
		<title>Kamloops First Aid</title>
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
					if(isset($_SESSION['ID']))
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
		<h2>Forgotten Password Recovery</h2>
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
		<form id="safeForm" method="post" action="changePW.php">
			<input type="hidden" id="postDigest" name="oldPassword"/>
			<input type="hidden" id="NPDigest" name="newPassword"/>
			<?php
				echo "<input type='hidden' id='email' value='".$_SESSION["email"]."'>";
			?>
			<!--input type="hidden" name="request" value="newuser"/-->
		</form>
		<table>
			<tr>
				<td><p>Old Password:</p></td><td><input type="password" id="preDigest"/></td>
			</tr>
			<tr>
				<td><p>Choose a New Password:</p></td><td><input type="password" id="newPreDigest"/></td>
				<td><p>Confirm new Password:</p></td><td><input type="password" name="confirm"/></td>
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
<script src="JS/nonsubmit.js"></script>
<script>
function checkPasswords(){
	if($("#newPreDigest").val().length<8)
		alert("Must enter a password, minimum 8 characters.")
	else if($("input[name=confirm]").val()!=$("#newPreDigest").val())
		alert("Password and password confirmation must match.")
	if($("input[name=confirm]").val()==$("#newPreDigest").val())
	{
		nonsubmit();
		digest();
	}
	else
		alert("New passwords don't match");
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