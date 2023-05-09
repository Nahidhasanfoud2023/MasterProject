<?php

	include 'includes/session.php';
	$conn = $pdo->open();

	if(isset($_POST['login'])){
		
		$email = $_POST['email'];
		$password = $_POST['password'];

		try{

			$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE email = :email");
			$stmt->execute(['email'=>$email]);
			$row = $stmt->fetch();


			if($row['numrows'] > 0){

				if($row['status']){	
					
					if(password_verify($password, $row['password'])){

						$remword = $row['remword'];
						$strLen = strlen($remword);
						// pos => position
						$posGap = (int)($strLen / 3);
						$pos1 = 0;
						$pos2 = $pos1 + $posGap;
						$pos3 = $pos2 + $posGap;
						$posArr = [];
						
						$posArr[] =  rand($pos1, ($pos2 - 1));
						$posArr[] =  rand($pos2, ($pos3 - 1));
						$posArr[] =  rand($pos3, ($strLen - 1));
						$_SESSION['posArr'] = $posArr;
						$_SESSION['email'] = $email;
						
						header('location: words.php');
						exit();
						
					}
					else{

						$_SESSION['error'] = 'Incorrect Password';
					}

					
				}
				else{
					$_SESSION['error'] = 'Account not activated.';
				}	


	    	}

			else{
				$_SESSION['error'] = 'Email not found';
			}
		  

		}
		catch(PDOException $e){
			echo "There is some problem in connection: " . $e->getMessage();
		}

	

	}
	else{
		$_SESSION['error'] = 'Input login credentails first';
	}

	$pdo->close();

	header('location: login.php');

?>