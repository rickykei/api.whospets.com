<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/sell.php';

// get database connection
$database = new Database();
$db = $database->getConnection();


// prepare user object
$sell = new Sell($db);
// set ID property of user to be edited
$sell->user_id =$_REQUEST['user_id']; 
$sell->id =$_REQUEST['content_id'];

isset($_REQUEST['limit'])? $limit =$_REQUEST['limit']:$limit=10; 
isset($_REQUEST['offset'])? $offset =$_REQUEST['offset']:$offset=0; 


 
$stmt = $sell->getUserSells($limit,$offset);	
  
if($stmt->rowCount() > 0){
    // get retrieved row
     
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	
		foreach($row as $key=>$value ) {
			if ($value==null)
				$row[$key]="";
		}
		if (!file_exists ("/home/vhost/sosopet/sosopet/images/app_img/SELL/".$row['filename'])){
				$row['image']="./assets/images/profile/200x200suarez.png";
			}else{
				$aa=$row['image'];
				$row['image']="http://whospets.com/images/app_img/SELL/thumb/".$aa;
				$row['image_large']="http://whospets.com/images/app_img/SELL/".$aa;
			}
			
		$sellArr[]=$row;
	}
    // create array
    $user_arr=array(
        "status" => "true",
        "message" => "Successfully Get sells!",
		"records" => "".$stmt->rowCount()."",
        "sells" => $sellArr
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($sellArr)."}";   
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Invalid Username or Password!",
    );
	  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
