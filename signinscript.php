<?php

include_once 'db.php';

if(isset($_POST['signin'])){
	
	//sanitize the input string from ddangerous characters
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$email = stripcslashes($email);
	$email = strip_tags($email);
	$email = trim($email);
	
	$password = $_POST['password'];
	
	//check if a field is empty
	if(empty($email) || empty($password)){
		mysqli_close($conn);
		header("Location: signin.php?signin=empty");
		exit();
	}
	else{
		//check if the email is valid
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			mysqli_close($conn);
			header("Location: signin.php?signin=invalid");
			exit();
		}
		else{
			
			//check if the password form is correct
			if(!preg_match("/[^a-zA-Z0-9].+.+|.+[^a-zA-Z0-9].+|.+.+[^a-zA-Z0-9]/", $password)){
				mysqli_close($conn);
				header("Location: signin.php?signin=invalidpwd");
				exit();
			}
			
			//try to select a user with the same email to see if it is present yet 
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
				header("Location: signin.php?signin=queryfailed1");
				exit();
			}
			
			//check the number of rows
			$rescheck = mysqli_num_rows($res);
			
			if($rescheck > 0){
				mysqli_close($conn);
				mysqli_free_result($res);
				header("Location: signin.php?signin=present");
				exit();
			}
			else{
				//hash the password
				$hashpwd = password_hash($password, PASSWORD_DEFAULT);
				
				//insert the user into the database
				$query = "INSERT INTO users (email, password) VALUES ('" . $email . "', '" . $hashpwd . "')";
				
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
					header("Location: signin.php?signin=queryfailed2");
					exit();
				}
				
				mysqli_close($conn);
				mysqli_free_result($res);
				header("Location: index.php?signin=success");
				exit();
			}
		}
	}
	
}
else{
	mysqli_close($conn);
	header("Location: signin.php?signin=notpost");
	exit();
}

?>
