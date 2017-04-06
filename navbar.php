<?php
	$username = $_SESSION['user_id'];
	$role = $_SESSION["role"];
	$roleString = "";
	$roleFunctions = "";
	if ($role == "ST") {
		$roleString = "STAFF";
		$roleFunctions = $roleFunctions . "<li><a href='pembelian.php' id='nav-pembelian'><span class='glyphicon glyphicon-bitcoin'></span> Pembelian</a></li>";
		$roleFunctions = $roleFunctions . "<li><a href='beli.php' id='nav-beli'><span class='glyphicon glyphicon-shopping-cart'></span> Beli Bahan</a></li>";
	} elseif ($role == "CH") {
		$roleString = "CHEF";
		$roleFunctions = $roleFunctions . "<li><a href='menu.php' id='nav-menu'><span class='glyphicon glyphicon-list-alt'></span> Menu</a></li>";
	} elseif ($role == "KS") {
		$roleString = "KASIR";
		$roleFunctions = $roleFunctions . "<li><a href='menu.php' id='nav-menu'><span class='glyphicon glyphicon-list-alt'></span> Menu</a></li>";
		$roleFunctions = $roleFunctions . "<li><a href='pemesanan.php' id='nav-pemesanan'><span class='glyphicon glyphicon glyphicon-check'></span> Pemesanan</a></li>";
	}
	$roleIdentity = "<li><a href=#><span class='glyphicon glyphicon-user'></span> $username</a></li> <li><a href=#>Logged in as: $roleString</a></li>";
?>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Foodie</a>
    </div>
    <ul class="nav navbar-nav">
      <?php echo $roleFunctions; ?>
    </ul>
	<ul class="nav navbar-nav navbar-right">
      <?php echo $roleIdentity; ?>
	  <li><a href="../logout.php"><span class='glyphicon glyphicon-log-out'></span> Logout</a></li>
    </ul>
  </div>
</nav>