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

if($stmt->rowCount() > 0){
    // get retrieved row
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	 	if ($row['fb_id']=="0")
		 $row['image']="./assets/images/profile/200x200jordan.png";
		else
			$row['image']="http://graph.facebook.com/".$row['fb_id']."/picture?type=normal";
		 
	 
	// $row['image']="./assets/images/profile/200x200jordan.png";
		$arr[]=$row;
		 
			 
	}
	$result = "{\"success\":\"true\", \"data\":". json_encode( $arr)."}";   
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
