<?php
	if(empty($_POST["email"])){
		$_SESSION['Error'] = "No email address entered!";
	}
	elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['Error'] = "Invalid email format";
	}
	else{
		$conn= mysqli_connect('localhost', 'root', '', 'kamloopsfirstaid') or
		die("error".mysqli_error($link));
		$target=$_POST["email"];
		if($query=mysqli_query($conn,"SELECT clientID, firstname, lastname, auth FROM client where email='$target' LIMIT 1"))
		{
			//generate token
			$token="";
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$size = strlen( $chars );
			for( $i = 0; $i < 12; $i++ ) {
				$token.= $chars[ rand( 0, $size - 1 ) ];
			}
			$tokenquery="INSERT INTO recoverytokens(email, token) VALUES ('$target','$token') ON DUPLICATE KEY UPDATE token='$token.'";
			mysqli_query($conn, $tokenquery) or die(mysqli_error($conn));
			$subject="Account password recovery";
			$message="Hello ";
			if($data=mysqli_fetch_assoc($query))
			{
				$message.=$data['firstname']." ".$data['lastname']."  It has been indicated to us that you have forgotten your password. ";
			}
			$message.= "To reset your password, please use the following token: ".$token."\r\nTo access your account at www.greatnorthaquatics.com/client/recovery.php";
			require 'PHPMailer/PHPMailerAutoload.php';

			$mail = new PHPMailer;
			
			//$mail->SMTPDebug = 3;                               // Enable verbose debug output
			
			
			/*
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = '****';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'passwordrecovery@****.com';                 // SMTP username
			$mail->Password = '****';                           // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                                    // TCP port to connect to
			
			$mail->From = 'passwordrecovery@****.com';
			$mail->FromName = 'Great North Aquatics';
			$mail->addAddress($target);     // recipient
			//$mail->addReplyTo('info@greatnorthaquatics.com', 'GreatNorthAquatics Information');
			
			$mail->Subject = $subject;
			$mail->Body    = $message;
			
			if(!$mail->send()) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			} 
			else {
			    $_SESSION['Error']='Message has been sent';
				header("Location: recovery.php");
			}
			*/
			$_SESSION['Error']='Password recovery is disabled. \'recoveryemail.php\' can be modified; GreatNorthAquatics domain is no longer active.';
			header("Location: recovery.php");
		}
		else
		{
			$_SESSION['Error'] = "Email address not associated with an account.";
			header("Location: forgotpassword.php");
		}
	}
?>