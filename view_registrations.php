<html>
	<head>
		<title>View Registrations</title>
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
					url: 'registered_view.php',
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
	<h1>Current Registrations:</h1>
	<table>
		<tr>
			<th></th>
		</tr>
		<?php
			ini_set('max_execution_time', 200);
			if(!isset($_SESSION))
			{
				session_start();
			}
			$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
				die("error".mysqli_error($link));
			echo "<tr><th>Course Title</th><th>Number of students</th><th>City</th><th>Location</th><th>Start Date</th><th>End Date</th><th>Comments</th>";
			if($myCourses = mysqli_query($conn, "SELECT courseID, quantity FROM studentlist WHERE clientID=".$_SESSION["clientID"]));
			{				
				while($registrations=mysqli_fetch_assoc($myCourses))
				{
					if($query=mysqli_query($conn, "SELECT courseID, title, locationName, comments, city, startDate, endDate, max_capacity, type FROM course WHERE courseID=".$registrations['courseID']))
					{
						while($courses=mysqli_fetch_assoc($query))
						{
							$courseOffering="<tr onclick=show(".$courses['courseID'].") style='cursor:pointer'><td>".$courses['title']."</td><td>".$registrations['quantity']."</td><td>".$courses['city']."</td><td>".$courses['locationName']."</td>";
							$courseOffering.="<td>".date_format(date_create($courses['startDate']), "Y/m/d")."</td><td>".date_format(date_create($courses['endDate']), "Y/m/d")."</td><td>".$courses['comments']."</td></tr>";
							echo $courseOffering;
						}
					}
				}
			}
		?>
	</table>
</html>