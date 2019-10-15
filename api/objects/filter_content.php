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
		  $this->comment="abc";
		  
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_name . "
                SET
                user_id=:user_id,
				content_id=:content_id,
				comment=:comment,
				table_name=:table_name,
				approved='N', 
				modified_datetime=now(),
				created_date=now()";
					   $stmt = $this->conn->prepare($query);
                       $stmt->bindParam(":user_id", $this->user_id);
                       $stmt->bindParam(":content_id", $this->content_id);
                       $stmt->bindParam(":comment", $this->comment);
                       $stmt->bindParam(":table_name", $this->app_table);
					    
					
 
		// echo $query;
 
		
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
	
    function processFilterContent(){
		  $this->comment="abc";
		  
        // search QNA
		$query = " Select content_id from app_filter_content where table_name='app_qna' and approved='Y' and sent is null";
		 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		 if($stmt->rowCount() > 0){
			$i=0;
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				 $content_id[$i]=$row['content_id'];
				 $i++;
			}
			
			//update QNA status
			$str = implode (", ", $content_id);
			
			$update_query="update app_qna set status=0 where id in (".$str.") ";
			 
			$stmt = $this->conn->prepare($update_query);
			$stmt->execute();
			
			$query = "update  app_filter_content set sent ='C' , modified_datetime=now() where table_name='app_qna' and approved='Y' and sent is null and content_id in (".$str.") ";
			
			 
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
	 
		}
		
		
		 // search sell
		$query = " Select content_id from app_filter_content where table_name='app_sell' and approved='Y' and sent is null";
		 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		 if($stmt->rowCount() > 0){
			$i=0;
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				 $content_id[$i]=$row['content_id'];
				 $i++;
			}
			
			//update QNA status
			$str = implode (", ", $content_id);
			
			$update_query="update app_sell set status=0 where id in (".$str.") ";
			 
			$stmt = $this->conn->prepare($update_query);
			$stmt->execute();
			
			$query = "update  app_filter_content set sent ='C' , modified_datetime=now() where table_name='app_sell' and approved='Y' and sent is null and content_id in (".$str.") ";
			
			 
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
	 
		}
		
		 // search sell
		$query = " Select content_id from app_filter_content where table_name='app_post' and approved='Y' and sent is null";
		 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		 if($stmt->rowCount() > 0){
			$i=0;
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				 $content_id[$i]=$row['content_id'];
				 $i++;
			}
			
			//update QNA status
			$str = implode (", ", $content_id);
			
			$update_query="update app_post set status=0 where id in (".$str.") ";
			 
			$stmt = $this->conn->prepare($update_query);
			$stmt->execute();
			
			$query = "update  app_filter_content set sent ='C' , modified_datetime=now()  where table_name='app_post' and approved='Y' and sent is null and content_id in (".$str.") ";
			
			 
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
	 
		}
       
	   
	   		 // search pet
		$query = " Select content_id from app_filter_content where table_name='shop_products' and approved='Y' and sent is null";
		 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		 if($stmt->rowCount() > 0){
			$i=0;
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				 $content_id[$i]=$row['content_id'];
				 $i++;
			}
			
			//update QNA status
			$str = implode (", ", $content_id);
			
			$update_query="update shop_products set status=0 where product_id in (".$str.") ";
			 
			$stmt = $this->conn->prepare($update_query);
			$stmt->execute();
			
			$query = "update  app_filter_content set sent ='C' , modified_datetime=now()  where table_name='shop_products' and approved='Y' and sent is null and content_id in (".$str.") ";
			
			 
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
	 
		}
        return false;
        
    }
    
}