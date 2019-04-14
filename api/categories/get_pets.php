<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/pet.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

$limit=0;
$offset=0;

// prepare user object
$pet = new Pet($db);
// set ID property of user to be edited
 
isset($_REQUEST['pet_status'])? $pet->pet_status =$_REQUEST['pet_status']:$pet->pet_status=0; 
isset($_REQUEST['sub_country_id_array'])? $sub_country_id_array =$_REQUEST['sub_country_id_array']:$sub_country_id_array=0; 
isset($_REQUEST['limit'])? $limit =$_REQUEST['limit']:$limit=10; 
isset($_REQUEST['offset'])? $offset =$_REQUEST['offset']:$offset=0; 
isset($_REQUEST['user_id'])? $pet->user_id =$_REQUEST['user_id']:$pet->user_id=0; 
isset($_REQUEST['keyword'])? $keyword =$_REQUEST['keyword']:$keyword=''; 

//echo $pet->username ;
$stmt='';
if ($pet->user_id==0){
	
	 if ($pet->pet_status!="")
		$stmt = $pet->getPetsByStatus($limit,$offset);	
	else if ($sub_country_id_array!="")
		$stmt = $pet->getPetsByCountry($limit,$offset,$sub_country_id_array,0);	
	else if ($keyword!="")
		$stmt = $pet->getPetsBySearch($limit,$offset,$keyword);	
	
}else{
	
	if ($sub_country_id_array==""){
		//userid!=0 and subcountry ==""
	$stmt = $pet->getPetsByStatus($limit,$offset);
	}else{
	//with user_id
	$stmt=$pet->getPetsCatByUserID();
	$i=0;
	 while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		 $i=$row['petcat']+$i;
	 }
	//echo "count=".$i;
		if ($i==1 || $i==2){
			 
			$stmt = $pet->getPetsByCountry($limit,$offset,$sub_country_id_array,$i);	
		}else{
			$stmt = $pet->getPetsByCountry($limit,$offset,$sub_country_id_array,3);	
		}
	}
}
	
  
if($stmt!='' && $stmt->rowCount() > 0){
    // get retrieved row
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		foreach($row as $key=>$value ) {
			if ($value==null)
				$row[$key]="";
		}	
		
			if (!file_exists ("/vhost/sosopet/sosopet/images/product/".$row['image'])){
				$row['image']="./assets/images/profile/200x200suarez.png";
			}else{
				$aa=$row['image'];
				$row['image']="http://whospets.com/images/product/thumb/".$aa;
				$row['image_large']="http://whospets.com/images/product/".$aa;
			}
			
		$petArr[]=$row;
	}
	
	
	 
    // create array
    $user_arr=array(
        "status" => "true",
        "message" => "Successfully Get Pets By Pet Status!",
		"records" => "".$stmt->rowCount()."",
        "pets" => $petArr
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($user_arr)."}";   
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Pet Status!",
    );
	  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
