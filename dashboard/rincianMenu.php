<?php	
	session_start();
	
	function getDetails() {
		require "../database.php";
		
		$nama = $_GET['nama'];
		$conn = connectDB();
		
		$sql="SELECT m.nama as nama_menu, m.gambar, m.harga, m.jumlahtersedia, k.nama AS nama_kategori, m.deskripsi FROM menu m, kategori k WHERE m.kategori=k.kode AND m.nama='$nama'";
		$result = pg_query($conn, $sql);
		$rows=pg_fetch_array($result);
		if ($rows < 1) {
			echo 'details not found';
		}
		echo "<h1><strong>";
		echo $rows['nama_menu'];
		echo "</strong></h1>";
		echo "<img src='";
		echo $rows['gambar'];
		echo "'><br> Harga :";
		echo $rows['harga'];
		echo "<br> Tersedia :";
		echo $rows['jumlahtersedia'];
		echo "<br> Kategori :";
		echo $rows['nama_kategori'];
		echo "<br> Deskripsi :";
		echo $rows['deskripsi'];
	}
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
    <!--jquery ui-->
	<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<!-- Own stylesheet -->
	<link rel="stylesheet" href="../css/style_common.css">
	<script src="../js/script_common.js"></script>

	<title>FOODIE: Best Restaurant</title>
</head>


<body>
<?php include '../navbar.php'; ?>
	<div id="content">
		<div class='container content-container'>
			<?php getDetails();?>
		</div>
	</div>
</body>