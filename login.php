<?php

session_start();

include_once 'db.php';

if(isset($_POST['login'])){
	
	//sanitize the input string from ddangerous characters
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$email = stripcslashes($email);
	$email = strip_tags($email);
	$email = trim($email);
	
	$password = $_POST['password'];
	
	//check if a field is empty
	if(empty($email) || empty($password)){
		mysqli_close($conn);
		header("Location: index.php?login=empty");
		exit();
	}
	else{
		
		//select the user from the table
		$query = "SELECT * FROM users WHERE email='" . $email . "'";
		
		try {
			mysqli_autocommit($conn, false);
			
			$res = mysqli_query($conn, $query);
			
			if(!$res){
			throw new Exception($conn->error);
			}
			
			mysqli_commit($conn);
		}
		catch(Exception $e){
			mysqli_rollback($conn);
			mysqli_close($conn);
			mysqli_free_result($res);
			header("Location: index.php?login=queryfailed1");
			exit();
		}
		
		$rescheck = mysqli_num_rows($res);
		
		//if the number of rows are minor of 1 there is no user with this email
		if($rescheck < 1){
			mysqli_close($conn);
			mysqli_free_result($res);
			header("Location: index.php?login=notpresent");
			exit();
		}
		else{
			if($row = mysqli_fetch_assoc($res)){
				//compare the password
				$hashpwdcheck = password_verify($password, $row['password']);
				
				if($hashpwdcheck == true){
					$_SESSION['user'] = $row['email'];
					$_SESSION['time'] = time();
					mysqli_close($conn);
					mysqli_free_result($res);
					header("Location: userpage.php?login=success");
					exit();
				}
				else{
					mysqli_close($conn);
					mysqli_free_result($res);
					header("Location: index.php?login=wrongpwd");
					exit();
				}
			}
		}
	}
	
}
else{
	mysqli_close($conn);
	header("Location: index.php?login=notpost");
	exit();
}

?>
