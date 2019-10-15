<?php
class Qna{
 
    // database connection and table name
    private $conn;
    private $table_name = "app_qna";
 
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
    // signup user
     
	function deleteQna(){
	 
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
                       where product_id =:id and app_table='QNA' ";
					    $stmt2 = $this->conn->prepare($query);
						$stmt2->bindParam(":id", $this->id);
						$stmt2->execute();
						
			// delete app_comment record if hv
			$query = "delete from
                   app_comment
                
                       where content_id =:id and user_id=:user_id and table_name='app_qna' ";
					    $stmt3 = $this->conn->prepare($query);
						$stmt3->bindParam(":id", $this->id);
						$stmt3->bindParam(":user_id", $this->user_id);
						$stmt3->execute();	
									
			// delete img files
			$this->deleteDir('/vhost/sosopet/sosopet/images/app_img/QNA/'.$this->id.'/');
			$this->deleteDir('/vhost/sosopet/sosopet/images/app_img/QNA/thumb/'.$this->id.'/');
			
	
            return true;
        }

        return false;
		
	}
	
	function updateQna($user_id,$post_id,$img){
		$this->user_id=$user_id;
		$this->id=$post_id;
		$query = "update
                    " . $this->table_name . "
                SET
                     owner_pet_id=:owner_pet_id, email=:email,title=:title,description=:description,modified_date=now() where id =:id ";
					    $stmt = $this->conn->prepare($query);
						
						$stmt->bindParam(":id", $this->id);
						//$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":email", $this->email);
						$stmt->bindParam(":title", $this->title);
						$stmt->bindParam(":description", $this->description);
						$stmt->bindParam(":owner_pet_id", $this->owner_pet_id);
		// execute query
            if($stmt->execute()){		 
		// delete app_img record if hv
		if ($img!=''){
			$query = "update 
                    app_image set is_default='N' 
                       where product_id =:id and app_table='QNA' ";
					    $stmt2 = $this->conn->prepare($query);
						$stmt2->bindParam(":id", $this->id);
						$stmt2->execute();
		}
           // $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
		
	}

		 // Create Post
    function createQna($user_id){
		$this->user_id=$user_id;
		  
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_name . "
                SET
					owner_pet_id=:owner_pet_id,title=:title,email=:email,description=:description,modified_date=now(), user_id=:user_id";
					   $stmt = $this->conn->prepare($query);
   
						 
						$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":email", $this->email);
						$stmt->bindParam(":title", $this->title);
						$stmt->bindParam(":description", $this->description);
						$stmt->bindParam(":owner_pet_id", $this->owner_pet_id);
 
		//echo $query;
 
		
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
    function getContent(){
        // select all query
        $query = "SELECT
                    a.* ,
					(select title from shop_products b where product_id = a.owner_pet_id)  as product_title,
					b.filename as image,
					(select count(*) from app_like b where b.content_id=a.id and b.table_name='app_qna') as likecnt,
					(select count(*) from app_like b where b.content_id=a.id and b.user_id=a.user_id and b.table_name='app_qna') as ownlike,
					(select count(*) from app_comment b where b.content_id=a.id and b.table_name='app_qna') as commentcnt
                FROM
                    " . $this->table_name . " a ,app_image b
                WHERE
					     b.app_table='QNA'
					   
					   ";
					
		if ($this->id!=""){
				$query.=" and a.id=".$this->id;
		}
		$query.="
					   and a.id= b.product_id and
					   b.is_default='Y'
					   order by a.id desc"
					  ;
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    } 
         // getUserPosts
    function getUserQnas($limit,$offset){
        // select all query
        $query = "SELECT
                    a.* ,
					(select title from shop_products b where product_id = a.owner_pet_id)  as product_title,
					b.filename as image,
					(select count(*) from app_like b where b.content_id=a.id and b.table_name='app_qna') as likecnt,
					(select count(*) from app_like b where b.content_id=a.id and b.user_id=a.user_id and b.table_name='app_qna') as ownlike,
					(select count(*) from app_comment b where b.content_id=a.id and b.table_name='app_qna') as commentcnt
                FROM
                    " . $this->table_name . " a ,app_image b
                WHERE
					   user_id ='".$this->user_id."'
					   and b.app_table='QNA'
					   
					   ";
					
		if ($this->id!=""){
				$query.=" and a.id=".$this->id;
		}
		$query.="
					   and a.id= b.product_id 
					   order by a.id desc
					   limit ".$limit." offset ".$offset ;
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    } 

		     // getUserPosts
    function getAllQnas($limit,$offset){
		
		 $sql_str="";
		 
		 $sql_str=$this->getBlockUserListByUserID($this->user_id);
		 
        // select all query
        $query = "SELECT
                     a.* ,
					 (select title from shop_products b where product_id = a.owner_pet_id)  as product_title,
					b.filename as image,
					(select count(*) from app_like b where b.content_id=a.id and b.table_name='app_qna') as likecnt,
					(select count(*) from app_like b where b.content_id=a.id and b.user_id=a.user_id and b.table_name='app_qna') as ownlike,
					(select count(*) from app_comment b where b.content_id=a.id and b.table_name='app_qna') as commentcnt ,
					c.fb_id as fb_id,
					c.firstname,
					c.lastname
					
                FROM
                    " . $this->table_name . " a,app_image b,profile c
					where 
					  c.user_id=a.user_id 
					  and b.is_default='Y' 
					  and status=1 
					  and a.id= b.product_id 
					  and b.app_table='QNA'
					  ".$sql_str."
					  order by a.id desc
					  limit ".$limit." offset ".$offset ;
        // prepare query statement
		// echo $query;
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
	
	
	function getBlockUserListByUserID($user_id){
		
		$query = "select a.block_user_id 
				from
                    app_filter_user a 
                where a.user_id=:user_id";
				 
				// echo $query;
				$stmt = $this->conn->prepare($query);
                $stmt->bindParam(":user_id", $user_id);
            
			// echo $query;
        // execute query
        if($stmt->execute()){	
			if($stmt->rowCount() > 0){
			// get retrieved row
				$i=0;
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					 $block_user_id[$i]=$row['block_user_id'];
					 $i++;
				}
				$str = implode (", ", $block_user_id);
				
				if($this->user_id!="" || $this->user_id!=0){
			 
					$str=" and c.user_id not in (".$str.") ";
			 
				} 
				
				return $str;
			}else{
				return "";
			}
		}   
		return "";
	} 
}