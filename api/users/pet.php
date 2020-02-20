<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
class Pet{
 
    // database connection and table name
    private $conn;
    private $table_name = "shop_products";
 
    // object properties
    public $id;
    public $username;
	
	public $product_id;
    public $category_id;
    public $status;
	public $store_id;
	public $tax_id;
	public $title;
	public $description;
	public $descriptionDisplay;
	public $keywords;
	public $price;
	public $language;
	public $specification;
	public $style_code;
	public $color;
	public $condition;
	public $size;
	public $quantity;
	public $view;
	public $created;
	public $feature_date;
	public $gallery_date;
	public $banner_a;
	public $banner_b;
	public $banner_c;
	public $todays_deal;
	public $discount;
	public $date_lost;
	public $date_born;
	public $sub_category;
	public $weight;
	public $height;
	public $name_of_pet;
	public $country;
	public $contact;
	public $pet_status;
	public $count_down_end_date;
	public $last_seen_appearance;
	public $questions;
	public $pet_id;
	public $gender;
	public $country_id;
	public $sub_country_id;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	 
    // add Pet Info 
    function addPet(){
    
        //if($this->isAlreadyExist()){
        //    return false;
        //}
	   
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    category_id=:category_id, status=:status, store_id=:store_id, tax_id=:tax_id, title=:title ,
					description=:description, descriptionDisplay=:descriptionDisplay,keywords=:keywords,
					price=:price , language=:language ,specifications=:specifications, style_code=:style_code,
					color=:color, condition=:condition, size=:size,	quantity=:quantity, view=:view,
					created=now(),feature_date=:feature_date,gallery_date=:gallery_date,banner_a=:banner_a,
					banner_b=:banner_b,banner_c=:banner_c,todays_deal=:todays_deal, discount=:discount,
					date_lost=:date_lost, date_born=:date_born, sub_category=:sub_category, weight=:weight,
					height=:height, name_of_pet=:name_of_pet, country=:country, contact=:contact,pet_status=:pet_status,
					count_down_end_date=:count_down_end_date,last_seen_appearance=:last_seen_appearance,questions=:questions,
					pet_id=:pet_id,gender=:gender, country_id=:country_id, sub_country_id=:sub_country_id 
					";
    
		
        // prepare query
        $stmt = $this->conn->prepare($query);
	 
        // sanitize
        //$this->username=htmlspecialchars(strip_tags($this->username));
        //$this->password=htmlspecialchars(strip_tags($this->password));
        //$this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":category_id", $this->category_id, PDO::PARAM_INT);
        $stmt->bindParam(":status", $this->status , PDO::PARAM_INT);
        $stmt->bindParam(":store_id", $this->store_id, PDO::PARAM_INT);
		$stmt->bindParam(":tax_id", $this->tax_id, PDO::PARAM_INT);
		$stmt->bindParam(":title", $this->title);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":descriptionDisplay", $this->descriptionDisplay);
		$stmt->bindParam(":keywords", $this->keywords);
		$stmt->bindParam(":price", $this->price);
		$stmt->bindParam(":language", $this->language);
		$stmt->bindParam(":specifications", $this->specifications);
		$stmt->bindParam(":style_code", $this->style_code);
		$stmt->bindParam(":color", $this->color);
		$stmt->bindParam(":condition", $this->condition);
		$stmt->bindParam(":size", $this->size);
		$stmt->bindParam(":quantity", $this->quantity , PDO::PARAM_INT);
		$stmt->bindParam(":view", $this->view, PDO::PARAM_INT);
		//$stmt->bindParam(":created", $this->created);
		$stmt->bindParam(":feature_date", $this->feature_date);
		$stmt->bindParam(":gallery_date", $this->gallery_date);
		$stmt->bindParam(":banner_a", $this->banner_a);
		$stmt->bindParam(":banner_b", $this->banner_b);
		$stmt->bindParam(":banner_c", $this->banner_c);
		$stmt->bindParam(":todays_deal", $this->todays_deal);
		$stmt->bindParam(":discount", $this->discount);
		$stmt->bindParam(":date_lost", $this->date_lost);
		$stmt->bindParam(":date_born", $this->date_born);
		$stmt->bindParam(":sub_category", $this->sub_category);
		$stmt->bindParam(":weight", $this->weight);
		$stmt->bindParam(":height", $this->height);
		$stmt->bindParam(":name_of_pet", $this->name_of_pet);
		$stmt->bindParam(":country", $this->country);
		$stmt->bindParam(":contact", $this->contact);
		$stmt->bindParam(":pet_status", $this->pet_status);
		$stmt->bindParam(":count_down_end_date", $this->count_down_end_date);
		$stmt->bindParam(":last_seen_appearance", $this->last_seen_appearance);
		$stmt->bindParam(":questions", $this->questions);
		$stmt->bindParam(":pet_id", $this->pet_id);
		$stmt->bindParam(":gender", $this->gender);
		$stmt->bindParam(":country_id", $this->country_id, PDO::PARAM_INT);
		$stmt->bindParam(":sub_country_id", $this->sub_country_id, PDO::PARAM_INT);
		 
		
        // execute query
        if($stmt->execute()){
			
			$this->id = $this->conn->lastInsertId();
				 
					return true;
			 
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
		
        return $stmt;
    }
    // getUserPets
    function getUserPets(){
        // select all query
        $query = "SELECT
                    shop_products.*
                FROM
                    " . $this->table_name . " , user,shop_store
                WHERE
					shop_products.store_id=shop_store.id
					and shop_store.user_id = user.id
                    and username='".$this->username."'";
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }
	
	function getPetsByStatus(){
        // select all query
        $query = "SELECT
                    shop_products.*,shop_image.title as title,shop_image.filename as filename
                FROM
                    " . $this->table_name . " ,shop_image, shop_store
                WHERE
					shop_products.store_id=shop_store.id
					and shop_products.product_id = shop_image.product_id and
					shop_products.pet_status=".$this->pet_status;
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }
	
    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                username='".$this->username."'";
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