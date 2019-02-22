<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/like.php';

 
// get database connection
	$database = new Database();
	$db = $database->getConnection();

	
// prepare user object
	$like = new Like($db);

	$like->table_name= $_REQUEST['table_name'];
	$like->content_id= $_REQUEST['content_id'];
   
	 
	
$stmt = $like->getLikeCount();	
  
if($stmt->rowCount() > 0){
    // get retrieved row
     
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	
		foreach($row as $key=>$value ) {
			if ($value==null)
				$row[$key]="";
		}
		$postArr[]=$row;
	}
    // create array
    $user_arr=array(
        "status" => "true",
        "message" => "Successfully Get Like Count!",
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