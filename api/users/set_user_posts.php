<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';
include_once '../objects/post.php';
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

	
// prepare user object
	$post = new Post($db);
	$user = new User($db);
	
// set ID property of user to be edited
	$user->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
    //$user->user_id= $_REQUEST['user_id'];
	$post->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
	$post->email= $decoded['email'];
	$post->description= $decoded['description'];
    $post->title= $decoded['title'];
	isset($decoded['owner_pet_id'])? $post->owner_pet_id =$decoded['owner_pet_id']:$owner_pet_id=""; 
	isset($decoded['avatar'])? $avatar =$decoded['avatar']:$avatar=""; 
	$img=new Appimage($db,$avatar);
	$img->avatar=$avatar;

	
	 
	if($user->user_id==''||$post->email==''||$post->description==''|| $post->title=='' || $post->owner_pet_id==''){
		$stmt=false;
	}else{
		$stmt = $post->createPost($user->user_id);
		//upload image 20190106
		if ($img->avatar!=''){
			
			$img->product_id = $post->id;
			$img->app_table = "LIFESTYLE";
			$img->is_default='N';
			$stmt=$img->addImage();
		}
		//
	}
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Created Post!"
      
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
