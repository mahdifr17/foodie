<?php 
	session_start();
	require "../database.php";
	if (!isset($_SESSION["userlogin"])) {
		header("Location: dashboard.php");
	}

	$orderby = "waktu";
	$pageSelected = '1';
	$ordertype = "DESC";

	if (isset($_GET['orderby']) || isset($_GET['page']) || isset($_GET['ordertype'])) {
		$orderby = $_GET['orderby'];
		$pageSelected = $_GET['page'];
		$ordertype = $_GET['ordertype'];
	}

	$date = date("d/m/Y");
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
		$query = "SELECT DISTINCT P.notapembelian AS nomornota, B.waktu AS waktu, B.namasupplier AS supplier, U.nama AS namastaf FROM pembelian_bahan_baku P, supplier S, pembelian B, staf F, tk_basdat.user U 
		WHERE P.notapembelian = B.nomornota AND extract(day from waktu)=$day AND extract(month from waktu)=$month AND extract(year from waktu)=$year
			AND B.emailstaff = F.email AND F.email = U.email ORDER BY $orderby $ordertype LIMIT 15 OFFSET ($pageSelected-1)*15";
		$result1=pg_query($conn, $query);
		if (!$result1) {
		die("Error in SQL query: " . pg_last_error());
	}

	$num_rec_per_page = 15;
	$rows = pg_num_rows($result1);
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

    <!-- Dari http://www.zhudesign.com/tutorial-178-belajar-php--entry-data-tanggal-dengan-date-picker.html -->
    <link type="text/css" rel="stylesheet" href="../js/development-bundle/themes/ui-lightness/ui.all.css" />
    <script src="../js/development-bundle/jquery-1.8.0.min.js"></script>
    <script src="../js/development-bundle/ui/ui.core.js"></script>
    <script src="../js/development-bundle/ui/ui.datepicker.js"></script>
    <script src="../js/development-bundle/ui/i18n/ui.datepicker-id.js"></script>
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
		      		window.location.href = "pembelian.php?date=" + this.value + "&orderby=" + orderby + "&ordertype=" + ordertype + "&page=1";
		    	}
			);
	    });
        
</script>
	<title>FOODIE: Best Restaurant</title>
</head>

<body>
	<?php include '../navbar.php';?>
<div id='content'>
<div class='container content-container'>
	<h1>Foodie - Pembelian Bahan Makanan</h1>
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

					echo "<a class='btn btn-success' href='pembelian.php?date=$date&orderby=nomornota&ordertype=$ordertype&page=".$pageSelected."'>Nomor Nota</a>";
					echo " ";
					echo "<a class='btn btn-success' href='pembelian.php?date=$date&orderby=waktu&ordertype=$ordertype&page=".$pageSelected."'>Waktu</a>";
		 			echo " ";
		 			echo "<a class='btn btn-success' href='pembelian.php?date=$date&orderby=supplier&ordertype=$ordertype&page=".$pageSelected."'>Supplier</a>";
		 			echo " ";
		 			echo "<a class='btn btn-success' href='pembelian.php?date=$date&orderby=namastaf&ordertype=$ordertype&page=".$pageSelected."'>Staf</a>";
		 			echo "<br>";
					echo "<br>";
		 			echo "<a class='btn btn-success' href='pembelian.php?date=$date&orderby=".$orderby."&ordertype=ASC&page=".$pageSelected."'>Menaik</a>";
		 			echo " ";
					echo "<a class='btn btn-success' href='pembelian.php?date=$date&orderby=".$orderby."&ordertype=DESC&page=".$pageSelected."'>Menurun</a>";
				?>
			</div>
			<br>
			<div class="form-group"> 
				<label for="tanggal">Tanggal pembelian:</label>
				<input class="form-control" name="tgl" id="tgl" type="text" placeholder=<?php echo $day."/".$month."/".$year; ?>>
			</div>
			<br>
 
	<br>
	<div id="tabelPembelian">
				<?php
					if ($rows==0) {
						echo "<h3>Tidak ada pembelian</3>";
					} 
					else {
						$total = 0;
						echo "<table class='table table-striped'>
						<tr>
							<th>Nomor Nota<br></th>
							<th>Waktu<br></th>
							<th>Supplier</th>
							<th>Staf</th>
							<th></th>
						</tr>";
						while ($row = pg_fetch_row($result1)) {
							//cari bahan yang dibeli berdasarkan nomornota tertentu
							$sql2 = "SELECT DISTINCT * FROM pembelian WHERE nomornota='$row[1]'";
							$result2 = pg_query($conn, $sql2);
							if (!$result2) {
								die("Error in SQL query: " . pg_last_error());
							}
							
							echo "<tr>	
								<td align='left'>$row[0]</td>
								<td align='left'>$row[1]</td>
								<td align='left'>$row[2]</td>
								<td align='left'>$row[3]</td>
								<td align='left'><a href='pembelian-rinci.php?supplier=$row[2]&nomornota=$row[0]&waktu=$row[1]&staf=$row[3]'>RINCIAN</a></td>
							</tr>";
						}
						echo "</table>";
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