<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';
include_once '../objects/like.php';

 
// get database connection
	$database = new Database();
	$db = $database->getConnection();

	
// prepare user object
	$like = new Like($db);
	$user = new User($db);
	// echo "A";
// set ID property of user to be edited
	$user->user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : die();
    $like->user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : die();
	$like->table_name= $_REQUEST['table_name'];
	$like->content_id= $_REQUEST['content_id'];
   
	 
	if($user->user_id==''||$like->content_id==''||$like->table_name==''){
		$stmt=false;
	}else{
		$stmt = $like->deleteLike($user->user_id,$like->content_id,$like->table_name);
	}
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully deleted Like!"
      
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
