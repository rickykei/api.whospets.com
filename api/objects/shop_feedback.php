<?php
class Shop_feedback{
 
    // database connection and table name
    private $conn;
    private $table_n = "shop_feedback";
 
    // object properties
	public $user_id;
	public $username;
    public $id; //table id
	public $store_id;
    public $order_id;
	public $product_id;
	public $feedback;
	public $comment;
	public $created_date;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
 
	// Create Post
    function createComment($user_id,$product_id, $store_id,$comment){
		$this->user_id=$user_id;
		  
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_n . "
                SET
					comment=:comment,store_id=:store_id,product_id=:product_id, user_id=:user_id";
					   $stmt = $this->conn->prepare($query);
    					$stmt->bindParam(":user_id", $user_id);
						$stmt->bindParam(":product_id", $product_id);
						$stmt->bindParam(":store_id", $store_id);
						$stmt->bindParam(":comment", $comment);
		// echo $query;
		
		
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
    
    function getCommentCount(){
        // select all query
        $query = "SELECT
                    count(*) as count
                FROM
                    " . $this->table_n . " 
                WHERE
					   content_id='".$this->content_id."'
					   and table_name='".$this->table_name."'
					   ";
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }  
	
	  function getPetComments($limit,$offset){
        // select all query
        $query = "SELECT
                    a.id , a.user_id,a.store_id,a.order_id, a.product_id,a.feedback,a.comment, a.create_date as created_date ,b.username ,c.firstname,c.lastname 
                FROM
                    " . $this->table_n . " a , user b, profile c
                WHERE
					   a.product_id='".$this->product_id."' 
					     and a.user_id = b.id 
						 and b.id = c.user_id 
					   order by a.id desc
					   ";
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    } 
	
	function deleteComment($user_id,$content_id,$table_name){
		$this->user_id=$user_id;
		  
        // query to insert record
			$query = "delete from 
                    " . $this->table_n . "
                where 
					content_id=:content_id and table_name=:table_name and user_id=:user_id";
					   $stmt = $this->conn->prepare($query);
    					$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":content_id", $this->content_id);
						$stmt->bindParam(":table_name", $this->table_name);
 
		//echo $query;
 
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
}