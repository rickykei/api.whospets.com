<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/pet.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();


// prepare user object
$pet = new Pet($db);

// set ID property of user to be edited
$pet->username =$_REQUEST['username']; 
$pet->user_id=$_REQUEST['user_id']; 
isset($_REQUEST['product_id'])? $pet->product_id=$_REQUEST['product_id']:$pet->product_id=""; 
//echo $pet->username ;
 
 if ($pet->product_id!=""){
 $stmt = $pet->getUserPetsByProductId();		 }
	 else
$stmt = $pet->getUserPets();	
  
if($stmt->rowCount() > 0){
    // get retrieved row
     
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	
		foreach($row as $key=>$value ) {
			if ($value==null)
				$row[$key]="";
		}
		
			if (!file_exists ("/home/vhost/sosopet/sosopet/images/product/".$row['image'])){
				$row['image']="./assets/images/profile/200x200suarez.png";
			}else{
				$aa=$row['image'];
				$row['image']="https://whospets.com/images/product/thumb/".$aa;
				$row['image_large']="https://whospets.com/images/product/".$aa;
			}
		
		$petArr[]=$row;
	}
	
	
	//check image exist

	
    // create array
    $user_arr=array(
        "status" => "true",
        "message" => "Successfully Get Pets!",
		"records" => "".$stmt->rowCount()."",
        "pets" => $petArr
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($petArr)."}";   
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
