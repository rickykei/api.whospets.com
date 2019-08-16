<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/sell.php';
include_once '../objects/appimage.php';
 include_once '../objects/push.php';
 include_once '../objects/country.php';
include_once '../objects/profile.php';


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
	$sell = new Sell($db);
	$user = new User($db);
	$push = new Push($db);
	$country= new Country($db);
	$profile = new Profile($db);
// set ID property of user to be edited

	
	 	 
    $user->user_id= $decoded['user_id'];
	$sell->user_id = $decoded['user_id'];
	$sell->email= $decoded['email'];
	$sell->description= $decoded['description'];
    $sell->title= $decoded['title'];
	$sell->price= $decoded['price'];
	$sell->size= $decoded['size'];
	$sell->color= $decoded['color'];
	$sell->weight= $decoded['weight'];
	$sell->country_id= $decoded['country_id'];
	$sell->sub_country_id= $decoded['sub_country_id'];
	isset($decoded['avatar'])? $avatar =$decoded['avatar']:$avatar="";
	 	$img=new Appimage($db,$avatar);
	$img->avatar=$avatar;



	$stmt = $sell->createSell($user->user_id);
	//upload image 20190106
		if ($img->avatar!=''){
			
			$img->product_id = $sell->id;
			$img->app_table = "SELL";
			$img->is_default='Y';
			$stmt=$img->addImage();
		}
	//if sell post created , add push notification
	if ($stmt!=""){
		$country->getDistrictNameById($sell->sub_country_id);
		$dids=$profile->getDeviceIdByCountryId($sell->sub_country_id);
		$push->device_id = implode(",",$dids);
			$push->push_title = $sell->title.", ".$sell->description." is available from ".$country->title;   
			$push->push_content = $sell->title.", ".$sell->description." is available from ".$country->title;  
			$push->push_app_table = "app_sell"; 
			$push->push_content_id = $sell->id;  
			$push->approved ="1";
			$push->type =4;
			$stmt2=$push->createPush();
	}
	
if($stmt){
    
    $user_arr=array(
        "status" => 'true',
        "message" => "Successfully Created sell!"
      
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
