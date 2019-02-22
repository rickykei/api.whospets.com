<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';
include_once '../objects/image.php';

 
// get database connection
	$database = new Database();
	$db = $database->getConnection();

	
// prepare user object
	$profile = new Profile($db);
	$user = new User($db);
	
	
// set ID property of user to be edited
	$user->username = $_REQUEST['username'];

    $profile->user_id = $_REQUEST['user_id'];
    
    
	
	// read the details of user to be edited
	
	$user->getUserIdByUsername($user->username);	
	
	if ($_FILES['image_field']!=''){
		$img=new Image($db,$_FILES);
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
	$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
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
