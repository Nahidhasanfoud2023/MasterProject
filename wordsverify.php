<?php

	include 'includes/session.php';
	$conn = $pdo->open();

	if(isset($_POST['verify'])){
		
		$lettersOnNthPositions = $_POST['remword'];	
		if(count($lettersOnNthPositions)>0){
			$email = $_SESSION['email'];
			try{
				$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE email = :email");
				$stmt->execute(['email'=>$email]);
				$row = $stmt->fetch();
	
				if($row['numrows'] > 0){
	
					if($row['status']){
						$remword = $row['remword'];
						$tag = 0;
						foreach ($_SESSION['posArr'] as $index => $pos) {
							if (strtolower($lettersOnNthPositions[$index]) == strtolower($remword[$pos])) {
								$tag = 1;
							} else {
								$tag = 0;
								break;
							}
						}
						if ($tag == 1) {
							if($row['type']){
								$_SESSION['admin'] = $row['id'];
								unset($_SESSION['posArr']);
								unset($_SESSION['email']);
							}
							else{
								$_SESSION['user'] = $row['id'];
							}
						} else {
							$_SESSION['error'] = 'words did not match';
							header('location: login.php');
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
	}
	else{
		$_SESSION['error'] = 'Input login credentails first';
	}

	$pdo->close();

	header('location: login.php');

?>