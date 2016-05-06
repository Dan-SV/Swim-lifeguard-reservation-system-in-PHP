<?php
	ini_set('max_execution_time', 200);
	if(!isset($_SESSION))
		session_start();
	if(!isset($_SESSION['ID']))
		$_SESSION['ID']=uniqid();
?>
<html>
	<head>
		<title>Cart-Great North Aquatics</title>
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
				<li>Cart</li>
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
			<?php
				ini_set('max_execution_time', 120);
				$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
					die("error".mysqli_error($conn));
				/*if(!isset($_SESSION['ID']))
				{
					header('Location: index.php');
				}*/
				$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
				die("error".mysqli_error($conn));
				$total=0;
				if($result=mysqli_query($conn, "SELECT id, course_id, quantity FROM cart WHERE session_id=\"".$_SESSION['ID']."\""))
				{
					$numItems=mysqli_num_rows($result);
					if($numItems)
					{
						echo "<table><tr><th>Course Title</th><th>Start Date</th><th>End Date</th><th># of students being registered</th><th>Price</th></tr>";
						echo $numItems." Items in cart.";
						echo "<p>Your registrations is not yet complete! Please register to save your spot in the course.</p>";
						while($item=mysqli_fetch_assoc($result))
						{
							if($res2=mysqli_query($conn, "SELECT * FROM course WHERE courseID=".$item['course_id']))
							{
								if($course=mysqli_fetch_assoc($res2))
								{
									echo "<tr><td>".$course['title']."</td><td>".$course['startDate']."</td><td>".$course['endDate']."</td>";
									echo "<td>".$item['quantity']."</td>";
									if($res3=mysqli_query($conn, "SELECT * FROM price WHERE type='".$course['type']."'"))
									{
										if($cost=mysqli_fetch_assoc($res3))
										{
											$price=$cost['basePrice'];
											$qty=$item['quantity'];
											if($qty>1)
												$price=$price+(($qty-1)*$cost['extraParticipant']);
											echo "<td>".$price."</td>";
											$total+=$price;
										}
									}
								}
							}
							echo "<td><form action='remove_item.php' method='POST'><input type='hidden' name='cartIndex' value=".$item['id']." /><input type='submit' value='Remove from cart' /></form></td></tr>";
						}
						echo "</table>";
						if($total)
						{
							echo "<h3>Total:  $".$total."<br></h3>";
							echo "<a href='https://localhost/protected/paynow.php'><button>Pay and Register</button></a>";
						}
						else
							echo "Error: Cart total not set.";
					}
					else
					{
						echo "Your cart is empty.<br>";
						echo "";
					}
				}
				/*{
					
					if(isset($_SESSION['clientID']))
						echo "<a href='https://localhost/greatnorthaquatics/protected/paynow.php'><button>Pay and Register</button></a>";
					else
						echo "<form action='login.php' method='POST'><input type='hidden' name='target' value='register.php'/><input type='submit' value='Pay and Register'/></form>";
				}*/
			?>
			<a href='index.php'><button>Continue Shopping</button></a>
		</div>
	</div>
</html>