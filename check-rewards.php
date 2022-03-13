<?php
	
	$userAccount = NULL;
	
	
	//https://romeaverse.com/code/php/check-rewards.php?uAN=0x44f751ead3D88b04a57C298789FCC26632e8179b
	if (isset($_GET['uAN']) && isset($_GET['which'])) {
		$userAccount = $_GET['uAN'];
		$whichCom = $_GET['which'];
	}
	else{
		die( "Hes ded!");
	}
		
	//Connect to DB
	require_once($_SERVER['DOCUMENT_ROOT'] . '/code/php/mysql-connect.php');
	
	//Add amount to db total
	//$sqlSt = "UPDATE romans SET  " . $whichCom . " = " . $whichCom . " + ". $howManyCom . " WHERE accountNumber = '" . $_GET['uAN'] . "'";
	$sqlSt = "SELECT * FROM romans WHERE accountNumber = '" . $_GET['uAN'] . "'";
	
	$my_Insert_Statement = $my_Db_Connection->prepare($sqlSt);


	if ($my_Insert_Statement->execute()) {
		while ($row = $my_Insert_Statement->fetch()) {
			$amountOfCom = $row[$whichCom];
			print $amountOfCom;
		}
	}
	
	$my_Db_Connection  = NULL;

?>