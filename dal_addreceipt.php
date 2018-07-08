<?php
include_once 'database.php';
include_once 'receipt.php';

$database = new Database();
$db = $database->getConnection();

$receipt = new Receipt($db);
$msg="";
 
    try{
    	if (empty($_POST["param_usn"])) {
            $msg = " Student USN is required ";
        }
        else
        {
             $receipt->student_usn=$_POST["param_usn"];    
        }
    	if (empty($_POST["param_paiddate"])) {
            $msg.= "<br> Paid date is required ";
        }
        else
        {
             $receipt->paiddate=$_POST["param_paiddate"];
        }
    	
		if (empty($_POST["param_amountpaid"])) {
            $msg.= "<br> Amount paid is required ";
        }
        else
        {
             $receipt->amountpaid=$_POST["param_amountpaid"];
        }   	

        
    	$receipt->fees_slno=$_POST["param_fees_slno"];
        $receipt->fees_head=$_POST["param_fees_head"];
        if(empty($msg))
        {


        if($receipt->create_receipt()&&$receipt->create_receiptdetails()){

            $msg="Success";
          
        }
    // if unable to create , tell the user
    else{
         $msg= "Unable";
        }
         echo json_encode($msg);
    }
    else
    {
    	 echo json_encode($msg);
    }
    }
    catch(Exception $ex)
    {
        $msg=$ex.errorMessage();
    }
    finally{
        //echo $msg;
    }
 
?>
