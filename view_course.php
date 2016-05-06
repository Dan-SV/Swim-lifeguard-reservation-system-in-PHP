<?php
	ini_set('max_execution_time', 200);
	if(!isset($_SESSION))
		session_start();
	if(!isset($_SESSION['ID']))
		$_SESSION['ID']=uniqid();
?>
<html>
	<head>
		<title>View Courses</title>
		<link rel="stylesheet" type="text/css" href="css/client.css"/>
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<script src='JS/jquery.js'></script>
		<script>
			$(document).ready(function() {
				$("#load").html("<img src='images/loading.gif' />");
			});	
			function show($n)
			{
				$("#load").html("<img src='images/loading.gif' />");
				$("#load").show();
				$("#hide").show();
				$.ajax
				({
					url: 'incalendar_view.php',
					data: 'courseid='+ $n,
					type: "POST",
					success: function(output) {
						$("#load").html(output);
					}
				});
			}
			function hide()
			{
				$("#load").hide();
				$("#hide").hide();
			}
		</script>
	</head>
	<body>
		<div id="head"><a style="float:left;" href="index.php"><img src="images/gna.jpg"/></a>
			<div id="nav">
			  <ul>
				<li>Shopping
					<ul>
					  <li><a href="calendar.php">Calendar</a></li>
					  <li>Course List</li>
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
			<div class="wideContent">
				<form method="post" action='<?php echo $_SERVER['PHP_SELF'];?>'>
					<?php 
						if(!isset($_SESSION))
						{
							session_start();
						}
						if(!isset($_SESSION['ID']))
						{
							$_SESSION['ID']=uniqid();
						}
						if(isset($_POST['typeRequested']))
							$selected=$_POST['typeRequested'];
						else
							$selected="";
					?>
					<select name="typeRequested" onchange="this.form.submit()">
						<option value="" <?php if($selected ==""){echo("selected");}?>>Any</option>
						<option value="FA" <?php if($selected =="FA"){echo("selected");}?>>First Aid</option>
						<option value="KS" <?php if($selected =="KS"){echo("selected");}?>>Swimming lesson for kids</option>
						<option value="PT" <?php if($selected =="PT"){echo("selected");}?>>Swimming lesson for Parent and Tot</option>
						<option value="PL" <?php if($selected =="PL"){echo("selected");}?>>Private lesson</option>
					</select>
				</form>
				<table>
					<?php
						$city="Kamloops";
						if(isset($_POST['typeRequested'])&!empty($_POST['typeRequested']))
						{
							$listQuery="SELECT courseID, title, locationName, comments, city, startDate, endDate, max_capacity, current_capacity, type FROM course WHERE startDate>=NOW() AND type='".$_POST['typeRequested']."' AND city='$city'";
						}
						else
							$listQuery="SELECT courseID, title, locationName, comments, city, startDate, endDate, max_capacity, current_capacity, type FROM course WHERE startDate>=NOW()";
						$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
						die("error".mysqli_error($link));
						echo "<tr><th>Course Title</th><th>City</th><th>Location</th><th>Start Date</th><th>End Date</th><th>Comments</th><th>Price</th><th>Spaces remaining</th>";
						if($query=mysqli_query($conn, $listQuery))
						{
							while($courses=mysqli_fetch_assoc($query))
							{
								$cid=$courses['courseID'];
								$qtyEnrolled=0;
								if($capacityQuery=mysqli_query($conn, "SELECT quantity FROM studentlist where courseID=".$cid))
								{
									while($registered=mysqli_fetch_assoc($capacityQuery))
									{
										$qtyEnrolled+=$registered['quantity'];
									}
								}
								mysqli_query($conn, "DELETE FROM cart WHERE hold_expiry < curtime()- interval 10 minute");
								if($qty=mysqli_query($conn, "SELECT quantity FROM cart WHERE course_id=".$cid))
								{
									while($students=mysqli_fetch_assoc($qty))
									{
										$qtyEnrolled+=$students['quantity'];
									}
								}
								$spacesLeft=$courses['max_capacity']-$qtyEnrolled;
								$courseOffering="<form action='add_to_cart.php' method='post'><tr onclick=show(".$cid.") style='cursor:pointer'><td>".$courses['title']."</td><td>".$courses['city']."</td>";
								$courseOffering.="<td>".$courses['locationName']."</td><td>".date_format(date_create($courses['startDate']), "Y/m/d")."</td>";
								$courseOffering.="<td>".date_format(date_create($courses['endDate']), "Y/m/d")."</td><td>".$courses['comments']."</td>";
								if($prices=mysqli_query($conn, "SELECT basePrice, extraParticipant FROM price WHERE type=\"".$courses['type']."\""))
								{
									while($price=mysqli_fetch_assoc($prices))
									{
										$courseOffering.="<td>$".$price['basePrice'];
										if($price['basePrice']!=$price['extraParticipant'])
											$courseOffering.=" additional students only $".$price['extraParticipant'];
										$courseOffering.="</br></a>";
									}
								}
								//$tomorrow=new DateTime('tomorrow');
								//echo $courses['startDate'];
								//if($courses['startDate'] < $tomorrow){	//less than tomorrow's date.
								//	$courseOffering="<td>Registration for this course has closed</td>";
								//}
								if($spacesLeft>0)
								{
									$courseOffering.= "<td>".$spacesLeft."</td>";	//"<td><input type='hidden' name='courseid' value=".$cid.">";
									//$courseOffering.= "<td><input name='participants' type='number' value='1' min='1' max='".$spacesLeft."'/></td>";
									//$courseOffering.= "<td><input type='submit' value='Add to cart'/></td>";
								}
								else
									$courseOffering.= "<td>Course is full</td>";

								$courseOffering.="</tr></form>";
									echo $courseOffering;
							}
						}
					?>
				</table>
			</div>
		</div>
		<div id='hide' onclick="hide()"><p>[X] Click in shaded area to close</p></div>
		<div id='load'></div>
	</body>
</html>