<?php
session_start();
include '../database.php';
$conn = connectDB();

if (!isset($_POST['nomor-nota'])) {
	header("Location: ../dashboard.php");
}

$noOfRows = count($_POST['jumlah']);

$notaName = $_POST['nomor-nota'];
$supplierName = $_POST['supplier'];
$staffEmail = $_SESSION['user_id'];

$date = getdate();
$today_string = $date['year'].'-'.$date['mon'].'-'.$date['mday'].' '.$date['hours'].':'.$date['minutes'].':'.$date['seconds'];

$query = "INSERT INTO PEMBELIAN VALUES('$notaName', '$today_string', '$supplierName', '$staffEmail');";
for ($i = 0; $i < $noOfRows; $i = $i + 1) {
	$bahanBaku = $_POST['bahan-baku'][$i];
	$hargaSatuan = $_POST['harga-satuan'][$i];
	$satuan = $_POST['satuan'][$i];
	$jumlah = $_POST['jumlah'][$i];
	
	$query .= "INSERT INTO PEMBELIAN_BAHAN_BAKU VALUES('$bahanBaku','$notaName', $jumlah, '$satuan', $hargaSatuan);";
}

$result = pg_query($conn, $query);
if (!$result) {
	die("Error in SQL query: " . pg_last_error());
}   


$query = "SELECT nama FROM tk_basdat.user WHERE email='$staffEmail'";
$result = pg_query($conn, $query);
$row = pg_fetch_assoc($result);
$nama = $row['nama'];

var_dump($nama);

pg_close($conn);
header("location: ../dashboard/pembelian-rinci.php?supplier=$supplierName&nomornota=$notaName&waktu=$today_string&staf=$nama");
?>