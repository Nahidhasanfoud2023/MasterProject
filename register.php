<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;


	include 'includes/session.php';

	if(isset($_POST['signup'])){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		$remword = $_POST['remword'];

		

		$_SESSION['firstname'] = $firstname;
		$_SESSION['lastname'] = $lastname;
		$_SESSION['email'] = $email;

		
  
		if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
		{
			  $secret = '6Lf67cIlAAAAAFDC5j70SfEwKTVAJmpa75eFFwSY';
			  $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
			  $responseData = json_decode($verifyResponse);
			  if($responseData->success)
			  {
				  $succMsg = 'Your contact request have submitted successfully.';
			  }
			  else
			  {
				  $errMsg = 'Please answer recaptcha correctly.';
			  }
		 }


		if($password != $repassword){
			$_SESSION['error'] = 'Passwords did not match';
			header('location: signup.php');
		}
		else{
			$conn = $pdo->open();

			$stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM users WHERE email=:email");
			$stmt->execute(['email'=>$email]);
			$row = $stmt->fetch();
			if($row['numrows'] > 0){
				$_SESSION['error'] = 'Email already taken';
				header('location: signup.php');
			}
			else{
				$now = date('Y-m-d');
				$password = password_hash($password, PASSWORD_DEFAULT);

				//generate code
				$set='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$code=substr(str_shuffle($set), 0, 12);

				try{
					$stmt = $conn->prepare("INSERT INTO users (email, password, firstname, lastname, remword, activate_code,  created_on) VALUES (:email, :password, :firstname, :lastname, :remword, :code, :now)");
					$stmt->execute(['email'=>$email, 'password'=>$password, 'firstname'=>$firstname, 'lastname'=>$lastname, 'remword'=>$remword, 'code'=>$code, 'now'=>$now]);
					$userid = $conn->lastInsertId();

					$message = "
						<h2>Thank you for Registering.</h2>
						<p>Your Account:</p>
						<p>Email: ".$email."</p>
						<p>Password: ".$_POST['password']."</p>
						<p>Please click the link below to activate your account.</p>
						<a href='http://localhost/buyonthefly/activate.php?code=".$code."&user=".$userid."'>Activate Account</a>
					";

					//Load phpmailer
					require 'vendor/autoload.php';

		    		$mail = new PHPMailer(true);                             
				    try {
				        //Server settings
						$mail->SMTPDebug = 2;
				        $mail->isSMTP();                                     
				        $mail->Host = 'mail.webvaultit.com';                      
				        $mail->SMTPAuth = true;                               
				        $mail->Username = 'test@webvaultit.com';     
				        $mail->Password = '6*MIO^@yeVma';                    
				        $mail->SMTPOptions = array(
							'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
							)
						);                         
				       
						$mail->SMTPSecure = 'ssl';                         
				        $mail->Port = 465;                                   

				        $mail->setFrom('test@webvaultit.com');
				        
				        //Recipients
				        $mail->addAddress($email);              
				        $mail->addReplyTo('test@webvaultit.com');
				       
				        //Content
				        $mail->isHTML(true);                                  
				        $mail->Subject = 'Buy On The Fly Register';
				        $mail->Body    = $message;

				        $mail->send();

				        unset($_SESSION['firstname']);
				        unset($_SESSION['lastname']);
				        unset($_SESSION['email']);
						unset($_SESSION['password']);
						unset($_SESSION['remword']);

				        $_SESSION['success'] = 'Account created. Check your email to activate.';
				        header('location: signup.php');

				    } 
				    catch (Exception $e) {
				        $_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
				        header('location: signup.php');
				    }


				}
				catch(PDOException $e){
					$_SESSION['error'] = $e->getMessage();
					header('location: register.php');
				}

				$pdo->close();

			}

		}

	}
	else{
		$_SESSION['error'] = 'Fill up signup form first';
		header('location: signup.php');
	}

?>