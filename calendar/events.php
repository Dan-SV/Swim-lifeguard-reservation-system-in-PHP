<?php
 $json = array();
 $request = "SELECT id, title, start, end FROM event ORDER BY id";
 try {
	$bdd = new PDO('mysql:host=localhost;dbname=kamloopsfirstaid', 'phpSwimClient', 'tsew06921421');
 } catch(Exception $e) {
	exit('Cannot connect to database');
 }
 $result = $bdd->query($request) or die(print_r($bdd->errorInfo()));
 echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
?>