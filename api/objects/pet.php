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
	public $specifications;
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
	public $pet_id;//userinput
	public $gender;
	public $country_id;
	public $sub_country_id;
	
	public $user_id;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
		//update Pet Info 
    function updatePet(){ 
        // query to insert record
        $query = " update 
                     shop_products 
                SET 
					category_id=:category_id,
					status=:status,
					store_id=:store_id,
					tax_id=:tax_id,
					title=:title, 
					description=:description,
					descriptionDisplay=:descriptionDisplay,
					keywords=:keywords, 
					price=:price,
					language=:language,
					specifications=:specifications,
					style_code=:style_code,
					color=:color,
					`condition`=:condition,
					size=:size,
					quantity=:quantity,
					view=:view, 
					created=now(),
					feature_date=:feature_date,
					gallery_date=:gallery_date,
					banner_a=:banner_a, 
					banner_b=:banner_b,
					banner_c=:banner_c,
					todays_deal=:todays_deal,
					discount=:discount, 
					date_lost=:date_lost,
					date_born=:date_born,
					sub_category=:sub_category,
					weight=:weight, 
					height=:height,
					name_of_pet=:name_of_pet,
					country=:country,
					contact=:contact,
					pet_status=:pet_status,
					count_down_end_date=:count_down_end_date,
					last_seen_appearance=:last_seen_appearance,
					questions=:questions, 
					pet_id=:pet_id,
					gender=:gender,
					country_id=:country_id,
					sub_country_id=:sub_country_id
				where 
					product_id=:product_id
					";
					
    
		 
        // prepare query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":store_id", $this->store_id);
		$stmt->bindParam(":tax_id", $this->tax_id);
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
		$stmt->bindParam(":quantity", $this->quantity);
		$stmt->bindParam(":view", $this->view);
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
		$stmt->bindParam(":country_id", $this->country_id);
		$stmt->bindParam(":sub_country_id", $this->sub_country_id);
		$stmt->bindParam(":product_id", $this->product_id);
		 
		
        // execute query
        if($stmt->execute()){
			
			 
					return true;
			 
        }  
    
        return false;
        
    }
	// del Pet Info 
        function deletePet(){
	 
		// delete pet record
		$query = "delete from
                    " . $this->table_name . "
                       where product_id =:product_id and store_id=:store_id ";
					    $stmt = $this->conn->prepare($query);
						$stmt->bindParam(":product_id", $this->product_id);
						$stmt->bindParam(":store_id", $this->store_id);
				//echo $query;
				
		// execute query
        if($stmt->execute()){		 
          
			// delete shop_image record if hv
			$query = "delete from
                    shop_image
                       where product_id =:product_id   ";
					    $stmt2 = $this->conn->prepare($query);
						$stmt2->bindParam(":product_id", $this->product_id);
						$stmt2->execute();
						
			// delete shop_feedback record if hv
			$query = "delete from
                   shop_feedback
					where product_id =:product_id and  store_id=:store_id ";
					    $stmt3 = $this->conn->prepare($query);
						$stmt3->bindParam(":product_id", $this->product_id);
						//$stmt3->bindParam(":user_id", $this->user_id);
						$stmt3->bindParam(":store_id", $this->store_id);
						$stmt3->execute();	
									
			// delete img files
			$this->deleteDir('/vhost/sosopet/sosopet/images/product/'.$this->product_id.'/');
			$this->deleteDir('/vhost/sosopet/sosopet/images/product/thumb/'.$this->product_id.'/');
			
	
            return true;
        }

        return false;
		
	}
	
    // add Pet Info 
    function addPet(){ 
        // query to insert record
        $query = "INSERT INTO 
                     shop_products 
                SET 
					category_id=:category_id,
					status=:status,
					store_id=:store_id,
					tax_id=:tax_id,
					title=:title, 
					description=:description,
					descriptionDisplay=:descriptionDisplay,
					keywords=:keywords, 
					price=:price,
					language=:language,
					specifications=:specifications,
					style_code=:style_code,
					color=:color,
					`condition`=:condition,
					size=:size,
					quantity=:quantity,
					view=:view, 
					created=now(),
					feature_date=:feature_date,
					gallery_date=:gallery_date,
					banner_a=:banner_a, 
					banner_b=:banner_b,
					banner_c=:banner_c,
					todays_deal=:todays_deal,
					discount=:discount, 
					date_lost=:date_lost,
					date_born=:date_born,
					sub_category=:sub_category,
					weight=:weight, 
					height=:height,
					name_of_pet=:name_of_pet,
					country=:country,
					contact=:contact,
					pet_status=:pet_status,
					count_down_end_date=:count_down_end_date,
					last_seen_appearance=:last_seen_appearance,
					questions=:questions, 
					pet_id=:pet_id,
					gender=:gender,
					country_id=:country_id,
					sub_country_id=:sub_country_id";
					
    
		 
        // prepare query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":store_id", $this->store_id);
		$stmt->bindParam(":tax_id", $this->tax_id);
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
		$stmt->bindParam(":quantity", $this->quantity);
		$stmt->bindParam(":view", $this->view);
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
		$stmt->bindParam(":country_id", $this->country_id);
		$stmt->bindParam(":sub_country_id", $this->sub_country_id);
		 
		
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
	
    //get owner petcat by user ID
	 function getPetsCatByUserID(){
        // select all query
        $query = "SELECT sum(a.sub_category) as petcat FROM user c ,shop_store b, shop_products a 
		WHERE c.id=b.user_id AND b.id = a.store_id and c.id='".$this->user_id."'
		and a.sub_category in (1,2) 
		group by sub_category";
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }
	
	
	// getUserPetsBySearch
	//pet id
    function getPetsBySearch($limit,$offset,$keywords){
        // select all query
        $query = "SELECT
                    shop_products.*,shop_image.filename as image,
					(select count(*) from app_like b where b.content_id=shop_products.product_id and b.table_name='shop_products') as likecnt,
					(select count(*) from app_like b where b.content_id=shop_products.product_id and b.user_id=shop_store.user_id and b.table_name='shop_products') as ownlike,
					shop_store.user_id 
                FROM
                     user,shop_store ," . $this->table_name . " left join shop_image on shop_products.product_id = shop_image.product_id
                WHERE
					shop_products.store_id=shop_store.id
					and shop_store.user_id = user.id
                    and (shop_products.pet_id like '%".$keywords."%'  or 
					shop_products.name_of_pet like '%".$keywords."%'  or 
					shop_products.title like '%".$keywords."%'  )
					order by shop_products.product_id desc";
        // prepare query statement
		 //echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }
	
	// getUserPets
    function getUserPets(){
        // select all query
        $query = "SELECT
                    shop_products.*,
					(select shop_image.filename  from shop_image where shop_image.product_id =shop_products.product_id limit 0,1 ) as image,
					(select count(*) from app_like b where b.content_id=shop_products.product_id and b.table_name='shop_products') as likecnt,
					(select count(*) from app_like b where b.content_id=shop_products.product_id and b.user_id=shop_store.user_id and b.table_name='shop_products') as ownlike,
					shop_store.user_id 
                FROM
                     user,shop_store ," . $this->table_name . "  
                WHERE
					shop_products.store_id=shop_store.id
					 
					and shop_store.user_id = user.id
                    and user.id='".$this->user_id."' 
					order by shop_products.product_id desc";
        // prepare query statement
		// echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }
	
	function getPetsByStatus($limit,$offset){
        // select all query
        $query = "SELECT
                    shop_products.*,shop_image.title as image,shop_image.filename as image,
					(select count(*) from app_like b where b.content_id=shop_products.product_id and b.table_name='shop_products') as likecnt,
					(select count(*) from app_like b where b.content_id=shop_products.product_id and b.user_id=shop_store.user_id and b.table_name='shop_products') as ownlike,
					shop_store.user_id as user_id ,
					(select count(*) from shop_feedback b where b.product_id=shop_products.product_id ) as commentcnt
					
                FROM
                    " . $this->table_name . "   ,shop_image, shop_store 
                WHERE
					shop_products.store_id=shop_store.id
					and shop_products.product_id = shop_image.product_id and 
					shop_products.pet_status=".$this->pet_status."   
					order by shop_products.product_id desc 
					limit ".$offset.", 10"
					;
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }
	
		function getPetsByCountry($limit,$offset,$country_array,$petcat){
			
		$str=implode(",",$country_array);
		
		if ($petcat >0 && $petcat <3){
			$petcat_str=" and shop_products.sub_category in (".$petcat.")";
		
		}else if ($petcat==3){
			$petcat_str=" and shop_products.sub_category in (1,2)";
		}else {
			$petcat_str="";
		}
		
		
        // select all query
        $query = "SELECT
                    shop_products.*,shop_image.title as image,shop_image.filename as image,
					(select count(*) from app_like b where b.content_id=shop_products.product_id and b.table_name='shop_products') as likecnt,
					(select count(*) from app_like b where b.content_id=shop_products.product_id and b.user_id=shop_store.user_id and b.table_name='shop_products') as ownlike,
					(select count(*) from shop_feedback b where b.product_id=shop_products.product_id ) as commentcnt
                FROM
                    " . $this->table_name . "  ,shop_image , shop_store 
                WHERE
					shop_products.store_id=shop_store.id
					and shop_products.product_id = shop_image.product_id and 
					shop_products.sub_country_id in (".$str.")    
					".$petcat_str."
					order by likecnt desc ,shop_products.product_id desc 
					limit ".$offset.", 10"
					;
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
	
	public static function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
	}
	
}