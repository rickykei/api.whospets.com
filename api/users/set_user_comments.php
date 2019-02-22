<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/comment.php';
 
 
 

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
	$post = new Comment($db);
	$user = new User($db);
	
// set ID property of user to be edited
	$user->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
    //$user->user_id= $_REQUEST['user_id'];
	$post->user_id = isset($decoded['user_id']) ? $decoded['user_id'] : die();
	$post->comment= $decoded['comment'];
	$post->content_id= $decoded['content_id'];
    $post->table_name=$decoded['table_name'];
	 
	if($user->user_id==''||$post->comment==''||$post->content_id==''){
		$stmt=false;
	}else{
		$stmt = $post->createComment($user->user_id,$post->content_id, $post->table_name,$post->comment);
 	}
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Created Comment!"
      
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
