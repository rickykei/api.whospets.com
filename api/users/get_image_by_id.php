<?php
 ini_set('error_reporting', E_ALL);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
 
include_once '../objects/image.php';

 	
 
// get database connection
	$database = new Database();
	$db = $database->getConnection();

	
// prepare user object
	$image = new Image($db,'sdfsdf');

	
// set ID property of user to be edited
	$image->id = $_REQUEST['imageid'];

   
	$stmt=$image->getImgById($image->id);	
	
	 
	
if($stmt){
    
    $user_arr=array(
        "email" => 'true',
		"fullname" => 'true',
        "avatar" => "http://www.whospets.com/images/product/thumb/".$image->filename
      
    );
	$result = json_encode($user_arr);   
}else{
    $user_arr=array(
        "status" => false,
        "message" => "Invalid Product Image",
    );
	  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
