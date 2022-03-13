<?php 
	$my_Db_Connection = NULL;
	/*DB Credentials*/
	$database = 'Romeaverse';
	$username = 'donzo';
	$password = '5!@q%48KQb5qN';
	$servername = 'localhost';
	$sql = "mysql:host=$servername;dbname=$database;";
		
	$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
	
	try { 
  		$my_Db_Connection = new PDO($sql, $username, $password, $dsn_Options);
  		//echo "Connected successfully";
	} 
	catch (PDOException $error) {
  		echo 'Connection error: ' . $error->getMessage();
	}
