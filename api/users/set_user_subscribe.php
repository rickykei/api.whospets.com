<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/follower.php';

  
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
	$follower = new Follower($db);
	$user = new User($db);
	
// set ID property of user to be edited
	$user->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
    
	$follower->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
	$follower->follower_user_id = isset($decoded['subscribe_user_id']) ? $decoded['subscribe_user_id'] : die();
    
	 
	if($user->user_id=='' || $follower->follower_user_id==''){
		$stmt=false;
	}else{
		$stmt = $follower->subscribe();
	}
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully subscribe!"
      
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
}else{
    $user_arr=array(
        "status" => false,
        "message" => "Invalid UserID",
    );
	  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
