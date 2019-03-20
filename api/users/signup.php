<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json'); 
// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/user.php';
 
 
 
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

 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// set user property values
$user->username = $decoded['username'];
$user->password = base64_encode($decoded['password']);
$user->logintype = $decoded['logintype'];
$user->firstname = $decoded['firstname'];
$user->lastname = $decoded['lastname'];
$user->created = time();
 
// create the user
if($user->logintype!='fb'){
	 if($user->signup()){
		$user_arr=array(
        "status" => true,
        "message" => "Successfully Signup!",
        "id" => $user->id,
        "username" => $user->username
		);
		$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
	 }else{
		 	 $result = "{\"success\":\"false\"}";
	 }
}else if($user->logintype=='fb'){
	 if($user->signup()){
	    $user_arr=array(
        "status" => true,
        "message" => "Successfully Signup!",
        "id" => $user->id,
        "username" => $user->username
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
	 }else{
		 	 $result = "{\"success\":\"false\"}";
	 }
}else{
    $user_arr=array(
        "status" => false,
        "message" => "Username already exists!"
    );
	  $result = "{\"success\":\"false\"}";
}
echo($result);
?>
