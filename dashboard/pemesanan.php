<?php 
	session_start();
	require "../database.php";
	
	if (!isset($_SESSION["userlogin"])) {
		header("Location: ../dashboard.php");
	}
	
	$orderby = 'waktupesan';
	$pageSelected = '1';
	$ordertype = 'DESC';

	if (isset($_GET['orderby']) || isset($_GET['page']) || isset($_GET['ordertype'])) {
		$orderby = $_GET['orderby'];
		$pageSelected = $_GET['page'];
		$ordertype = $_GET['ordertype'];
	}

	$date = date("Y/m/d");
	$pisah = explode('/',$date);
	$day = $pisah[2];
	$month = $pisah[1];
	$year = $pisah[0];

	if (isset($_GET['date'])) {
		$date = $_GET['date'];
		$pisah = explode('/',$date);
		$day = $pisah[2];
		$month = $pisah[1];
		$year = $pisah[0];
	}

	$conn = connectDB();
	$query = "SELECT * FROM tk_basdat.pemesanan WHERE extract(day from waktupesan)=$day AND extract(month from waktupesan)=$month AND extract(year from waktupesan)=$year ORDER BY $orderby $ordertype LIMIT 15 OFFSET ($pageSelected-1)*15";
	$result1 = pg_query($conn, $query);
	if (!$result1) {
		die("Error in SQL query: " . pg_last_error());
	}
	
	$sql = "SELECT * FROM tk_basdat.pemesanan WHERE extract(day from waktupesan)=$day AND extract(month from waktupesan)=$month AND extract(year from waktupesan)=$year";
	$result = pg_query($conn, $sql);
	if (!$result) {
		die("Error in SQL query: " . pg_last_error());
	}
	
	$num_rec_per_page = 15;
	$rows = pg_num_rows($result);
	$total_pages = ceil($rows / $num_rec_per_page);
	

	
	
	
?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS and js from CDN-->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <!--jquery ui-->
	<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<!-- Own stylesheet -->
	<link rel="stylesheet" href="../css/style_common.css">
	<script src="../js/script_common.js"></script>
	<script>
		$(function() {
			var orderby = '<?php echo $orderby; ?>';
			var ordertype = '<?php echo $ordertype; ?>';
	        $("#tgl").datepicker({
		      dateFormat: "yy/mm/dd",
		      onSelect: function(dateText) {
		        $(this).change();
		      }
		    })
		    .change(function() {
		      		window.location.href = "pemesanan.php?date=" + this.value + "&orderby=" + orderby + "&ordertype=" + ordertype + "&page=1";
		    	}
			);
	    });
	</script>

	<title>FOODIE: Best Restaurant</title>
</head>
<body>
	<?php include "../navbar.php" ?>
	<div id='content'>
		<div class='container content-container'>
			<h1>Daftar Pemesanan</h1>
			

			<style>
				#urutan a  {
					text-decoration: none;
				    color: white;
				}
			</style>

			<div id="urutan">
				<label for="tanggal">Urutkan Berdasarkan:</label>
				<br>
				<?php
					echo "<a class='btn btn-success' href='pemesanan.php?date=$date&orderby=nomornota&ordertype=$ordertype&page=".$pageSelected."'>Nomor Nota</a>";
					echo " ";
					echo "<a class='btn btn-success' href='pemesanan.php?date=$date&orderby=waktupesan&ordertype=$ordertype&page=".$pageSelected."'>Waktu Pesan</a>";
		 			echo " ";
		 			echo "<a class='btn btn-success' href='pemesanan.php?date=$date&orderby=emailkasir&ordertype=$ordertype&page=".$pageSelected."'>Email Kasir</a>";
		 			echo "<br>";
		 			echo "<br>";
		 			echo "<a class='btn btn-success' href='pemesanan.php?date=$date&orderby=".$orderby."&ordertype=ASC&page=".$pageSelected."'>Menaik</a>";
		 			echo " ";
					echo "<a class='btn btn-success' href='pemesanan.php?date=$date&orderby=".$orderby."&ordertype=DESC&page=".$pageSelected."'>Menurun</a>";
				?>
			</div>
			<br>
			<div class="form-group"> 
				<label for="tanggal">Tanggal Pemesanan:</label>
				<input class="form-control" name="tgl" id="tgl" type="text" placeholder=<?php echo $day."/".$month."/".$year; ?>>
			</div>
			<br>
			<div id="tabelPemesanan">
				<?php
					if ($rows==0) {
						echo "<h3>Tidak ada Pemesanan</3>";
					} 
					else {
						$total = 0;
						echo "<h3>Banyak Pemesanan: $rows</h3>";
						echo "<table class='table table-striped'>
						<tr>
							<th>Nomor Nota<br></th>
							<th>Waktu Pesan<br></th>
							<th>Waktu Bayar</th>
							<th>Total</th>
							<th>Email Kasir</th>
							<th>Mode Bayar</th>
							<th>Detail</th>
						</tr>";
						while ($row = pg_fetch_row($result1)) {
							//cari menu yang dipesan oleh nomornota tertentu
							$sql2 = "SELECT * FROM tk_basdat.pemesanan_menu_harian WHERE nomornota='$row[0]'";
							$result2 = pg_query($conn, $sql2);
							if (!$result2) {
								die("Error in SQL query: " . pg_last_error());
							}

							// query untuk mencari total harga suatu nomornota
							$sql3 = "SELECT sum(A.harga*B.jumlah) AS total FROM tk_basdat.menu A, tk_basdat.pemesanan_menu_harian B WHERE B.namamenu=A.nama AND nomornota='$row[0]'";
							$result3 = pg_query($conn, $sql3);
							$row3=pg_fetch_array($result3);
							$totalHarga = $row3[0];

							// mencari mode pembayaran setiap pemesanan
							$sql4 = "SELECT * FROM tk_basdat.mode_pembayaran WHERE kode='$row[5]'";
							$result4 = pg_query($conn, $sql4);
							if (!$result4) {
									die("Error in SQL query: " . pg_last_error());
							}
							$row4 = pg_fetch_array($result4);
							
							echo "<tr>	
								<td align='left'>$row[0]</td>
								<td align='left'>$row[1]</td>
								<td align='left'>$row[2]</td>
								<td align='left'>$totalHarga</td>
								<td align='left'>$row[4]</td>
								<td align='left'>$row4[nama]</td>
								<td align='left'><a href='rincianpemesanan.php?nomornota=$row[0]'>Lihat</a></td>
							</tr>";
						}
						$sql99 = "SELECT sum(A.harga*B.jumlah) AS total FROM tk_basdat.menu A, tk_basdat.pemesanan_menu_harian B WHERE B.namamenu=A.nama AND nomornota IN (SELECT nomornota FROM tk_basdat.PEMESANAN WHERE extract(day from waktupesan)=$day AND extract(month from waktupesan)=$month AND extract(year from waktupesan)=$year)";
						$result99 = pg_query($conn, $sql99);
						$row99 = pg_fetch_assoc($result99);
						$total = $row99['total'];
							
						echo "</table>";
						echo "<h3 align='right'> Total: $total</h3>";
					}
				?>
				
				
			</div>

			
			<ul class="pagination">
			<?php
				for ($i=1; $i<=$total_pages; $i++) { 
					echo "<li><a href='pemesanan.php?date=".$date."&orderby=".$orderby."&ordertype=".$ordertype."&page=".$i."'>".$i."</a></li>"; 
				}; 
			?>
			</ul>
		</div>
	</div>
</body>
</html>