<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';

 
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);
$profile = new Profile($db);
// set ID property of user to be edited
$user->username = isset($_REQUEST['username']) ? $_REQUEST['username'] : die();
$user->password = base64_encode(isset($_REQUEST['password']) ? $_REQUEST['password'] : "");
$user->logintype = isset($_REQUEST['logintype']) ? $_REQUEST['logintype'] : die();
$user->fb_uid = isset($_REQUEST['fb_uid']) ? $_REQUEST['fb_uid'] : "";
$user->device_id = isset($_REQUEST['device_id']) ? $_REQUEST['device_id'] : "";
 
// read the details of user to be edited

if ($user->logintype=='fb'){
	$stmt = $user->fblogin();	
}else{
	$stmt = $user->login();
}


//get profile language
$stmt2=$profile->getProfileByUsername($user->username);
 $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
 
if($stmt->rowCount() > 0){
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // create array
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Login!",
        "id" => $row['id'],
        "username" => $row['username'],
		"language"=> $row2['language'],
		"firstname"=> $row2['firstname'],
		"lastname"=> $row2['lastname'],
		"image" => "https://graph.facebook.com/".$user->fb_uid."/picture?type=normal"
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Invalid Username or Password!",
    );
	 	$result = "{\"success\":\"false\", \"data\":". json_encode($user_arr)."}";   
}
// make it json format
echo($result);
?>
