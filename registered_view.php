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
		<?php
			ini_set('max_execution_time', 300);
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
					if($qty=mysqli_query($conn, "SELECT quantity FROM cart WHERE course_id=".$cid))
					{
						while($students=mysqli_fetch_assoc($qty))
						{
							$qtyEnrolled+=$students['quantity'];
						}
					}
					$spacesLeft=$courses['max_capacity']-$qtyEnrolled;
					$courseOffering="<table style='margin-left:auto; margin-right:auto;'><tr><th>Course Title</th><td>".$courses['title']."</td><th>City</th><td>".$courses['city']."</td>";
					$courseOffering.="<tr><th>Start Date</th><td>".date_format(date_create($courses['startDate']), "Y/m/d")."</td><th>Location</th><td>".$courses['locationName']."</td></tr></tr>";
					$courseOffering.="<tr><th>End Date</th><td>".date_format(date_create($courses['endDate']), "Y/m/d")."</td></tr></tr>";
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
</html>