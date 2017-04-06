<?php 
	session_start();
	require "../database.php";
	
	function notaRandomizer() {
		$equal = true;
		
		$conn = connectDB();
		$code = '';
		
		while ($equal) {
			// generate 2 letters, 4 numbers nota code
			$letter = chr(rand(65,90)).chr(rand(65,90)).rand(1000,9999); 
		
			//   query the database to return username and password existence
			$sql = "SELECT nomornota FROM tk_basdat.pembelian WHERE nomornota='$letter'";
			$result = pg_query($conn, $sql);
			if (!$result) {
				die("Error in SQL query: " . pg_last_error());
			}   
			
			if (pg_num_rows($result) == 0) {
				$code = $letter;
				$equal = false;
			}
		}
		
		pg_close($conn);
		return $code;
	}
	
	function getSupplierList() {
		$conn = connectDB();
		$string = "";
		
		$sql = "SELECT DISTINCT nama FROM tk_basdat.supplier ORDER BY nama ASC";
		$result = pg_query($conn, $sql);
		if (!$result) {
			die("Error in SQL query: " . pg_last_error());
		}   
		
		while ($row = pg_fetch_assoc($result)) {
			$string = $string . "<li><a href='#' data-value='".$row['nama']."'>".$row['nama']."</a></li>";
		}
		
		pg_close($conn);
		return $string;
	}
	
	function getBahanBaku() {
		$conn = connectDB();
		$string = "";
		
		$sql = "SELECT DISTINCT nama FROM tk_basdat.bahan_baku ORDER BY nama ASC";
		$result = pg_query($conn, $sql);
		if (!$result) {
			die("Error in SQL query: " . pg_last_error());
		}   
		
		$string = $string . "<select class='form-control' name='bahan-baku[]'>";
		while($row = pg_fetch_assoc($result)) { 
			$string = $string."<option value='".$row['nama']."'>".$row['nama']."</option>";
		}
		$string = $string . "</select>";
		return $string;
	}
	
	function getSatuan() {
		$conn = connectDB();
		$string = "";
		
		$sql = "SELECT DISTINCT satuanawal FROM tk_basdat.konversi ORDER BY satuanawal ASC";
		$result = pg_query($conn, $sql);
		if (!$result) {
			die("Error in SQL query: " . pg_last_error());
		}   
		
		$string = $string . "<select class='form-control' name='satuan[]'>";
		while($row = pg_fetch_assoc($result)) { 
			$string = $string."<option value='".$row['satuanawal']."'>".$row['satuanawal']."</option>";
		}
		$string = $string . "</select>";
		return $string;
	}
	
	$code = notaRandomizer();
	$supplier = getSupplierList();
	$satuan = getSatuan();
	$bahan_baku = getBahanBaku();
	
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
	<?php include "../navbar.php" ?>
	<div id='content'>
	
	<div class='container content-container'>
	<form action='../logic/process-beli.php' method='post'>
	<div>
		<h1>Beli Bahan</h1>
		
		<div class="form-group">
			<label for="usr">Nomor Nota:</label>
			<input type="text" class="form-control nota-form" id="usr" value="<?php echo $code;?>" name='nomor-nota' readonly>
		</div>
		<strong>Supplier:</strong>
		<div class="dropdown">
		
		<div class="input-group col-sm-3">                                            
            <input type="text" id="datebox" class="form-control nota-form" name='supplier' required></input>
            <div class="input-group-btn">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span></button>
                <ul class="dropdown-menu change" aria-labelledby="dropdownMenu1">
					<?php echo $supplier?>
				</ul>
            </div>
        </div>
		
			
		</div>
		<br/>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Nama Bahan<br></th>
					<th>Harga Satuan<br></th>
					<th>Satuan</th>
					<th>Jumlah</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody class='beli-bahan-body'>
			  
			</tbody>
			<tfoot>
				<td></td>
				<td></td>
				<td></td>
				<td>GRAND TOTAL :</td>
				<td class='grand-total'><input type='text' placeholder="0" class='grand-total-input form-control' name='grand-total' readonly></td></td>
		</table>
	</div>
	<div class="buttons-container">
		<button type="button" class="btn btn-primary buttons add-bahan"><span class="glyphicon glyphicon-plus"></span> Tambah Bahan</button>
		<button type="submit" class="btn btn-success buttons"><span class="glyphicon glyphicon-saved"></span> Simpan</button>
	</div>
	</form>
	</div>
	
	</div>
</body>

<script>
	var bahan_baku = "<?php echo $bahan_baku; ?>";
	var satuan = "<?php echo $satuan; ?>";
	var index = 0;
	
	

	$(document).ready(function() {
		appendTable();
		
		$('.add-bahan').click(function() {
			appendTable();
		});
	});
	
	function appendTable() {
		var string = '<tr><td class="bahan-baku bb' + index + '">' + bahan_baku + "</td><td class='harga-satuan form hs" + index + "'> <input type='text' placeholder='0' name='harga-satuan[]' required class='harga-satuan-input form-control hsi" + index + "'></td><td class='satuan s" + index + "'>" + satuan + "</td><td class='jumlah j" + index + "'><input type='text' name='jumlah[]' required placeholder='0' class='jumlah-input form-control ji" + index + "'></td><td class='total t" + index + "'><input type='text' placeholder='0' name='total[]' class='total-input form-control ti" + index + "' readonly></td>";
		$('.beli-bahan-body').append(string);
		index = index + 1;
	}
	
	$(document).on('change','.harga-satuan-input',function(){
		countAll();
	});
	
	$(document).on('change','.jumlah-input',function(){
		countAll();
	});
	
	function countAll() {
		var accumulate = 0;
		var temp = 0;
		for (var i = 0; i < index; i++) {
			temp = parseInt($('.hsi'+i).val() * $('.ji'+i).val());
			accumulate = accumulate + temp;
			$('.ti'+i).val(parseInt(temp));
			console.log();
			console.log($('.ti'+i).val());
		}
		console.log(accumulate);
		$('.grand-total-input').val(accumulate);
	}
</script>
</html>