<?php
	
	$userAccount = NULL;
	//We need the user account number for the file system so do or die
	if (isset($_POST['userAccountNum'])) {
		$userAccount = $_POST['userAccountNum'];
	}
	else{
		die("No user account sent.");
	}
	
	
	//Connect to DB
	require_once($_SERVER['DOCUMENT_ROOT'] . '/code/php/mysql-connect.php');
	
	
	$my_Insert_Statement = $my_Db_Connection->prepare("INSERT IGNORE INTO romans (accountNumber) VALUES (:accountNumber)");
	$my_Insert_Statement->bindValue(':accountNumber',$userAccount);
	/*$my_Insert_Statement->bindValue(':testName', $testTitle);
	$my_Insert_Statement->bindValue(':testNameFF', $formattedTitle);
	$my_Insert_Statement->bindValue(':pathToResources', $pathToThisTest);
	$my_Insert_Statement->bindValue(':pathToTest', $pathToUserJSON);
	$my_Insert_Statement->bindValue(':pathToImage1',$imgURL1);*/
	if ($my_Insert_Statement->execute()) {
		echo "New record created or ignored successfully";
	}
	
	
	
	$my_Db_Connection  = NULL;

?>