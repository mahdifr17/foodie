<?php
	require 'database.php';
	//echo phpinfo();
	$letter = chr(rand(65,90)).chr(rand(65,90)).rand(1000,9999);
	echo $letter;
	
	
	$conn = connectDB();
	$string = "";
	
	$sql = "SELECT DISTINCT * FROM tk_basdat.bahan_baku";
	$result = pg_query($conn, $sql);
	if (!$result) {
		die("Error in SQL query: " . pg_last_error());
	}   
	
	$rows=array(); 
	while($row = pg_fetch_assoc($result)) { 
		$rows[]=$row;
	}
	
	header("Content-type:application/json"); 
	echo json_encode($rows);
?>