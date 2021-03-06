<?php
  ini_set('error_reporting', E_ALL);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/shop_feedback.php';
include_once '../objects/push.php';
 

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
	$post = new Shop_feedback($db);
	$user = new User($db);
	$push = new Push($db);
	
	
	
// set ID property of user to be edited
	$user->id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
    //$user->user_id= $_REQUEST['user_id'];
	$post->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
	$post->comment= $decoded['comment'];
	$post->product_id= $decoded['product_id'];
	
	$user->getStoreIdByUserId();
 	
	if($user->id==''||$post->comment==''||$post->product_id==''|| $user->store_id==''){
		$stmt=false;
	}else{
	
		$stmt = $post->createComment($user->id,$post->product_id, $user->store_id,$post->comment);
		
		//find content owner device id
			$device_id_str=$post->getDeviceIdByContentId($post->product_id, "shop_products",$user->id);
				//	echo $device_id_str;
			// insert push record
			//if post creator <> post owner
			if ($device_id_str!=""){
				$push->device_id = $device_id_str;
				$push->push_title = "New comments for whospets pet";  
				$push->push_content = $post->comment;  
				$push->push_app_table = "shop_products"; 
				$push->push_content_id = $post->product_id;  
				$push->approved ="1";
				$push->type = 3;
				$stmt2=$push->createPush();
			}
 	}
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Created Pet Comment!"
      
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
