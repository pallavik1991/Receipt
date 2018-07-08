<?php

class Receipt{
	//database connection and table name

private $conn;
private $table1_name="receipt";
private $table2_name="receiptdetails";

//object properties

public $student_usn;
public $paiddate;
public $totalamountpaid;
public $receiptnumber;
public $fees_slno;
public $fees_head;
public $amountpaid;

public function __construct($db){
	$this->conn=$db;
}


//autogeneration of receiptnumber
function autogen(){
	$query="select count(receiptnumber) as c, max(receiptnumber) as m from ".$this->table1_name;
	$stmt=$this->conn->prepare($query);
	$stmt->execute();

	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	$this->countrows=$row['c'];
	if($this->countrows==0)
		$this->receiptnumber=1;
	else{
		$this->receiptnumber=$row['m'];
		$this->receiptnumber++;
	}
}


//create receipt
function create_receipt(){
	//write query

	try{
	$this->autogen();
	$query="INSERT INTO ".$this->table1_name. "(student_usn,paiddate,totalamountpaid,receiptnumber,fees_slno) values(?,?,?,?,?)";
	$stmt=$this->conn->prepare($query);

	//bind values
	$stmt->bindParam(1,$this->student_usn);
	$stmt->bindParam(2,$this->paiddate);
	$stmt->bindParam(3,$this->totalamountpaid);
	$stmt->bindParam(4,$this->receiptnumber);
	$stmt->bindParam(5,$this->fees_slno);

 	if($stmt->execute()){
		return "success";
	}
	else{
		return "fail";
	}
}
catch(Exception $ex){
	return $ex.errorMessage();
}
}

//create receiptdetails
function create_receiptdetails(){
	//write query

	try{
	$query="INSERT INTO ".$this->table2_name. "(receiptnumber,fees_head,amountpaid) values(?,?,?)";
	$stmt=$this->conn->prepare($query);

	//bind values
	$stmt->bindParam(1,$this->receiptnumber);
	$stmt->bindParam(2,$this->fees_head);
	$stmt->bindParam(3,$this->amountpaid);
	
 	if($stmt->execute()){
		return "success";
	}
	else{
		return "fail";
	}
}
catch(Exception $ex){
	return $ex.errorMessage();
}
}


//select all data from receipt
function readAll_receipt(){
	$query="SELECT * FROM ". $this->table1_name;
	
	$stmt=$this->conn->query($query);
	$output=array();
	$output=$stmt->fetchall(PDO::FETCH_ASSOC);
	return $output;
}

//select all data from receiptdetails
function readAll_receiptdetails(){
	$query="SELECT * FROM ". $this->table2_name;
	
	$stmt=$this->conn->query($query);
	$output=array();
	$output=$stmt->fetchall(PDO::FETCH_ASSOC);
	return $output;
}

//select all data from receipt and receiptdetails
function join_tables_display(){
	$query=" select receipt.receiptnumber,receipt.paiddate,receipt.totalamountpaid,receiptdetails.fees_head,receiptdetails.amountpaid from receipt,receiptdetails where receipt.receiptnumber=receiptdetails.receiptnumber;";
	
	$stmt=$this->conn->query($query);
	$output=array();
	$output=$stmt->fetchall(PDO::FETCH_ASSOC);
	return $output;
}

//select all data from receiptdetails based on receiptnumber
function readAll_receiptdetails_onreceiptnumber(){
	$query="SELECT * FROM ". $this->table2_name." WHERE receiptnumber=?";
	
		$stmt=$this->conn->prepare($query);
		$stmt->bindParam(1,$this->receiptnumber);

		$stmt->execute();

		$output=array();
		$output=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $output;
}

}
?>