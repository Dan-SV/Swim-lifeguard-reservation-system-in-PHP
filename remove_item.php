<?php
	session_start();
	if(isset($_POST['cartIndex'])){
		$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
			die("error".mysqli_error($link));
		$deleteQuery="DELETE FROM `cart` WHERE id=".$_POST['cartIndex'];
		if($result=mysqli_query($conn, $deleteQuery))
		{
			echo "item removed!";
		}
		else
			echo "Error: item not found in cart<br>".$deleteQuery;
	}
	else
		echo "Error: no course selected";
	include("cart.php");
?>