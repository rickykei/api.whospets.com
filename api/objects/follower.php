<?php
class Follower{
 
    // database connection and table name
    private $conn;
    private $table_n = "app_follower";
 
    // object properties
	public $user_id;
    public $id;
	public $follower_user_id;
    public $created_date;
    
    
	
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // signup user
     
	
	 
		 // Create Post
    function subscribe(){
		 
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_n . "
                SET
					follower_user_id=:follower_user_id,created_date=now(), user_id=:user_id";
					   $stmt = $this->conn->prepare($query);
    					$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":follower_user_id", $this->follower_user_id);
			 
		//echo $query;
  
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
	
	  
    
         // getUserPosts
    function getFollowing(){
        // select all query
        $query = "SELECT
                  a.id ,a.username,b.user_id,b.follower_user_id , if(b.user_id=".$this->user_id.",'Y','N') as followed, profile.fb_id
                FROM
                    app_follower b right join  user a on a.id=b.follower_user_id, profile
                WHERE
					   	 a.id=profile.user_id and b.user_id='".$this->user_id."' ";
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }  
	      // getUserPosts
    function getFollowers(){
        // select all query
        $query = "SELECT
                    a.id ,a.username,b.user_id,b.follower_user_id ,
					if ((select id from app_follower c where c.follower_user_id= b.user_id and c.user_id=".$this->user_id." )>8,'Y','N') as followed,
					c.fb_id
                FROM
                    user a , app_follower b  , profile c
                WHERE
					   a.id=b.user_id and
					   a.id=c.user_id and
					   b.follower_user_id='".$this->user_id."'
 
					   ";
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }  
	
	function unSubscribe(){
	 
        // query to insert record
			$query = "delete from 
                    " . $this->table_n . "
                where 
					follower_user_id=:follower_user_id and  user_id=:user_id";
					   $stmt = $this->conn->prepare($query);
    					$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":follower_user_id", $this->follower_user_id);
					 
		//echo $query;
  
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
}