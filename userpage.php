<?php 
	session_start();
	
	include_once 'timesession.php';
	
	include_once 'size.php';
	
	if (!isset($_SESSION['user'])) {
		header("Location: index.php?notlogged");
		exit();
	}
	
	if($_SERVER['HTTPS'] != "on"){
		header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}
?>
	
<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" type="text/css" href="style.css">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		
		<script>
			var oneClicked = 0;
			var row_s;
			var col_s;
			var row_e;
			var col_e;

			function clicked (id_el){
				el = document.getElementById(id_el);
				
				//split the string to obtain the row and the col
				id = id_el.split("-");
				
				//verify if the cell is not colored yet
				if(!el.className){
					if(oneClicked < 2){
					
						oneClicked++;
						
						if (oneClicked == 1){ 
							//set the star row and col
							row_s = Number(id[1]);
							col_s = Number(id[2]);
							
							//set the value of the row and the col for the booking
							document.getElementById("r1").value = row_s;
							document.getElementById("c1").value = col_s;
							
							el.className='clicked';
						}
						else{
							//set the end row and col
							row_e = Number(id[1]);
							col_e = Number(id[2]);
							
							lshape = <?php echo $lshape ?>;
							
							//check if the shape isn't diagonal
							if( (row_e == row_s) || (col_e == col_s) ){
								el.className='clicked';
								var start_r, start_c, end_r, end_c;
								
								//set the value of the row and the col for the booking
								document.getElementById("r2").value = row_e;
								document.getElementById("c2").value = col_e;
								
								//if the shape is in the same row
								if(row_s == row_e){
									
									if(col_s < col_e){
										start_c = col_s;
										end_c = col_e;
									}
									else{
										start_c = col_e;
										end_c = col_s;
									}
									
									//check the lenght of the shape
									if( ( end_c - start_c + 1) != lshape){
										alert("Shape must be lenght " + lshape);
										undo();
									}
									else{
										for(var i=start_c; i<end_c; i++){
											id = "p-" + row_s + "-" + i;
											elm_tmp = document.getElementById(id);
											
											if( (elm_tmp.className == "booked") || (elm_tmp.className == "mybook") ){
												alert("Overlapp with a position already booked");
												undo();
											}
											else{
												elm_tmp.className = "clicked";
											}
										}
									}
								}
								else{
									//the shape is in the same col
									
									if(row_s < row_e){
										start_r = row_s;
										end_r = row_e;
									}
									else{
										start_r = row_e;
										end_r = row_s;
									}
									
									//check the lenght of the shape
									if( ( end_r - start_r + 1) != lshape){
										alert("Shape must be lenght " + lshape);
										undo();
									}
									else{
										for(var i=start_r; i<end_r; i++){
											id = "p-" + i + "-" + col_s;
											elm_tmp = document.getElementById(id);
											
											if( (elm_tmp.className == "booked") || (elm_tmp.className == "mybook") ){
												alert("Overlapp with a position already booked");
												undo();
											}
											else{
												elm_tmp.className = "clicked";
											}
										}
									}
								}
							}
							else{
								oneClicked--;
								alert("The shape can not be diagonal");
							}
						}
					}
				}
			}
			
			function undo(){
				rows = <?php echo $rows ?>;
				cols = <?php echo $cols ?>;
				
				window.location.href = "userpage.php";
			}

		</script>
		
		<title>Personal page</title>

	</head>

	<body class="bg">
	
		
		<div  class="sidenav" id="menu">
			
			<a class="nav-link" href="index.php">
				<button type="button" class="btn btn-warning">Home</button>
			</a>
			  
			<a class="nav-link">
				<form method="POST" action="logout.php">
					<button type="submit" class="btn btn-danger" name="logout"> Logout </button>
				</form>
			</a>
			
		</div>
		
		<h1 style="color:white">
			Welcome
			<?php 
				echo $_SESSION['user'];
			?>
		</h1>	
		
		<div class="container">
			<div id="box1" class="row align-items-center">

				<div class="col">
				</div>
				
				<div id="box2" class="col-5 align-self-center">
					
					<?php 
						include_once 'usertable.php';
					?>
					
					
					<div class="text-center">
						<button class="btn btn-info" name="undo" onclick="undo()"> Undo </button>
					
						<form id="reservation" action="booking.php" method="POST">
							<input type="hidden" id="r1" name="start_r" value="">
							<input type="hidden" id="c1" name="start_c" value="">
							<input type="hidden" id="r2" name="end_r" value="">
							<input type="hidden" id="c2" name="end_c" value="">
							
							
							<button type="submit" class="btn btn-success" name="book">Book</button>
						</form>
						
						
						<form action="delete.php" method="POST">
							<button type="submit" class="btn btn-danger" name="delete">Delete</button>	
						</form>
						
					</div>
					
				</div>
		
				<div class="col">
				</div>

			</div>
		</div>
		
		<script src="js/bootstrap.min.js"></script>

	</body>

</html>