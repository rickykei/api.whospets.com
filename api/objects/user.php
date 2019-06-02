<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "user";
 
    // object properties
    public $id;
    public $username;
	public $email;
	public $logintype;
    public $password;
    public $created;
	public $fb_uid;
	public $device_id;
	public $firstname;
	public $lastname;
	
	public $store_id;
	public $profile_id;
	
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	function searchUser(){
		/*select a.id ,a.username,b.user_id,b.follower_user_id , if(b.user_id=514,'Y','N') as followed from app_follower b right join  user a on a.id=b.user_id 
where upper(username) like upper('%c%') 
and a.id <> 514
order by a.id*/
		 $query = "SELECT
                    a.id ,a.username,b.user_id,b.follower_user_id , profile.fb_id,if(b.user_id=".$this->id.",'Y','N') as followed
                FROM
                   app_follower b right join  user a on a.id=b.follower_user_id, profile
                WHERE
                   a.id=profile.user_id  and upper(username) like upper('%".$this->username."%') 
			and a.id <> ".$this->id."
			order by a.id;";
        // prepare query statement
		
	//	echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
	}
	
	function getUserIdByUsername($uname){
		$this->username=$uname;
		 if($this->isAlreadyExist()){
			return $this->id;
        }
		return false;
	}
	
	function getStoreIdByUsername($uname){
		$this->username=$uname;
		 if($this->isAlreadyExist()){
			return $this->store_id;
        }
		return false;
	}
	
    // signup user
    function signup(){
    
        if($this->isAlreadyExist()){
            return false;
        }
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    username=:username, password=:password, createtime=:created, status=1 ";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":created", $this->created);
    
        // Create Profile & Store for new user
        if($stmt->execute()){
			$this->id = $this->conn->lastInsertId();
				$query2= "INSERT INTO profile   SET  user_id=:user_id , email=:email,firstname=:firstname,lastname=:lastname";
				$stmt2 = $this->conn->prepare($query2);   
				$stmt2->bindParam(":user_id", $this->id);
				$stmt2->bindParam(":email", $this->username);
				$stmt2->bindParam(":firstname", $this->firstname);
				$stmt2->bindParam(":lastname", $this->lastname);
				if($stmt2->execute()){
					 
					$query3= "INSERT INTO shop_store SET  user_id=:user_id ,
					store_name='store_name',
					store_description='store_description' ,
					store_email='store_email' ,
					store_phone='store_phone' ,
					shipping_fee_us=0 ,
					additional_shipping_fee=0 ";

					$stmt3 = $this->conn->prepare($query3);   
					$stmt3->bindParam(":user_id", $this->id);
				/* 	$stmt3->bindParam(":store_name","store_name");
					$stmt3->bindParam(":store_description","store_description");
					$stmt3->bindParam(":store_email","store_email");
					$stmt3->bindParam(":store_phone","91239123"); */
					
					if($stmt3->execute()){
						return true;
					}
					
					return false;
				}
        }
    
        return false;
        
    }
	//fb login user
    function fblogin(){
        // select all query
        $query = "SELECT
                    `id`, `username`, `password`  
                FROM
                    " . $this->table_name . " 
                WHERE
                    username='".$this->username."' and activationKey ='' ";
        // prepare query statement
		
		
		
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
		
		
		
		$update_fb_id_query=" update profile set fb_id=".$this->fb_uid." , device_id='".$this->device_id."' where user_id=( select id from user where username='".$this->username."')";
		
		//echo $update_fb_id_query;
		 $stmt2 = $this->conn->prepare($update_fb_id_query);
		 $stmt2->execute();
        return $stmt;
    }
    // login user
    function login(){
        // select all query
        $query = "SELECT
                    `id`, `username`, `password` 
                FROM
                    " . $this->table_name . " 
                WHERE
                    username='".$this->username."' AND password='".$this->password."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
		
		$update_fb_id_query=" update profile set  device_id='".$this->device_id."' where user_id=( select id from user where username='".$this->username."')";
		 $stmt2 = $this->conn->prepare($update_fb_id_query);
		 $stmt2->execute();
		 
		 
        return $stmt;
    }
	
    function isAlreadyExist(){
        $query = "SELECT user.id as user_id , shop_store.id as store_id
            FROM
                " . $this->table_name . " ,shop_store 
            WHERE
				user.id=shop_store.user_id AND
                user.username='".$this->username."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->id=$row['user_id'];
			$this->store_id=$row['store_id'];
            return true;
        }
        else{
            return false;
        }
    }
	
	function getStoreIdByUserId(){
        $query = "SELECT user.id as user_id , shop_store.id as store_id
            FROM
                " . $this->table_name . " ,shop_store 
            WHERE
				user.id=shop_store.user_id AND
                user.id='".$this->id."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->id=$row['user_id'];
			$this->store_id=$row['store_id'];
            return true;
        }
        else{
            return false;
        }
    }
}