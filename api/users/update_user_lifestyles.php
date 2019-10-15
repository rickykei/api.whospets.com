<?php
ini_set('error_reporting', E_ALL);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/lifestyle.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';
include_once '../objects/appimage.php';
 
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
$post = new Lifestyle($db);

// set ID property of user to be edited

isset($decoded['username'])? $user->username=$decoded['username'] :$user->username=""; 

//set lifestyle class
$post->id = isset($decoded['id']) ? $decoded['id'] : die();
$user->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
$post->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
isset($decoded['email'])? $post->email =$decoded['email']:$email=""; 
isset($decoded['description'])? $post->description =$decoded['description']:$description=""; 
isset($decoded['title'])? $post->title =$decoded['title']:$title=""; 
isset($decoded['owner_pet_id'])? $post->owner_pet_id =$decoded['owner_pet_id']:$owner_pet_id=""; 
isset($decoded['avatar'])? $avatar =$decoded['avatar']:$avatar=""; 
$img=new Appimage($db,$avatar);
$img->avatar=$avatar;
 


	if($user->user_id==''|| $post->id==''){
		$stmt=false;
	}else{
		$stmt = $post->updateLifestyle($user->user_id,$post->id,$img->avatar);
		//updae image 20190406
		if ($img->avatar!=''){
			
			$img->product_id = $post->id;
			$img->app_table = "LIFESTYLE";
			$img->is_default='Y';
			$stmt=$img->addImage();
		}
		//
	}
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully update livestyle!"
      
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
