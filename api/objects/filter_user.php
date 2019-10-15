<?php
class Filter_user{
 
    // database connection and table name
    private $conn;
    private $table_name = "app_filter_user";
 
    // object properties
    public $id;
    public $user_id;
    public $block_user_id;
	 
 

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	 
    // Create Push
    function createFilterUser(){
		  
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_name . "
                SET
                user_id=:user_id,
				block_user_id=:block_user_id,
				modified_datetime=now(),
				created_date=now()";
					   $stmt = $this->conn->prepare($query);
                       $stmt->bindParam(":user_id", $this->user_id);
                       $stmt->bindParam(":block_user_id", $this->block_user_id);
                        
			 
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
	
    function getFilterUsers(){
		  
        // query to insert record
			$query = "select a.block_user_id,b.firstname,b.lastname ,c.username,b.fb_id 
				from
                    " . $this->table_name ." a , profile b, user c
                where
				c.id=b.user_id and 
				a.block_user_id=b.user_id and 
                a.user_id=:user_id";
				 
				// echo $query;
				$stmt = $this->conn->prepare($query);
                 $stmt->bindParam(":user_id", $this->user_id);
            
			 
        // execute query
        if($stmt->execute()){		 
            
            return $stmt;
        }

        return false;
        
    }
	 function unsetFilterUser(){
		  
        // query to insert record
			$query = "delete from 
                    " . $this->table_name . "
                where
                user_id=:user_id and block_user_id=:block_user_id
				
				";
					   $stmt = $this->conn->prepare($query);
                       $stmt->bindParam(":user_id", $this->user_id);
                       $stmt->bindParam(":block_user_id", $this->block_user_id);
                        
			 
        // execute query
        if($stmt->execute()){		 
            
            return true;
        }

        return false;
        
    }
	
    
}