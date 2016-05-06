<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='calendar/fullcalendar.css' rel='stylesheet' />
<link rel="stylesheet" type="text/css" href="css/customcalendar.css">
<script src='calendar/moment.min.js'></script>
<script src='calendar/jquery.min.js'></script>
<script src='calendar/fullcalendar.min.js'></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<script>
	$(document).ready(function() {
		$("#hide2").hide();
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		var calendar= $('#calendar').fullCalendar({
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: {
				url: 'calendar/events.php',
				error: function() {
					$('#script-warning').show();
				},
				success: function(){
					alert("Events loaded successfully");
					$("#load").hide();
					$("#hide").hide();
				}
			},
			selectable: true,
			eventStartEditable: false,
			selectHelper: true,
			loading: function(isLoading){
				if(isLoading){
					$("#load").show();
					$("#hide").show();
				}
				else{
					$("#load").hide();
					$("#hide").hide();
				}
			},
			eventClick: function(event)//calEvent, jsEvent, view)
			{
				$("#eventView").html("<img src='images/loading.gif' />");
				$("#eventView").show();
				$("input[name=courseid]").val(event.id);
				$("#hide2").show();
				$.ajax
					({
						url: 'incalendar_view.php',
						data: 'courseid='+ event.id,
						type: "POST",
						success: function(output) {
							$("#eventView").html(output);
						}
					});
			},
			eventMouseover: function(jsEvent, view) {
				$(this).css('background-color', 'blue');
			},
			eventMouseout: function(event, jsEvent, view) {
				$(this).css('background-color', '#3a87ad');
			}
		});	
		$("#cancel").click(function(){
			$("#new-event").hide();
		});
		$("#hide2").click(function(){
			$("#eventView").hide();
			$("#hide2").hide();
		});
		var img_array=["images/firstaid.png", "images/guard1.jpg",
			"images/screen shot 2015-05-20 at 23121 pm-ieu940-fr.png", "images/unnamed.jpg",
			"images/screen shot 2015-05-23 at 75954 pm.jpg"];
		var img_boxes=[$("#imgbox1"),$("#imgbox2"),$("#imgbox3"),$("#imgbox4")];
		var index=0;
		setInterval(function(){
			$(img_boxes[index%img_boxes.length]).attr("src", img_array[index++%img_array.length]);
		}, 1000);
	});
</script>
</head>
<body>
<?php
	if(!isset($_SESSION))
		session_start();
	if(!isset($_SESSION['ID']))
		$_SESSION['ID']=uniqid();
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<title>Great North Aquatics</title>
	</head>
	<body>
		<div id="head"><a style="float:left;" href="index.php"><img src="images/gna.jpg"/></a>
			<div id="nav">
			  <ul>
				<li>Shopping
					<ul>
					  <li>Calendar</li>
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
	<div id='calendar'></div>
	<div id='hide'></div>
	<div id='hide2'><p>[Click anywhere to hide]</p></div>
	<div id='load'>
		<h1>Loading courses...</h1>
		<table>
			<tr>
				<td>
					<img id="imgbox1"/>
				</td>
				<td>
					<img id="imgbox2"/>
				</td>
			</tr>
			<tr>
				<td>
					<img id="imgbox3"/>
				</td>
				<td>
					<img id="imgbox4"/>
				</td>
			</tr>
		</table>
	</div>
	<div id="eventView"/>
	<p id="script-warning">Warning! Event loading error.</p>
</body>
</html>
