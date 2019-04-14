<?php
class Sell{
 
    // database connection and table name
    private $conn;
    private $table_name = "app_sell";
 
    // object properties
	public $user_id;
    public $id;
    public $email;
	public $title;
    public $description;
	public $price;
	public $size;
	public $country_id;
	public $sub_country_id;
	public $color;
	public $weight;
    
	
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // signup user
     
	    function deleteSell(){
	 
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
                       where product_id =:id and app_table='SELL' ";
					    $stmt2 = $this->conn->prepare($query);
						$stmt2->bindParam(":id", $this->id);
						$stmt2->execute();
						
			// delete app_comment record if hv
			$query = "delete from
                   app_comment
                
                       where content_id =:id and user_id=:user_id and table_name='app_sell' ";
					    $stmt3 = $this->conn->prepare($query);
						$stmt3->bindParam(":id", $this->id);
						$stmt3->bindParam(":user_id", $this->user_id);
						$stmt3->execute();	
									
			// delete img files
			$this->deleteDir('/vhost/sosopet/sosopet/images/app_img/SELL/'.$this->id.'/');
			$this->deleteDir('/vhost/sosopet/sosopet/images/app_img/SELL/thumb/'.$this->id.'/');
			
	
            return true;
        }

        return false;
		
	}
	
	function updateSell($user_id,$post_id){
		$this->user_id=$user_id;
		$this->id=$post_id;
		$query = "update
                    " . $this->table_name . "
                SET
                      email=:email,title=:title,description=:description,modified_date=now(),price=:price,size=:size,
						color=:color,weight=:weight,country_id=:country_id,sub_country_id=:sub_country_id where id =:id ";
					    $stmt = $this->conn->prepare($query);
						$stmt->bindParam(":id", $this->id);
						$stmt->bindParam(":price", $this->price);
						$stmt->bindParam(":size", $this->size);
						$stmt->bindParam(":color", $this->color);
						$stmt->bindParam(":weight", $this->weight);
						$stmt->bindParam(":country_id", $this->country_id);
						$stmt->bindParam(":sub_country_id", $this->sub_country_id);
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
    function createSell($user_id){
		$this->user_id=$user_id;
		  
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_name . "
                SET
					title=:title,email=:email,description=:description,modified_date=now(),
					user_id=:user_id, 
					price=:price,
					size=:size,
					country_id=:country_id,
					sub_country_id=:sub_country_id,
					color=:color,
					weight=:weight ";
					   $stmt = $this->conn->prepare($query);
   
						 
						$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":email", $this->email);
						$stmt->bindParam(":title", $this->title);
						$stmt->bindParam(":description", $this->description);
						$stmt->bindParam(":price", $this->price);
						$stmt->bindParam(":country_id", $this->country_id);
						$stmt->bindParam(":sub_country_id", $this->sub_country_id);
						$stmt->bindParam(":color", $this->color);
						$stmt->bindParam(":weight", $this->weight);
						$stmt->bindParam(":size", $this->size);
		 
 
	//	echo $query;
 
		
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
          // getUserSells
    function getUserSells($limit,$offset){
        // select all query
        $query = "SELECT
                    a.* ,
					b.filename as image,
					(select count(*) from app_like b where b.content_id=a.id and b.table_name='app_sell') as likecnt,
					(select count(*) from app_like b where b.content_id=a.id and b.user_id=a.user_id and b.table_name='app_sell') as ownlike,
					(select count(*) from app_comment b where b.content_id=a.id and b.table_name='app_sell') as commentcnt
                FROM
                    " . $this->table_name . " a ,app_image b
                WHERE
					   user_id ='".$this->user_id."'";
					
		if ($this->id!=""){
				$query.=" and a.id=".$this->id;
		}
		$query.="
					   and a.id= b.product_id 
					   and b.app_table='SELL'
					   order by a.id desc
					   limit ".$limit." offset ".$offset ;
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }  
	
	function getAllSells($limit,$offset){
        // select all query
        $query = "SELECT
                    a.* ,
					b.filename as image,
					(select count(*) from app_like b where b.content_id=a.id and b.table_name='app_sell') as likecnt,
					(select count(*) from app_like b where b.content_id=a.id and b.user_id=a.user_id and b.table_name='app_sell') as ownlike,
					(select count(*) from app_comment b where b.content_id=a.id and b.table_name='app_sell') as commentcnt
                FROM
                    " . $this->table_name . " a ,app_image b
					where 
					  
					     a.id= b.product_id 
						 and b.app_table='SELL'
					order by a.id desc
					limit ".$limit." offset ".$offset ;
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