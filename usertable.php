<?php
	
	include_once 'size.php';
	include_once 'db.php';
	
	//create the grid element
    echo '<table class="grid">';
	
    for ($r=0; $r<$rows; $r++){
		
		//create the row element
        echo "<tr>";
        
		for ($c=0; $c<$cols; $c++){
			//flag to verify if the cell is booked by the user logged
			$my_book = false;
			
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
			
			for($i=0; $i<$n; $i++){
				$line = mysqli_fetch_array($res);
				
				//if is true the current cell is booked by the user logged
				if($line['user'] == $_SESSION['user']){
					$my_book = true;
				}
			}
			
			//create the id to insert for each element that indicate the coordinate
			$id = "p-" . $r . "-" . $c;
			
			//print the data cells, if booked are black
			if($n > 0){
				if($my_book){
					echo ' <td id="' . $id . '" class="mybook"> </td> ';
				}
				else{
					echo ' <td id="' . $id . '" class="booked"> </td> ';
				}
			}
			else{
				//create the data cells and instantiate an handler for th click event
				echo ' <td id="' . $id . '" onclick="clicked(this.id)"> </td> ';
			}
        }
		
		echo "</tr>";
    }
	
	//close the grid element
    echo '</table>';
	
		
?>
		