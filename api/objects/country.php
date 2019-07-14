<?php
class Country{
	
	private $conn;
    private $table_n = "shop_country";
 
    // object properties
	public $country_id;
	public $parent_id;
    public $title; //table id
	public $title_zh;
    public $description;
	public $language;
	
	 public function __construct($db){
        $this->conn = $db;
    }
 
	 function getDistrictNameById($id){
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_n . " 
                WHERE
					   country_id='".$id."'
					  
					   ";
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        
		 if($stmt->execute()){

           if($stmt->rowCount() > 0){

			 

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				 

			$this->country_id=$row['country_id'];
			$this->parent_id=$row['parent_id'];
            $this->title=$row['title'];
            $this->title_zh=$row['title_zh'];

			}

		   }

		    

            return $this->country_id;

        }
        
    }  
}
?>