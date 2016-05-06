<?php
	session_start();
	session_destroy();
	$logoutMsg="Thank you for visiting Great North Aquatics";
	include "index.php";
?>