<html>
	<script src='JS/jquery.js'/>
	<script>
		$("#multiCheckbox").click(function(){
			if($(this).is(':checked'))
				$("#multi").show();
			else{
				$("#multi").hide();
				$("input[name=participants]").val('1');
			}
		});
	</script>
	<br>
	<form action="add_to_cart.php" method="post">
		<?php
			$cid=$_POST['courseid'];
			
			$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
			die("error".mysqli_error($link));
			if($query=mysqli_query($conn,"SELECT title, locationName, comments, city, startDate, endDate, max_capacity, current_capacity, type FROM course WHERE courseID='$cid'"))
			{
				while($courses=mysqli_fetch_assoc($query))
				{
					//$cid=$courses['courseID'];
					$qtyEnrolled=0;
					mysqli_query($conn, "DELETE FROM cart WHERE hold_expiry <".time());
					mysqli_query($conn, "DELETE FROM cart WHERE hold_expiry < curtime()- interval 10 minute");
					if($qty=mysqli_query($conn, "SELECT quantity FROM cart WHERE course_id=".$cid))
					{
						while($students=mysqli_fetch_assoc($qty))
						{
							$qtyEnrolled+=$students['quantity'];
						}
					}
					if($capacityQuery=mysqli_query($conn, "SELECT quantity FROM studentlist where courseID=".$cid))
					{
						while($registered=mysqli_fetch_assoc($capacityQuery))
						{
							$qtyEnrolled+=$registered['quantity'];
						}
					}
					$spacesLeft=$courses['max_capacity']-$qtyEnrolled;
					$start=$courses['startDate'];
					$courseOffering="<table style='margin-left:auto; margin-right:auto;'><tr><th>Course Title</th><td>".$courses['title']."</td><th>City</th><td>".$courses['city']."</td>";
					$courseOffering.="<tr><th>Start Date</th><td>".date_format(date_create($start), "Y/m/d")."</td><th>Location</th><td>".$courses['locationName']."</td></tr></tr>";
					$courseOffering.="<tr><th>End Date</th><td>".date_format(date_create($courses['endDate']), "Y/m/d")."</td></tr></tr>";
					if($prices=mysqli_query($conn, "SELECT basePrice, extraParticipant FROM price WHERE type=\"".$courses['type']."\""))
					{
						while($price=mysqli_fetch_assoc($prices))
						{
							$courseOffering.="<tr><th>Price</th><td>$".$price['basePrice']."</td>";
							if($price['basePrice']!=$price['extraParticipant'])
								$courseOffering.="<th>Additional students</th><td>$".$price['extraParticipant']." each</td>";
							$courseOffering.="</tr></br>";
						}
					}
					if($spacesLeft>0)
						$courseOffering.= "<tr><th>Spaces remaining</th><td>".$spacesLeft."</td>";
					else
						$courseOffering.= "<tr><td>Course is full</td><td></td>";
					$courseOffering.="</tr><tr><th>Comments</th><td>".$courses['comments']."</td></table><input type='hidden' name='courseid' value=".$cid."/>";
						//echo $courseOffering;
				}
				$courseOffering.= "<br>Course times:<br><table style='margin-left:auto; margin-right:auto;'>";
				if($q2=mysqli_query($conn, "SELECT * FROM event WHERE id='$cid'"))
				{
					while($e=mysqli_fetch_assoc($q2))
					{
						$s_date=new DateTime($e['start']);
						$e_date=new DateTime($e['end']);
						$courseOffering.= "<tr><th>".$s_date->format("l, F jS, Y");
						$courseOffering.= "</th><td>Start:</td><th>".$s_date->format("H:i");
						$courseOffering.= "</th><td>End:</td><th>".$e_date->format("H:i")."</th></tr>";
					}
				}
				$courseOffering.="</table>";
				echo $courseOffering;
			}
			echo "<input type='hidden' name='courseid' value='".$cid."'/>";
		?>
		
		<table width="500px" height="60px" style="margin-left:auto; margin-right:auto;">
			<?php
				$start=new DateTime($start);
				$now=new DateTime();
				$now->setTime(0, 0);
				if($spacesLeft>0)
				{
					if($start>=$now)
					{
						echo"<td width='33%'><input id='multiCheckbox' type='checkbox'>Multiple students</td>
						<td width='33%'>
							<div id='multi' style='display: none;' width='50px' style='float: left;'>
								Number of students:<input name='participants' value='1' min='1' max='".$spacesLeft."' type='number' style='width:40px;'/>
							</div>
						</td>
						<td><input style='float: left;' value='Add to cart' type='submit'></td>";
					}
					else
						echo "Course has already started. Registration is complete.";
				}
				else
					echo "Registration for this course is complete";
			?>
		</table>
	</form>
</html>