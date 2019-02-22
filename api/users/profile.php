<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';
include_once '../objects/follower.php';

 
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$profile = new Profile($db);
$user = new User($db);
$follower = new Follower($db);

// set ID property of user to be edited
$user->username = isset($_REQUEST['username']) ? $_REQUEST['username'] : die();
$user->fb_uid = isset($_REQUEST['fb_uid']) ? $_REQUEST['fb_uid'] : "";

// read the details of user to be edited
//echo $user->username;

$stmt = $profile->getProfileByUsername($user->username);	


if($stmt->rowCount() > 0){
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // create array
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Login!",
        "id" => $row['id'],
        "tc" => $row['tc'],
		"user_id" => $row['user_id'],
		"lastname" => $row['lastname'],
		"firstname" => $row['firstname'],
		"email" => $row['email'],
		"street" => $row['street'],
		"city" => $row['city'],
		"about" => $row['about'],
		"newsletter" => $row['newsletter'],
		"seller" => $row['seller'],
		"notification" => $row['notification'],
		"gender" => $row['gender'],
		"birthday" => $row['birthday'],
		"bio" => $row['bio'],
		"country_id" => $row['country_id'],
		"sub_country_id" => $row['sub_country_id'],
		"fb_uid" => "http://graph.facebook.com/".$user->fb_uid."/picture?type=normal" 
		//"fb_uid"=>"http://bmautohk.com/images/logo.jpg"
		 
    );
	
	$follower->user_id=$row['user_id'];
	$stmt2 = $follower->getFollowing();	 
	$stmt3 = $follower->getFollowers();	

	foreach($user_arr as $key=>$value ) {
		if ($value==null)
			$user_arr[$key]="";
    }
	
	//
	if($stmt2->rowCount() > 0){
		 
		while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
		 
			$row2['image']="./assets/images/profile/200x200wade.png";
			$followingArr[]=$row2;
		}
	}
	if($stmt3->rowCount() > 0){
		 
		while($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)){
		 $row3['image']="./assets/images/profile/200x200wade.png";
			$followersArr[]=$row3;
		}
	}
	$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr).", \"following\":". json_encode($followingArr).", \"followers\":". json_encode($followersArr)."}";   
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Invalid Username",
    );
	  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
