<?php 

	 
	 	include_once 'database.php';
		include_once 'receipt.php';

		$database = new Database();
		$db = $database->getConnection();

		$receipt = new Receipt($db);
		$result=$receipt->join_tables_display();

		
		echo json_encode($result);

	?>
	 