<?php
   ini_set('error_reporting', E_ALL);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';
include_once '../objects/qna.php';
include_once '../objects/appimage.php';
include_once '../objects/push.php';
include_once '../objects/pet.php';


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
	$post = new Qna($db);
	$user = new User($db);
	$push = new Push($db);	
	$pet = new Pet($db);
	
	
// set ID property of user to be edited
	$user->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
    //$user->user_id= $decoded['user_id'];
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
		$stmt = $post->createQna($user->user_id);
		
		//upload image 20190106
		if ($img->avatar!=''){
			
			$img->product_id = $post->id;
			$img->app_table = "QNA";
			$img->is_default='Y';
			$stmt2=$img->addImage();
		}
		//add push
		
		if ($stmt){
			//find cat or dog
			$pet->findPetBreedByPetId($post->owner_pet_id);
			//echo $pet->category_id;
			$dids=$pet->getDeviceIdsByPetBreed($pet->category_id);
			$push->device_id = implode(",",$dids);
			$push->push_title = "Please help! ".$post->title.", ".$post->description." ";   
			$push->push_content = "Please help! ".$post->title.", ".$post->description." ";   
			$push->push_app_table = "app_qna"; 
			$push->push_content_id = $post->id;  
			$push->approved ="1";
			$push->type =6;
			$stmt2=$push->createPush();
			
		}
	}
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Created Qna!"
      
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
