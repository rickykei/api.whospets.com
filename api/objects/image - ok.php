<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
include_once "uploadclass.php";
class Image{
 
    // database connection and table name
    private $conn;
    private $table_name = "shop_image";
 
    // object properties
    public $id;
    public $username;
	
	public $title;
    public $filename;
    public $product_id;
	public $is_default;
	public $exten;
	
    // constructor with $db as database connection
    public function __construct($db,$upload){
        $this->conn = $db;
		//$this->filename=$upload['image_field']['name'];
		$this->title=$upload['image_field']['name'];
		$this->filename=md5($upload['image_field']['name']);
		$a=explode(".",$upload['image_field']['name']);
		//print_r($a);
		$this->exten=$a[1];
    }
	
	 
    // add Image
    function addImage(){ 
	
	$fname=$this->product_id.'/'.$this->filename.".".$this->exten;
	$name=$this->filename;
	$name_wfn=$this->filename;
        // query to insert record
        $query = "INSERT INTO 
                     shop_image 
                SET 
					title=:title,
					filename=:filename,
					product_id=:product_id, 
					is_default=:is_default";
					
    
		 
        // prepare query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":filename",$fname);
        $stmt->bindParam(":product_id", $this->product_id);
		$stmt->bindParam(":is_default", $this->is_default);
 
		
        // execute query
        if($stmt->execute()){
			
			$this->id = $this->conn->lastInsertId();
				 
					
					//copy image to big directory
					
					//convert image to thumbnail
					$handle = new upload($_FILES['image_field']);
					if ($handle->uploaded) {
					  $handle->file_new_name_body   = $name;
					  $handle->image_resize         = false;
					  
					  $handle->process('/vhost/sosopet/sosopet/images/product/'.$this->product_id);
					  //$handle->clean();
					  $handle->file_new_name_body   = $name;
					  $handle->image_resize         = true;
					  $handle->image_x              = 250;
					  $handle->image_ratio_y        = true;
					  $handle->process('/vhost/sosopet/sosopet/images/product/thumb/'.$this->product_id);
					  
					  if ($handle->processed) {
						//echo 'image resized';
						$handle->clean();
					  } else {
						//echo 'error : ' . $handle->error;
					  }
					}
			 
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
                    shop_products.*,shop_image.filename as image
                FROM
                    " . $this->table_name . " , user,shop_store,shop_image
                WHERE
					shop_products.store_id=shop_store.id
					and shop_products.product_id = shop_image.product_id
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