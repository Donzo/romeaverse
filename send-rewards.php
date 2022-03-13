<?php
	
	$userAccount = NULL;
	
	
	//https://romeaverse.com/code/php/send-rewards.php?which=leather&howMany=10&uAN=0x44f751ead3D88b04a57C298789FCC26632e8179b
	if (isset($_GET['uAN']) && isset($_GET['which']) && isset($_GET['howMany'])) {
		$userAccount = $_GET['uAN'];
		$whichCom = $_GET['which'];
		$howManyCom =  $_GET['howMany'];
	}
	else{
		die( "Hes ded!");
	}
		
	//Connect to DB
	require_once($_SERVER['DOCUMENT_ROOT'] . '/code/php/mysql-connect.php');
	
	//Add amount to db total
	$sqlSt = "UPDATE romans SET  " . $whichCom . " = " . $whichCom . " + ". $howManyCom . " WHERE accountNumber = '" . $_GET['uAN'] . "'";
	$my_Insert_Statement = $my_Db_Connection->prepare($sqlSt);


	if ($my_Insert_Statement->execute()) {
		echo "Records updated sucessfully";
	}
	else{
		echo "error";
	}
	
	$my_Db_Connection  = NULL;

?>