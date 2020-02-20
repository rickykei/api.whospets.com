<?php
class Filter_content{
 
    // database connection and table name
    private $conn;
    private $table_name = "app_filter_content";
 
    // object properties
    public $id;
    public $user_id;
    public $content_id;
	public $comment;
    public $app_table;
    public $approved;
 

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	 
    // Create Push
    function createFilterContent(){
		  $this->comment="";
		  echo $this->user_id;
		  echo $this->content_id;
		  echo $this->app_table;
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_name . "
                SET
                user_id=:user_id,content_id=:content_id,comment=:comment,app_table=:app_table,approved='N', modified_datetime=now(),created_date=now()";
					   $stmt = $this->conn->prepare($query);
                       $stmt->bindParam(":user_id", $this->user_id);
                       $stmt->bindParam(":content_id", $this->content_id);
                       $stmt->bindParam(":comment", $this->comment);
                       $stmt->bindParam(":app_table", $this->app_table);
					    
					
 
		// echo $query;
 
		
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
    
    
}