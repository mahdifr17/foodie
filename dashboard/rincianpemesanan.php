<?php	
	session_start();
	require "../database.php";
	$total = 0;
	$nomornota = $_GET['nomornota'];
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
	<h1>Rincian Pemesanan Makanan</h1>
	<?php 
		$sql2="SELECT * FROM pemesanan WHERE nomornota='$nomornota'";
		$result2 = pg_query($conn, $sql2);
		$row2=pg_fetch_array($result2);

		// mencari mode pembayaran setiap pemesanan
		$sql4 = "SELECT * FROM tk_basdat.mode_pembayaran WHERE kode='$row2[mode]'";
		$result4 = pg_query($conn, $sql4);
		if (!$result4) {
				die("Error in SQL query: " . pg_last_error());
		}
		$row4 = pg_fetch_array($result4);

		// mencari nama kasir
		$sql5 = "SELECT * FROM tk_basdat.user WHERE email='$row2[emailkasir]'";
		$result5 = pg_query($conn, $sql5);
		if (!$result5) {
				die("Error in SQL query: " . pg_last_error());
		}
		$row5 = pg_fetch_array($result5);

		echo "<table>
				<tr>
					<th align='left'>Nomor Nota</th>
					<th align='left'>:</th>
					<th align='left'>$row2[nomornota]</th>
				</tr>
				<tr>
					<th>Waktu Pesan</th>
					<th align='left'>:</th>
					<th>$row2[waktupesan]</th>
				</tr>
				<tr>
					<th>Waktu Bayar</th>
					<th align='left'>:</th>
					<th>$row2[waktubayar]</th>
				</tr>
				<tr>
					<th>Nama Kasir</th>
					<th align='left'>:</th>
					<th>$row5[nama]</th>
				</tr>
				<tr>
					<th>Email Kasir</th>
					<th align='left'>:</th>
					<th>$row2[emailkasir]</th>
				</tr>
				<tr>
					<th>Mode Bayar</th>
					<th align='left'>:</th>
					<th>$row4[nama]</th>
				</tr>
			</table>";

	?>
	
	<h3>Menu yang Dipesan</h3>
	
	<?php
		// query untuk mnecari menu yang dipesan oleh setiap nomor nota
		$sql="SELECT * FROM pemesanan_menu_harian WHERE nomornota='$nomornota'";
		$result = pg_query($conn, $sql);

		echo "<table class='table table-striped'>
				<tr>
					<th>Nama Menu<br></th>
					<th>Harga<br></th>
					<th>Jumlah</th>
					<th>Total Harga</th>
				</tr>";
		
		while ($row = pg_fetch_row($result)) {
			// query untuk mencari harga setiap menu
			$sql1="SELECT * FROM tk_basdat.menu WHERE nama='$row[1]'";
			$result1 = pg_query($conn, $sql1);
			$row1=pg_fetch_array($result1);
			$totalTemp = $row[3]*$row1['harga'];
			
			echo "<tr>	
				<td align='left'>$row[1]</td>
				<td align='left'>$row1[harga]</td>
				<td align='left'>$row[3]</td>
				<td align='left'>$totalTemp</td>
			</tr>";
		}
		// query untuk mencari total harga suatu nomornota
		$sql2 = "SELECT sum(A.harga*B.jumlah) AS total FROM tk_basdat.menu A, tk_basdat.pemesanan_menu_harian B WHERE B.namamenu=A.nama AND nomornota='$nomornota'";
		$result2 = pg_query($conn, $sql2);
		$row2=pg_fetch_array($result2);
		$total = $row2[0];
		if (!$result2) {
			$total = 0;
		}
		
		echo "<tr>
				<th></th>
				<th>Total</th>
				<th></th>
				<th>$total</th>
			</tr>


		</table>";
	?>
</div>
</div>

</body>
</html>