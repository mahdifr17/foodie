<?php 
	session_start();
	require "../database.php";
	$supplier = $_GET['supplier'];
	$nomornota = $_GET['nomornota'];
	$waktu = $_GET['waktu'];
	$staf = $_GET['staf'];
	$conn = connectDB();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS and js from CDN-->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<!-- Own stylesheet -->
	<link rel="stylesheet" href="../css/style_common.css">
	<script src="../js/script_common.js"></script>

	<title>FOODIE: Best Restaurant</title>
</head>
<body>
	<?php include '../navbar.php';?>
	
<div id='content'>
	<div class='container content-container'>
	<h1>Rincian Pembelian Bahan Baku</h1>
	<?php 
		$sql0="SELECT * FROM pembelian WHERE namasupplier ='$supplier'";
		$result0 = pg_query($conn, $sql0);
		$row0=pg_fetch_array($result0);

		$sql1="SELECT * FROM pembelian_bahan_baku WHERE notapembelian ='$nomornota'";
		$result1 = pg_query($conn, $sql1);
		$row1=pg_fetch_array($result1);		

		$sql4="SELECT * FROM tk_basdat.user WHERE nama ='$staf'";
		$result4 = pg_query($conn, $sql4);
		$row4=pg_fetch_array($result4);		

		$totalHarga = 0;		


		echo "<table>
				<tr>
					<th>Nomor Nota</th>
					<th align='left'>:</th>
					<th align='left'>$nomornota</th>
				</tr>
				<tr>
					<th>Waktu</th>
					<th align='left'>:</th>
					<th>$waktu</th>
				</tr>
				<tr>
					<th>Supplier</th>
					<th align='left'>:</th>
					<th>$supplier</th>
				</tr>
				<tr>
					<th>Staf</th>
					<th align='left'>:</th>
					<th>$staf</th>
				</tr>
			</table>";

	?>
	
	<?php
		// query untuk mnecari menu yang dipesan oleh setiap nomor nota
		$sql="SELECT * FROM pembelian_bahan_baku WHERE notapembelian='$nomornota'";
		$result = pg_query($conn, $sql);

		echo "<table class='table table-striped'>
				<tr>
					<th>Nama Bahan<br></th>
					<th>Harga Satuan<br></th>
					<th>Satuan</th>
					<th>Jumlah</th>
					<th>Total</th>
				</tr>";
		

		while ($row = pg_fetch_row($result)) {
			$sql1="SELECT * FROM tk_basdat.pembelian_bahan_baku WHERE notapembelian= '$nomornota'";
			$result1 = pg_query($conn, $sql1);
			$row1=pg_fetch_array($result1);
			$totalbeli= $row[2]*$row[4];
			

			echo "<tr>	
				<td align='left'>$row[0]</td>
				<td align='left'>$row[4]</td>
				<td align='left'>$row[3]</td>
				<td align='left'>$row[2]</td>
				<td align='left'>$totalbeli</td>
			</tr>";

			$totalHarga = $totalHarga + $totalbeli;
		}
		// query untuk mencari total harga suatu nomornota
		
		echo "<tr>
			</tr>


		</table>";
		echo "Total: $totalHarga";
	?>
</div>
</div>
</div>
</body>
</html>