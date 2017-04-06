<?php
	session_start();
	if (isset($_SESSION["role"])) {
		
		$roleList = array("beli.php", "menu.php","pemesanan.php", "pembelian.php");
		$role = $_SESSION["role"];
		$activities = array();
		if ($role == "ST") {
			$activities[0] = $roleList[3];
			$activities[1] = $roleList[0];
		} elseif ($role == "CH") {
			$activities[0] = $roleList[1];
		} elseif ($role == "KS") {
			$activities[0] = $roleList[1];
			$activities[1] = $roleList[2];
		}
		$activities[0] = "dashboard/".$activities[0];
		header("Location: ".$activities[0]);
	} else {
		header("Location: index.php");
	}
	
?>