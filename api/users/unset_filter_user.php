<?php
  ini_set('error_reporting', E_ALL);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/filter_user.php';
 

//get post json
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
	
    //throw new Exception('Content type must be: application/json');
}
$content = trim(file_get_contents("php://input"));
$decoded = json_decode($content, true);
if(!is_array($decoded)){
    throw new Exception('Received content contained invalid JSON!');
}
  
// get database connection
	$database = new Database();
	$db = $database->getConnection();

	
// prepare user object
	 
	$user = new User($db);
	$push = new Filter_user($db);
	
	
	
// set ID property of user to be edited
	$user->id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
    //$user->user_id= $_REQUEST['user_id'];
	$push->user_id = isset($decoded['user_id']) ? $decoded['user_id'] :'';
	$push->block_user_id= isset($decoded['block_user_id']) ? $decoded['block_user_id'] :'';
 
	if($user->id==''||$push->block_user_id==''){
		$stmt=false;
	}else{
	
		$stmt = $push->unsetFilterUser();
	 
 	}
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Unset Filter User!"
      
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
}else{
    $user_arr=array(
        "status" => false,
        "message" => "Unset Filter User Error!",
    );
	  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
