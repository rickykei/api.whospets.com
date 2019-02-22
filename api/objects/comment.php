<?php
class Comment{
 
    // database connection and table name
    private $conn;
    private $table_n = "app_comment";
 
    // object properties
	public $user_id;
	public $username;
    public $id; //table id
	public $table_name;
    public $content_id;
	public $feedback;
	public $comment;
	public $created_date;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
 
	// Create Post
    function createComment($user_id,$content_id,$table_name,$comment){
		$this->user_id=$user_id;
		  
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_n . "
                SET
					comment=:comment,content_id=:content_id,table_name=:table_name,created_date=now(), user_id=:user_id";
					   $stmt = $this->conn->prepare($query);
    					$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":content_id", $this->content_id);
						$stmt->bindParam(":table_name", $this->table_name);
						$stmt->bindParam(":comment", $this->comment);
		//echo $query;
		
		
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
	
	  function getUserComments($limit,$offset){
        // select all query
        $query = "SELECT
                    a.*,b.username
                FROM
                    " . $this->table_n . " a , user b
                WHERE
					   content_id='".$this->content_id."' 
					   and a.user_id = b.id 
					   and a.table_name='".$this->table_name."' 
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