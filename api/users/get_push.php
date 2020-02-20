<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/push.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$push = new push($db);
// set ID property of user to be edited
$push->user_id =$_REQUEST['user_id']; 
$push->id=$_REQUEST['content_id'];

// isset($_REQUEST['limit'])? $limit =$_REQUEST['limit']:$limit=10; 
// isset($_REQUEST['offset'])? $offset =$_REQUEST['offset']:$offset=0; 

//echo $push->username ;
 
$stmt = $push->getUserpushs($limit,$offset);	
  
if($stmt->rowCount() > 0){
    // get retrieved row
     
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	
		foreach($row as $key=>$value ) {
			if ($value==null)
				$row[$key]="";
		}
		
		if (!file_exists ("/vhost/sosopet/sosopet/images/product/".$row['image'])){
				$row['image']="./assets/images/profile/200x200suarez.png";
			}else{
				$row['image']="http://whospets.com/images/product/thumb/".$row['image'];
			}
			
		$pushArr[]=$row;
	}
	
	// get push Like Count
	
	
    // create array
    $user_arr=array(
        "status" => "true",
        "message" => "Successfully Get pushs!",
		"records" => "".$stmt->rowCount()."",
        "pushs" => $pushArr
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($pushArr)."}";   
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
