<?php

session_start();

include_once 'db.php';

if(isset($_POST['delete'])){
	
	//select the last book of the user
	$query = "SELECT * FROM positions WHERE user = '" . $_SESSION['user'] . "' FOR UPDATE;";	
		
	try {
		mysqli_autocommit($conn, false);
		
		$res = mysqli_query($conn, $query);
		
		if(!$res){
		throw new Exception($conn->error);
		}
		
	}
	catch(Exception $e){
		mysqli_rollback($conn);
		mysqli_close($conn);
		mysqli_free_result($res);
		header("Location: userpage.php?book=queryfailed0");
		exit();
	}
	
	$n = mysqli_num_rows($res);
	
	//delete it
	$query = "DELETE FROM positions WHERE user = '" . $_SESSION['user'] . "' && n = '" . $n . "';";	
		
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
		header("Location: userpage.php?book=queryfailed1");
		exit();
	}
	
	mysqli_close($conn);
	mysqli_free_result($res);
	header("Location: userpage.php?book=delete_success");
	exit();
	
}
else{
	mysqli_close($conn);
	header("Location: userpage.php?book=notpost");
	exit();
}

?>
