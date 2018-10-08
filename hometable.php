<?php
	
	include_once 'size.php';
	include_once 'db.php';
	
	//create the grid element
    echo '<table class="grid">';
	
    for ($r=0; $r<$rows; $r++){
		
		//create the row element
        echo "<tr>";
        
		for ($c=0; $c<$cols; $c++){
			
			//to verify if the current cell is between some booking 
			$query = "SELECT * FROM positions WHERE
						( (rowstart='" . $r ."' && rowend='" . $r ."') && ( (colstart<='" . $c ."' && colend>='" . $c ."') || (colstart>='" . $c ."' && colend<='" . $c ."') ) ) ||
						( (colstart='" . $c ."' && colend='" . $c ."') && ( (rowstart<='" . $r ."' && rowend>='" . $r ."') || (rowstart>='" . $r ."' && rowend<='" . $r ."') ) )
						";
		
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
				header("Location: index.php?destination=queryfailed");
				exit();
			}
			
			$n = mysqli_num_rows($res);
			
			//print the data cells, if booked are black
			if($n > 0){
				echo ' <td class="booked"> </td> ';
			}
			else{
				echo ' <td> </td> ';
			}
        }
		
		echo "</tr>";
    }
	
	//close the grid element
    echo '</table>';
	
	mysqli_free_result($res);
		
		
?>
		