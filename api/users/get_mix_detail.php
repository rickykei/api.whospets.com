<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/pet.php';
include_once '../objects/sell.php';
include_once '../objects/lifestyle.php';
include_once '../objects/qna.php';
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object

$user = new User($db);
 

// set ID property of user to be edited
$app_table =$_REQUEST['app_table']; 

if ($app_table=='app_post'){
	$post = new Lifestyle($db);	
	$img_str="LIFESTYLE";
}else if ($app_table=='app_qna'){
	$post = new Qna($db);
	$img_str="QNA";
} else if ($app_table=='app_sell'){	
	$post = new Sell($db);
	$img_str="SELL";
}else{
	$post = new Pet($db);
	$img_str='';
}

if($img_str!='')	
$post->id= $_REQUEST['content_id'];
else
$post->product_id= $_REQUEST['content_id'];
  
$stmt = $post->getContent();	
  
//print_r($stmt);
  
if($stmt->rowCount() > 0){
    // get retrieved row
     
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	
//	print_r($row);
		foreach($row as $key=>$value ) {
			if ($value==null)
				$row[$key]="";
		}
		
		if($img_str!=''){	
		if (!file_exists ("/home/vhost/sosopet/sosopet/images/app_img/".$img_str."/".$row['image'])){
				$row['image']="./assets/images/profile/200x200suarez.png";
		}else{
				$aa=$row['image'];
				$row['image']="http://whospets.com/images/app_img/".$img_str."/thumb/".$aa;
				$row['image_large']="http://whospets.com/images/app_img/".$img_str."/".$aa;
		}}else{
			if (!file_exists ("/home/vhost/sosopet/sosopet/images/product/".$row['image'])){
				$row['image']="./assets/images/profile/200x200suarez.png";
			}else{
				$aa=$row['image'];
				$row['image']="http://whospets.com/images/product/thumb/".$aa;
				$row['image_large']="http://whospets.com/images/product/".$aa;
		}}
		$postArr[]=$row;
	}
	
	// get Post Like Count
	
	
    // create array
    $user_arr=array(
        "status" => "true",
        "message" => "Successfully Get Mix Detail!",
		"records" => "".$stmt->rowCount()."",
        "posts" => $postArr
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($postArr)."}";   
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
