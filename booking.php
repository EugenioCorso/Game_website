<?php

session_start();

include_once 'size.php';
include_once 'db.php';

if(isset($_POST['book'])){
	
	function error(){
		global $conn;
		
		mysqli_close($conn);
		echo '
			<script>
			alert("Booking operation failed, invalid position");
			window.location.href = "userpage.php?book=invalid";
			</script>
		';
		exit();
	}
	
	function set($row_s, $col_s, $row_e, $col_e){
		global $rows, $cols, $matrix;
		
		if($row_s == $row_e){
			//the shape is in the same row
			for($i=$col_s; $i<=$col_e; $i++){
				
				//check up
				if($row_s != 0){
					if($matrix[$row_s-1][$i] == 1){
						error();
					}
				}
				
				//check down
				if($row_s < ($rows - 1)){
					if($matrix[$row_s+1][$i] == 1){
						error();
					}
				}
				
				//check left and diagonal
				if( ($i == $col_s) && ($col_s > 0) ){
					if($matrix[$row_s][$i-1] == 1){
						error();
					}
					
					//check up left
					if($row_s > 0){
						if($matrix[$row_s-1][$i-1] == 1){
							error();
						}
					}
					
					//check down left
					if($row_s < ($rows-1) ){
						if($matrix[$row_s+1][$i-1] == 1){
							error();
						}
					}
				}
				
				//check right and diagonal
				if( ($i == $col_e) && ($col_e < ($cols-1) ) ){
					if($matrix[$row_s][$i+1] == 1){
						error();
					}
					
					//check up right
					if($row_s > 0){
						if($matrix[$row_s-1][$i+1] == 1){
							error();
						}
					}
					
					//check down right
					if($row_s < ($rows-1) ){
						if($matrix[$row_s+1][$i+1] == 1){
							error();
						}
					}
				}
				
				$matrix[$row_s][$i] = 1;
			}
		}
		else{
			//the shape is in the same col
			
			for($i=$row_s; $i<=$row_e; $i++){
				//check left
				if($col_s > 0){
					if($matrix[$i][$col_s-1] == 1){
						error();
					}
				}
				
				//check right
				if($col_s < ($cols-1) ){
					if($matrix[$i][$col_s+1] == 1){
						error();
					}
				}
				
				//check up and diagonal
				if( ($i == $row_s) && ($row_s > 0) ){
					if($matrix[$i-1][$col_s] == 1){
						error();
					}
					
					//check up left
					if($col_s > 0){
						if($matrix[$i-1][$col_s-1] == 1){
							error();
						}
					}
					
					//check up right
					if($col_s < ($cols-1)){
						if($matrix[$i-1][$col_s+1] == 1){
							error();
						}
					}
				}
				
				//check down and diagonal
				if( ($i == $row_e) && ($row_e < ($rows-1) ) ){
					if($matrix[$i+1][$col_s] == 1){
						error();
					}
					
					//check down right
					if($col_s > 0){
						if($matrix[$i+1][$col_s-1] == 1){
							error();
						}
					}
					
					//check down right
					if($col_s < ($cols-1) ){
						if($matrix[$i+1][$col_s+1] == 1){
							error();
						}
					}
				}
				
				$matrix[$i][$col_s] = 1;
			}
		}
	}
	
	$rs = $_POST['start_r'];
	$cs = $_POST['start_c'];
	$re = $_POST['end_r'];
	$ce = $_POST['end_c'];
	
	if( $_POST['start_r']==NULL || $_POST['start_c']==NULL || $_POST['end_r']==NULL || $_POST['end_c']==NULL){
	//if(empty($rs) || empty($rc) || empty($re) || empty($ce)){
		mysqli_close($conn);
		echo '
			<script>
			alert("Booking operation failed, fields empty");
			window.location.href = "userpage.php?book=empty";
			</script>
		';
		exit();
	}
	else{
		
		//check if the segment is diagonal
		if(($rs != $re) && ($cs != $ce)){
			mysqli_close($conn);
			echo '
				<script>
				alert("Booking operation failed, segment diagonal");
				window.location.href = "userpage.php?book=diagonal";
				</script>
			';
			exit();
		}
		
		//number of book done by the user, initialized at one 
		//because of this is one more among the ones did
		$n_book = 1;
		
		//order the col and the row
		if($cs < $ce){
			$start_col = $cs;
			$end_col = $ce;
		}
		else{
			$start_col = $ce;
			$end_col = $cs;
		}
		
		if($rs < $re){
			$start_row = $rs;
			$end_row= $re;
		}
		else{
			$start_row = $re;
			$end_row = $rs;
		}
		
		
		//check the size of the segment
		if($start_row == $end_row){
			if( ( $end_col - $start_col + 1) != $lshape){
				mysqli_close($conn);
				echo '
					<script>
					alert("Booking operation failed,  segment dimension ' . ( $end_col - $start_col + 1) . ' wrong");
					window.location.href = "userpage.php?book=wrg_sgm";
					</script>
				';
				mysqli_free_result($res);
				exit();
			}
		}
		else{
			if( ( $end_row - $start_row + 1) != $lshape){
				mysqli_close($conn);
				echo '
					<script>
					alert("Booking operation failed,  segment dimension wrong");
					window.location.href = "userpage.php?book=wrg_sgm";
					</script>
				';
				mysqli_free_result($res);
				exit();
			}
		}
		
		//initialize the matrix
		$matrix = array();
		for($i=0; $i<$rows; $i++){
			$matrix[$i] = array();
			for($j=0; $j<$cols; $j++){
				$matrix[$i][$j] = 0;
			}
		}
		
		$query = "SELECT * FROM positions FOR UPDATE;";
		
		try {
			mysqli_autocommit($conn, false);
			
			$res = mysqli_query($conn, $query);
			
			if(!$res){
			throw new Exception($conn->error);
			}
			
			//mysqli_commit($conn);
		}
		catch(Exception $e){
			mysqli_rollback($conn);
			mysqli_close($conn);
			echo '
				<script>
				alert("Booking operation failed,  query unsuccessful");
				window.location.href = "userpage.php?book=queryfailed_1";
				</script>
			';
			mysqli_free_result($res);
			exit();
		}
		
		$n = mysqli_num_rows($res);
		
		for($j=0; $j<$n; $j++){
			$line = mysqli_fetch_array($res);
			
			//update the number of book done by the user
			if($line['user'] == $_SESSION['user']){
				$n_book++;
			}
			
			$row_s = $line['rowstart'];
			$col_s = $line['colstart'];
			$row_e = $line['rowend'];
			$col_e = $line['colend'];
			
			//set the start and the end on the matrix as booked
			$matrix[$row_s][$col_s] = 1;
			$matrix[$row_e][$col_e] = 1;
			
			//do it with all the cells between
			set($row_s, $col_s, $row_e, $col_e);
				
		}
		
		//put in the matrix the requested segment
		set($start_row, $start_col, $end_row, $end_col);
		
		//insert the new book
		$query = "INSERT INTO positions (user, rowstart, colstart, rowend, colend, n) VALUES ('" . $_SESSION['user'] . "', '" . $start_row . "', '" . $start_col . "', 
																								'" . $end_row . "', '" . $end_col . "', '" . $n_book . "');";
		
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
			echo '
				<script>
				alert("Booking operation failed,  query unsuccessful");
				window.location.href = "userpage.php?book=queryfailed_2";
				</script>
			';
			mysqli_free_result($res);
			exit();
		}
		
		mysqli_close($conn);
		
		echo '
			<script>
			alert("Booking operation successful");
			window.location.href = "userpage.php?book=success";
			</script>
		';
		mysqli_free_result($res);
		exit();
	}
}
else{
	mysqli_close($conn);
	echo '
		<script>
		alert("Book operation failed");
		window.location.href = "userpage.php?book=notpost";
		</script>
	';
	exit();
}

?>
