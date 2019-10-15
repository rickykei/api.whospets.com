<?php
 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/filter_user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$filter_user = new Filter_user($db);
// set ID property of user to be edited
$filter_user->user_id =$_REQUEST['user_id']; 

isset($_REQUEST['limit'])? $limit =$_REQUEST['limit']:$limit=10; 
isset($_REQUEST['offset'])? $offset =$_REQUEST['offset']:$offset=0; 

 
$stmt = $filter_user->getFilterUsers();	
 
if($stmt->rowCount() > 0){
    // get retrieved row
     
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		
		if ($row['fb_id']=="0")
		 $row['image']="./assets/images/profile/200x200jordan.png";
		else
			$row['image']="http://graph.facebook.com/".$row['fb_id']."/picture?type=normal";
		$sellArr[]=$row;
	}
    // create array
    $user_arr=array(
        "status" => "true",
        "message" => "Successfully Get Filter Users!",
		"records" => "".$stmt->rowCount()."",
        "filter_users" => $sellArr
    );
	$result = "{\"success\":\"true\", \"data\":". json_encode($sellArr)."}";   
}else{
    $user_arr=array(
        "status" => false,
        "message" => "Invalid Filter Users Request!",
    );
	  $result = "{\"success\":\"false\"}";
}
// make it json format
echo($result);
?>
