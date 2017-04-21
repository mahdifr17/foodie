<?php
	session_start();
	require "../database.php";
	
	$date = date("Y/m/d");
	$pisah = explode('/',$date);
	$day = $pisah[2];
	$month = $pisah[1];
	$year = $pisah[0];

	if (isset($_GET['date'])){
		$date = $_GET['date'];
		$pisah = explode('/',$date);
		$day = $pisah[2];
		$month = $pisah[1];
		$year = $pisah[0];
	}
	
	$orderby = 'nama_menu';
	$ordertype = 'DESC';

	if (isset($_GET['orderby']) || isset($_GET['ordertype'])) {
		$orderby = $_GET['orderby'];
		$ordertype = $_GET['ordertype'];
	}

	function appendrow() {
		
		$conn = connectDB();
		
		$limit = 15;
		$offset = 0;
		$date = date("Y/m/d");
		$pisah = explode('/',$date);
		$day = $pisah[2];
		$month = $pisah[1];
		$year = $pisah[0];
		
		$orderby = 'nama_menu';
		$ordertype = 'DESC';

		if (isset($_GET['orderby']) || isset($_GET['ordertype'])) {
			$orderby = $_GET['orderby'];
			$ordertype = $_GET['ordertype'];
		}

		if (isset($_GET['limit']) || isset($_GET['offset'])) {
			$limit = $_GET['limit'];
			$offset = $_GET['offset'];
		}
		if (isset($_GET['date'])){
			$date = $_GET['date'];
			$pisah = explode('/',$date);
			$day = $pisah[2];
			$month = $pisah[1];
			$year = $pisah[0];
		}
		
		$result = pg_query($conn,"SELECT DISTINCT m.nama AS nama_menu, m.deskripsi, m.harga, m.jumlahtersedia, k.nama AS nama_kategori FROM menu m, menu_harian mh, kategori k where m.nama=mh.namamenu AND m.kategori=k.kode AND extract(day from waktu)=".$day." AND extract(month from waktu)=".$month." AND extract(year from waktu)=".$year."ORDER BY $orderby $ordertype LIMIT ".$limit." OFFSET ".$offset);
		$data = "<table class='table table-striped'><tr><th>Nama<br></th><th>Deskripsi<br></th><th>Harga</th><th>Jumlah Tersedia</th><th>Kategori</th><th>Detail</th></tr><div id='listPost'>";
		
		while($row = pg_fetch_assoc($result)){
			$data = $data . "<tr><td>" . $row['nama_menu'] . "</a></td>" .
			"<td>" . $row['deskripsi'] . "</td>" .
			"<td align='center'>" . $row['harga'] . "</td>" .
			"<td align='center'>" . $row['jumlahtersedia'] . "</td>" .
			"<td align='center'>" . $row['nama_kategori'] . "</td>" .
			"<td><a class='menu-details' href='rincianMenu.php?nama=" . $row['nama_menu'] . "'>Lihat</a></td></tr>";
		}
		$data = $data . "</div></table>";
		if (pg_num_rows($result) == 0) {
			$data = "<h3>Tidak ada Menu Harian</h3>";
		} 
		pg_close($conn);
		return $data;
	}
	
	function appendPagination() {
		$limit = 15;
		$offset = 0;
		$date = date("y/m/d");
		$pisah = explode('/',$date);
		$day = $pisah[2];
		$month = $pisah[1];
		$year = $pisah[0];
		
		$orderby = 'nama_menu';
		$ordertype = 'DESC';
		
		if (isset($_GET['orderby']) || isset($_GET['ordertype'])) {
			$orderby = $_GET['orderby'];
			$ordertype = $_GET['ordertype'];
		}
		
		if (isset($_GET['limit']) || isset($_GET['offset'])) {
			$limit = $_GET['limit'];
			$offset = $_GET['offset'];
		}

		if (isset($_GET['date'])){
			$date = $_GET['date'];
			$pisah = explode('/',$date);
			$day = $pisah[2];
			$month = $pisah[1];
			$year = $pisah[0];
		}
		
		$conn = connectDB();
		
		$result = pg_query($conn,"SELECT DISTINCT m.nama AS nama_menu, m.deskripsi, m.harga, m.jumlahtersedia, k.nama AS nama_kategori FROM menu m, menu_harian mh, kategori k where m.nama=mh.namamenu AND m.kategori=k.kode AND extract(day from waktu)=".$day." AND extract(month from waktu)=".$month." AND extract(year from waktu)=".$year."ORDER BY nama_menu ASC LIMIT ".$limit." OFFSET ".$offset);

		$resultForAppend = pg_query($conn,"SELECT DISTINCT m.nama AS nama_menu, m.deskripsi, m.harga, m.jumlahtersedia, k.nama AS nama_kategori FROM menu m, menu_harian mh, kategori k where m.nama=mh.namamenu AND m.kategori=k.kode AND extract(day from waktu)=".$day." AND extract(month from waktu)=".$month." AND extract(year from waktu)=".$year."ORDER BY nama_menu ASC");

		$numrow = pg_num_rows($resultForAppend);
		pg_close($conn);
		
		$ret = '<ul class="pagination pagination-lg">';
		
		for($i = 0; $i <= $numrow / $limit; $i = $i + 1) {
			if ($i == $offset / $limit) {
				$ret = $ret . '<li class="active"><a href="menu.php?limit='.$limit.'&offset='.($limit * $i).'&date=' .$date.'&orderby='.$orderby.'&ordertype='.$ordertype.'">'.($i+1).'</a></li>';
			} else {
				$ret = $ret . '<li><a href="menu.php?limit='.$limit.'&offset='.($limit * $i).'&date=' .$date.'&orderby='.$orderby.'&ordertype='.$ordertype.'">'.($i+1).'</a></li>';
			}
		}
		
		$ret = $ret . '</ul>';
		if (pg_num_rows($result) == 0) {
			$ret = "";
		} 
		return $ret;
	}
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
	        $("#datepicker")	
		    .datepicker({
		      dateFormat: "yy/mm/dd",
		      onSelect: function(dateText) {
		        $(this).change();
		      }
		    })
		    .change(function() {
		      		window.location.href = "menu.php?date=" + this.value;
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
			<h1>Daftar Menu</h1>
			<div id="urutan">
				<label for="tanggal">Urutkan Berdasarkan:</label>
				<br>
				<?php
					echo "<a class='btn btn-success' href='menu.php?date=$date&orderby=nama_menu&ordertype=$ordertype'>Nama</a>";
					echo " ";
					echo "<a class='btn btn-success' href='menu.php?date=$date&orderby=harga&ordertype=$ordertype'>Harga</a>";
		 			echo " ";
		 			echo "<a class='btn btn-success' href='menu.php?date=$date&orderby=nama_kategori&ordertype=$ordertype'>Kategori</a>";
		 			echo "<br>";
		 			echo "<br>";
		 			echo "<a class='btn btn-success' href='menu.php?date=$date&orderby=$orderby&ordertype=ASC'>Menaik</a>";
		 			echo " ";
					echo "<a class='btn btn-success' href='menu.php?date=$date&orderby=$orderby&ordertype=DESC'>Menurun</a>";
				?>
			</div>
			<br>
			<p>Date: <input class='form-control' type="text" id="datepicker" readonly placeholder=<?php echo $day."/".$month."/".$year; ?>></p>


					<?php
						echo appendrow();
					?>					

			
					<?php echo appendPagination(); ?>
			
		</div>
	</div>
</body>