<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);
// set ID property of user to be edited
$user->username = isset($_REQUEST['username']) ? $_REQUEST['username'] : die();
$user->password = base64_encode(isset($_REQUEST['password']) ? $_REQUEST['password'] : "");
$user->logintype = isset($_REQUEST['logintype']) ? $_REQUEST['logintype'] : die();
$user->fb_uid = isset($_REQUEST['fb_uid']) ? $_REQUEST['fb_uid'] : "";

// read the details of user to be edited

if ($user->logintype=='fb'){
	$stmt = $user->fblogin();	
}else{
	$stmt = $user->login();
}


if($stmt->rowCount() > 0){
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // create array
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Login!",
        "id" => $row['id'],
        "username" => $row['username'],
		"image" => "http://graph.facebook.com/".$user->fb_uid."/picture?type=normal"
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Invalid Username or Password!",
    );
	  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
