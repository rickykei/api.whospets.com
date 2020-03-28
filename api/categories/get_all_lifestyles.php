<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/lifestyle.php';
include_once '../objects/like.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$post = new Lifestyle($db);
// set ID property of user to be edited
$post->user_id =$_REQUEST['user_id']; 
isset($_REQUEST['limit'])? $limit =$_REQUEST['limit']:$limit=10; 
isset($_REQUEST['offset'])? $offset =$_REQUEST['offset']:$offset=0; 


//echo $post->username ;
 
$stmt = $post->getAllPosts($limit,$offset);	
  
if($stmt->rowCount() > 0){
    // get retrieved row
     
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	
		foreach($row as $key=>$value ) {
			if ($value==null)
				$row[$key]="";
		}
		
		if (!file_exists ("/home/vhost/sosopet/sosopet/images/app_img/LIFESTYLE/".$row['image'])){
				$row['image']="./assets/images/profile/200x200suarez.png";
			}else{
				$aa=$row['image'];
				$row['image']="http://whospets.com/images/app_img/LIFESTYLE/thumb/".$aa;
				$row['image_large']="http://whospets.com/images/app_img/LIFESTYLE/".$aa;
			}
			
		//prepare fb image
			if ($row['fb_id']=="0")
			 $row['postuserimage']="./assets/images/profile/200x200jordan.png";
			else
				$row['postuserimage']="https://graph.facebook.com/".$row['fb_id']."/picture?type=normal";
			
			$row['postusername']=$row['firstname']." ".$row['lastname'];

			
		$postArr[]=$row;
	}
	
	// get Post Like Count
	
	
    // create array
    $user_arr=array(
        "status" => "true",
        "message" => "Successfully Get posts!",
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
