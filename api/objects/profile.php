<?php
class Profile{
 
    // database connection and table name
    private $conn;
    private $table_name = "profile";
 
    // object properties
	public $username;
    public $id;
    public $tc;
	public $user_id;
	public $lastname;
    public $firstname;
    public $email;
	public $street;
	public $city;
	public $about;
	public $newsletter;
	public $seller;
	public $notification;
	public $gender;
	public $birthday;
	public $bio;
	public $country_id;
	public $sub_country_id;
	public $language;
	public $device_id;
	
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
 
	function getDeviceIdByCountryId($country_id){
		
		$device_id_sql=" SELECT device_id,user_id FROM `profile` WHERE `country_id` = 1 and device_id != '' ";
		$stmt = $this->conn->prepare($device_id_sql);
		
		 if($stmt->execute()){

           if($stmt->rowCount() > 0){

			$i=0;
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$device_ids[$i]=$row['device_id'];
				$user_ids[$i]=$row['user_id'];
				$i++;
			}
		   }
            return $device_ids;

        }
	}
	
	
	
	  function getProfileByUserId($user_id){
    
         
        // query to insert record
        $query = "select profile.* 
		
		from " . $this->table_name . " , user a 
		where 
		username=:username and a.id=profile.user_id 
		";
    
	//echo $query;
	 
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->username=htmlspecialchars(strip_tags($uname));
    
        // bind values
        $stmt->bindParam(":username", $uname);
   
    
        // execute query
        if($stmt->execute()){
           
		    
            return $stmt;
        }
    
        return false;
        
    }
	
    // signup user
    function getProfileByUsername($uname){
    
         
        // query to insert record
        $query = "select profile.* 
		
		from " . $this->table_name . " , user a 
		where 
		username=:username and a.id=profile.user_id 
		";
    
	//echo $query;
	 
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->username=htmlspecialchars(strip_tags($uname));
    
        // bind values
        $stmt->bindParam(":username", $uname);
   
    
        // execute query
        if($stmt->execute()){
            return $stmt;
        }
    
        return false;
        
    }
function getProfileDetailByUsername($uname){

    

         

        // query to insert record

        $query = "select profile.* 

		

		from " . $this->table_name . " , user a 

		where 

		username=:username and a.id=profile.user_id 

		";

    

	//echo $query;

	 

        // prepare query

        $stmt = $this->conn->prepare($query);

    

        // sanitize

        $this->username=htmlspecialchars(strip_tags($uname));

    

        // bind values

        $stmt->bindParam(":username", $uname);

   

    

        // execute query

        if($stmt->execute()){

           if($stmt->rowCount() > 0){

			 

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				 

			$this->device_id=$row['device_id'];

			$this->id=$row['id'];

           $this->user_id=$row['user_id'];

			}

		   }

		    

            return $stmt;

        }

    

        return false;

        

    }

		

		 // Create profile
    function createProfile($user_id){
		$this->user_id=$user_id;
		 
        if($this->isAlreadyExist()){
            $query = "update
                    " . $this->table_name . "
                SET
                    tc=:tc,
						language=:language,lastname=:lastname,firstname=:firstname,email=:email,street=:street,city=:city,about=:about,newsletter=:newsletter,seller=:seller,notification=:notification,gender=:gender,birthday=:birthday,bio=:bio,country_id=:country_id,sub_country_id=:sub_country_id where id =:id ";
					 $stmt = $this->conn->prepare($query);
						$stmt->bindParam(":id", $this->id);
	 
					//	$stmt->bindParam(":user_id", $this->user_id);
						$stmt->bindParam(":tc", $this->tc);
						$stmt->bindParam(":lastname", $this->lastname);
						$stmt->bindParam(":firstname", $this->firstname);
						$stmt->bindParam(":email", $this->email);
						$stmt->bindParam(":street", $this->street);
						$stmt->bindParam(":city", $this->city);
						$stmt->bindParam(":about", $this->about);
						$stmt->bindParam(":newsletter", $this->newsletter);
						$stmt->bindParam(":seller", $this->seller);
						$stmt->bindParam(":notification", $this->notification);
						$stmt->bindParam(":gender", $this->gender);
						$stmt->bindParam(":birthday", $this->birthday);
						$stmt->bindParam(":bio", $this->bio);
						$stmt->bindParam(":country_id", $this->country_id);
						$stmt->bindParam(":sub_country_id", $this->sub_country_id);
						$stmt->bindParam(":language", $this->language);
        }else{
			 
        // query to insert record
			$query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    user_id=$user_id";
					   $stmt = $this->conn->prepare($query);
 
		}
 
		
 
		
        // execute query
        if($stmt->execute()){		 
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
        
    }
    
      function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                user_id='".$this->user_id."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
		
		
        if($stmt->rowCount() > 0){
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->id=$row['id'];
			
            return true;
        }
        else{
            return false;
        }
    }
}
