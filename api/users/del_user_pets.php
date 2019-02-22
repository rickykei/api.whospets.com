<?php
ini_set('error_reporting', E_ALL);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/pet.php';
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
$avatar ="";
// prepare user object

$user = new User($db);
$pet = new Pet($db);

$user->id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
    //$user->user_id= $_REQUEST['user_id'];
$pet->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();

isset($decoded['pet_id'])? $pet->product_id =$decoded['pet_id']:$pet->product_id=""; 
 
//echo $user->username;
$user->getStoreIdByUserId();
 
$pet->store_id=$user->store_id;
//echo "storeID".$pet->store_id;
 //echo "petID".$pet->id;
 
if( $pet->store_id !="" && $pet->store_id >0 && $pet->product_id!=""){

	if($pet->deletePet()){ 
		$user_arr=array(
        "status" => true,
        "message" => "Successfully del pet!" 
		);
		$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
	}else{
		$user_arr=array(
			"status" => false,
			"message" => "Del pet fail",
		);
		  $result = "{\"success\":\"false\"}";
	}
}
// make it json format
echo($result);
?>
