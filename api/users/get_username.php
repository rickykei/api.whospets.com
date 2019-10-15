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
$requester_user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : die();
$user->username = isset($_REQUEST['username']) ? $_REQUEST['username'] : die();

$user->id=$requester_user_id;
// read the details of user to be edited
	$stmt = $user->searchUser();	

if(count($stmt)>0){
    // get retrieved row
    for($i=0;$i<count($stmt);$i++){
	 	if ($stmt[$i]['fb_id']=="0")
		 $stmt[$i]['image']="./assets/images/profile/200x200jordan.png";
		else
			$stmt[$i]['image']="http://graph.facebook.com/".$stmt[$i]['fb_id']."/picture?type=normal";
		 
	 
	// $row['image']="./assets/images/profile/200x200jordan.png";
		 
			 
	}
	$result = "{\"success\":\"true\", \"data\":". json_encode( $stmt)."}";   
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
