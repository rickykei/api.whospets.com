<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';
include_once '../objects/image.php';

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
	$profile = new Profile($db);
	$user = new User($db);
	
	
// set ID property of user to be edited
	$user->username = $_REQUEST['username'];

    $profile->user_id = $_REQUEST['user_id'];
    $_REQUEST['product_id']='99999';
    
	
	// read the details of user to be edited
	
	$user->getUserIdByUsername($user->username);	
	
	if ($decoded['avatar']!=''){
		$img=new Image($db,$decoded['avatar']);
		$img->product_id = $_REQUEST['product_id'];
		$img->is_default=$_REQUEST['is_default'];
		$stmt=$img->addImage();
	}
	//echo $user->id;
 
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Uploaded Product Image!"
      
    );
	$result = json_encode($img);   
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
