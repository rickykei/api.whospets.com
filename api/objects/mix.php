<?php
class Mix{
 
    // database connection and table name
    private $conn;
  
    // object properties
	public $user_id;
    public $store_id;
	
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // signup user
     
	 
         // getUserPosts
    function getUserMix($limit,$offset){
        // select all query
        $query = "
			select * from (
		
				SELECT
					c.id,
                    b.filename as image,
					b.app_table as app_table,
					b.created_date,
					c.user_id
                FROM
                   app_image b,app_post c
                WHERE
					   c.id= b.product_id
            and app_table='LIFESTYLE'     
					   and c.user_id ='".$this->user_id."' 
				union all
				SELECT
					c.id,
                    b.filename as image,
					b.app_table as app_table,
					b.created_date,
					c.user_id
                FROM
                   app_image b,app_qna c
                WHERE
					   c.id= b.product_id
              and app_table='QNA' 
					   and c.user_id ='".$this->user_id."' 
				union all
				SELECT
					c.id,
                    b.filename as image,
					b.app_table as app_table,
					b.created_date,
					c.user_id
                FROM
                   app_image b,app_sell c
                WHERE
					   c.id= b.product_id
             and app_table='SELL' 
					   and c.user_id ='".$this->user_id."'	   
			) a
			order by id desc"
					   ;
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }  
	
 
}