<?php


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/mix.php';
include_once '../objects/user.php';
include_once '../objects/follower.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$post = new Mix($db);
$user = new User($db);
$follower = new Follower($db);
 

// set ID property of user to be edited
$post->user_id =$_REQUEST['user_id']; 
$user->id=$_REQUEST['user_id']; 
 
isset($_REQUEST['limit'])? $limit =$_REQUEST['limit']:$limit=10; 
isset($_REQUEST['offset'])? $offset =$_REQUEST['offset']:$offset=0; 
 
$user->getStoreIdByUserId();
$follower->user_id =$post->user_id; 

$stmt = $follower->getFollowing();	

$i=0;
if($stmt->rowCount() > 0){
	 while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		 
			 $following_user_id[$i]=$row['follower_user_id'];
			 $i++;
		 
	}
}
$following_user_id_str=implode(",", $following_user_id);
//echo "followingid".$following_user_id_str."<P>";
$stmt = $post->getUserFollowingMixMix($limit,$offset,$following_user_id_str);	
  

  
if($stmt->rowCount() > 0){
    // get retrieved row
     
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	
	 
		 if(substr($row['app_table'],0,3)=='sho'){
			if (!file_exists ("/home/vhost/sosopet/sosopet/images/product/".$row['image'])){
				$row['image']="./assets/images/profile/200x200suarez.png";
			}else{
				$row['image']="http://whospets.com/images/product/thumb/".$row['image'];
			}
		}else {
			if (!file_exists ("/home/vhost/sosopet/sosopet/images/app_img/".$row['app_table']."/".$row['image'])){
					$row['image']="./assets/images/profile/200x200suarez.png";
				}else{
					$row['image']="http://whospets.com/images/app_img/".$row['app_table']."/thumb/".$row['image'];
			}
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
        "message" => "Successfully Get following Mix Mix Post!",
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
