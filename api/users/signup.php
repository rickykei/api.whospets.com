<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json'); 
// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// set user property values
$user->username = $_REQUEST['username'];
$user->password = base64_encode($_REQUEST['password']);
$user->logintype = $_REQUEST['logintype'];
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
