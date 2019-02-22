<?php
class Lifestyle{
 
    // database connection and table name
    private $conn;
    private $table_name = "app_post";
 
    // object properties
	public $user_id;
    public $id;
    public $email;
	public $title;
    public $description;
	public $owner_pet_id;
    
	
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    function deleteLifestyle(){
	 
		// delete lifestyle record
		$query = "delete from
                    " . $this->table_name . "
                       where id =:id and user_id=:user_id ";
					    $stmt = $this->conn->prepare($query);
						$stmt->bindParam(":id", $this->id);
						//$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":user_id", $this->user_id);
						
		// execute query
        if($stmt->execute()){		 
           // $this->id = $this->conn->lastInsertId();
			
			// delete app_img record if hv
			$query = "delete from
                    app_image
                       where product_id =:id and app_table='LIFESTYLE' ";
					    $stmt2 = $this->conn->prepare($query);
						$stmt2->bindParam(":id", $this->id);
						$stmt2->execute();
						
			// delete app_comment record if hv
			$query = "delete from
                   app_comment
                
                       where content_id =:id and user_id=:user_id and table_name='app_post' ";
					    $stmt3 = $this->conn->prepare($query);
						$stmt3->bindParam(":id", $this->id);
						$stmt3->bindParam(":user_id", $this->user_id);
						$stmt3->execute();	
									
			// delete img files
			$this->deleteDir('/vhost/sosopet/sosopet/images/app_img/LIFESTYLE/'.$this->id.'/');
			$this->deleteDir('/vhost/sosopet/sosopet/images/app_img/LIFESTYLE/thumb/'.$this->id.'/');
			
	
            return true;
        }

        return false;
		
	}
	
	function updatePost($user_id,$post_id){
		$this->user_id=$user_id;
		$this->id=$post_id;
		$query = "update
                    " . $this->table_name . "
                SET
                      owner_pet_id=:owner_pet_id,email=:email,title=:title,description=:description,modified_date=now() where id =:id ";
					    $stmt = $this->conn->prepare($query);
						$stmt->bindParam(":id", $this->id);
						//$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":owner_pet_id", $this->owner_pet_id);
						$stmt->bindParam(":email", $this->email);
						$stmt->bindParam(":title", $this->title);
						$stmt->bindParam(":description", $this->description);
		// execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
		
	}

		 // Create Post
    function createPost($user_id){
		$this->user_id=$user_id;
		  
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_name . "
                SET
					owner_pet_id=:owner_pet_id,title=:title,email=:email,description=:description,modified_date=now(), user_id=:user_id";
					   $stmt = $this->conn->prepare($query);
   
						 $stmt->bindParam(":owner_pet_id", $this->owner_pet_id);
						$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":email", $this->email);
						$stmt->bindParam(":title", $this->title);
						$stmt->bindParam(":description", $this->description);
		 
 
		//echo $query;
 
		
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
    
         // getUserPosts
    function getUserPosts($limit,$offset){
        // select all query
        $query = "SELECT
                     a.* ,
					b.filename as image,
					(select count(*) from app_like b where b.content_id=a.id and b.table_name='app_post') as likecnt,
					(select count(*) from app_like b where b.content_id=a.id and b.user_id=a.user_id and b.table_name='app_post') as ownlike,
					(select count(*) from app_comment b where b.content_id=a.id and b.table_name='app_post') as commentcnt
                FROM
                    app_post a ,app_image b
                WHERE
					 
					   a.id= b.product_id and ";
					   
					 if ($this->id>0)
					$query.=" a.id ='".$this->id."' and ";			
					$query.=" user_id ='".$this->user_id."' 
					and b.app_table='LIFESTYLE'
						order by a.id desc
						limit ".$limit." offset ".$offset;
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }  
	
	       // getAllPosts
    function getAllPosts($limit,$offset){
        // select all query
        $query = "SELECT
                    a.* ,
					b.filename as image,
					(select count(*) from app_like b where b.content_id=a.id and b.table_name='app_post') as likecnt,
					(select count(*) from app_like b where b.content_id=a.id and b.user_id=a.user_id and b.table_name='app_post') as ownlike,
					(select count(*) from app_comment b where b.content_id=a.id and b.table_name='app_post') as commentcnt
                FROM
                    app_post a ,app_image b
					where 
                a.id= b.product_id 
				and b.app_table='LIFESTYLE'
				order by a.id desc
				limit ".$limit." offset ".$offset;
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }  
	
	public static function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
	}
}