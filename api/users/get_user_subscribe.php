<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/follower.php';

// get database connection
$database = new Database();
$db = $database->getConnection();


// prepare user object
$follower = new Follower($db);
// set ID property of user to be edited
$follower->user_id =$_REQUEST['user_id']; 


isset($_REQUEST['limit'])? $limit =$_REQUEST['limit']:$limit=10; 
isset($_REQUEST['offset'])? $offset =$_REQUEST['offset']:$offset=0; 


 
$stmt = $follower->getFollowing();	
  
if($stmt->rowCount() > 0){
    // get retrieved row
     
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			if ($row['fb_id']=="0")
		 $row['image']="./assets/images/profile/200x200jordan.png";
		else
			$row['image']="http://graph.facebook.com/".$row['fb_id']."/picture?type=normal";
		 
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
