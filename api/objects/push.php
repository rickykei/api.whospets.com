<?php
class Push{
 
    // database connection and table name
    private $conn;
    private $table_name = "app_push_record";
 
    // object properties
    public $id;
    public $device_id;
    public $push_title;
	public $push_content;
    public $push_app_table;
    public $push_content_id;   
    public $sent;   
    public $modified_datetime;   
    public $approved;
	public $type;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	function pushNotificationCompleted($id){
		$query = "update  app_push_record set sent ='C' where id=$id and sent is null ";
		$stmt = $this->conn->prepare($query);
		if($stmt->execute()){
			 return $stmt;
		}
	}
    // signup user
    function getPushRecords(){
		 $query = "select * from app_push_record where sent is null and approved ='1' ";
		 	$stmt = $this->conn->prepare($query);
		
		    if($stmt->execute()){		 
            $i=0;
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$return_row[$i]=$row;
					$i++;
				}
			} 
		  
		  return $return_row;
	}
		  
    // Create Push
    function createPush(){
		  
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_name . "
                SET
                push_title=:push_title,push_content=:push_content,push_app_table=:push_app_table,push_content_id=:push_content_id,approved=:approved,type=:type,modified_datetime=now(),created_date=now(), device_id=:device_id";
					   $stmt = $this->conn->prepare($query);
                       $stmt->bindParam(":device_id", $this->device_id);
                       $stmt->bindParam(":push_title", $this->push_title);
                       $stmt->bindParam(":push_content", $this->push_content);
                       $stmt->bindParam(":push_app_table", $this->push_app_table);
						$stmt->bindParam(":push_content_id", $this->push_content_id);
						$stmt->bindParam(":approved", $this->approved);	 
						$stmt->bindParam(":type", $this->type);	 
 
		//echo $query;
 
		
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
    
    
}