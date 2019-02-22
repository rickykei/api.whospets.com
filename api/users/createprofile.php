<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';

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
	$user->username = $decoded['username'];

    $profile->tc =  $decoded['tc'];
	$profile->user_id = $decoded['user_id'];
	$profile->lastname= $decoded['lastname'];
	$profile->firstname =$decoded['firstname'];
    $profile->email= $decoded['email'];
    $profile->street=  $decoded['street'];
	$profile->city=  $decoded['city'];
	$profile->about= $decoded['about'];
	$profile->newsletter=  $decoded['newsletter'];
	$profile->seller=  $decoded['seller'];
	$profile->notification= $decoded['notification'];
	$profile->gender=  $decoded['gender'];
	$profile->birthday=  $decoded['birthday'];
	$profile->bio=  $decoded['bio'];
	$profile->country_id= $decoded['country_id'];
	$profile->sub_country_id= $decoded['sub_country_id'];
	
	// read the details of user to be edited
	
	$user->getUserIdByUsername($user->username);	
	
	//echo $user->id;

	$stmt = $profile->createProfile($user->id);
	
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Created/Updated Profile!"
      
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
}else{
    $user_arr=array(
        "status" => false,
        "message" => "Invalid Username",
    );
	  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
