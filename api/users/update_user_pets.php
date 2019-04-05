<?php
ini_set('error_reporting', E_ALL);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/pet.php';
include_once '../objects/user.php';
include_once '../objects/profile.php';
include_once '../objects/image.php';
 
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
$avatar ="";
// prepare user object

$user = new User($db);
$pet = new Pet($db);

// set ID property of user to be edited

isset($decoded['username'])? $user->username=$decoded['username'] :$user->username=""; 

//set pet class
isset($decoded['category_id'])? $pet->category_id =$decoded['category_id']:$pet->category_id=""; 
isset($decoded['status'])? $pet->status =$decoded['status']:$pet->status="";  
isset($decoded['store_id'])?$pet->store_id =$decoded['store_id']:$pet->store_id="";  
isset($decoded['tax_id'])? $pet->tax_id =$decoded['tax_id']:$pet->tax_id=""; 
isset($decoded['title'])? $pet->title =$decoded['title']:$pet->title="";  
isset($decoded['description'])? $pet->description =$decoded['description']:$pet->description=""; 
isset($decoded['descriptionDisplay'])? $pet->descriptionDisplay =$decoded['descriptionDisplay']:$pet->descriptionDisplay=""; 
isset($decoded['keywords'])? $pet->keywords =$decoded['keywords']:$pet->keywords=""; 
isset($decoded['price'])? $pet->price =$decoded['price']:$pet->price=""; 
isset($decoded['language'])? $pet->language =$decoded['language']:$pet->language=""; 
isset($decoded['specifications'])? $pet->specifications =$decoded['specifications']:$pet->specifications=""; 
isset($decoded['style_code'])? $pet->style_code =$decoded['style_code']:$pet->style_code=""; 
isset($decoded['color'])? $pet->color =$decoded['color']:$pet->color=""; 
isset($decoded['condition'])? $pet->condition =$decoded['condition']:$pet->condition=""; 
isset($decoded['size'])? $pet->size =$decoded['size']:$pet->size=""; 
isset($decoded['quantity'])? $pet->quantity =$decoded['quantity']:$user->quantity=""; 
isset($decoded['view'])? $pet->view =$decoded['view']:$pet->view=""; 
isset($decoded['created'])? $pet->created =$decoded['created']:$pet->created=""; 
isset($decoded['feature_date'])? $pet->feature_date =$decoded['feature_date']:$pet->feature_date=""; 
isset($decoded['gallery_date'])? $pet->gallery_date =$decoded['gallery_date']:$pet->gallery_date=""; 
isset($decoded['banner_a'])? $pet->banner_a =$decoded['banner_a']:$pet->banner_a=""; 
isset($decoded['banner_b'])? $pet->banner_b =$decoded['banner_b']:$pet->banner_b=""; 
isset($decoded['banner_c'])? $pet->banner_c =$decoded['banner_c']:$pet->banner_c=""; 
isset($decoded['todays_deal'])? $pet->todays_deal =$decoded['todays_deal']:$pet->todays_deal=""; 
isset($decoded['discount'])? $pet->discount =$decoded['discount']:$pet->discount=""; 
isset($decoded['date_lost'])? $pet->date_lost =$decoded['date_lost']:$pet->date_lost=""; 
isset($decoded['date_born'])? $pet->date_born =$decoded['date_born']:$pet->date_born=""; 
isset($decoded['sub_category'])? $pet->sub_category =$decoded['sub_category']:$pet->sub_category=""; 
isset($decoded['weight'])? $pet->weight =$decoded['weight']:$pet->weight=""; 
isset($decoded['height'])? $pet->height =$decoded['height']:$pet->height=""; 
isset($decoded['name_of_pet'])? $pet->name_of_pet =$decoded['name_of_pet']:$pet->name_of_pet=""; 
isset($decoded['country'])? $pet->country =$decoded['country']:$pet->country=""; 
isset($decoded['contact'])? $pet->contact =$decoded['contact']:$pet->contact=""; 
isset($decoded['pet_status'])? $pet->pet_status =$decoded['pet_status']:$pet->pet_status=""; 
isset($decoded['count_down_end_date'])? $pet->count_down_end_date =$decoded['count_down_end_date']:$pet->count_down_end_date=""; 
isset($decoded['last_seen_appearance'])? $pet->last_seen_appearance =$decoded['last_seen_appearance']:$pet->last_seen_appearance=""; 
isset($decoded['questions'])? $pet->questions =$decoded['questions']:$pet->questions=""; 
isset($decoded['pet_id'])? $pet->pet_id =$decoded['pet_id']:$pet->pet_id=""; 
isset($decoded['gender'])? $pet->gender =$decoded['gender']:$pet->gender=""; 
isset($decoded['country_id'])? $pet->country_id =$decoded['country_id']:$pet->country_id=""; 
isset($decoded['sub_country_id'])? $pet->sub_country_id =$decoded['sub_country_id']:$pet->sub_country_id=""; 
isset($decoded['avatar'])? $avatar =$decoded['avatar']:$avatar=""; 
isset($decoded['product_id'])? $pet->product_id =$decoded['product_id']:$product_id=""; 
$img=new Image($db,$avatar);
$img->avatar=$avatar;


//echo $user->username;
if ($pet->product_id!=''){
	
  
		if($pet->updatePet()){ 
			//upload image 20190106
			if ($img->avatar!=''){
				
				$img->product_id = $pet->product_id;
				$img->is_default='Y';
				$stmt=$img->addImage();
			}
			//
			 $user_arr=array(
			"status" => true,
			"message" => "Successfully update pet!",
			"product_id" => $pet->product_id
			);
			$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
		}else{
			$user_arr=array(
				"status" => false,
				"message" => "update pet fail",
			);
			  $result = "{\"success\":\"false\"}";
		}
 
}else{
	$user_arr=array(
			"status" => false,
			"message" => "update pet fail",
		);
		  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
