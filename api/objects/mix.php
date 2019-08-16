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
					(select title from shop_products  where product_id = c.owner_pet_id)  as product_title,
                    b.filename as image,
					b.app_table as app_table,
					b.created_date,
					c.user_id
                FROM
                   app_image b,app_post c
                WHERE
					   c.id= b.product_id
            and app_table='LIFESTYLE'     
			 and b.is_default='Y'
					   and c.user_id ='".$this->user_id."' 
         group by c.id
				union all
				SELECT
					c.id,
					(select title from shop_products  where product_id = c.owner_pet_id)  as product_title,
                    b.filename as image,
					b.app_table as app_table,
					b.created_date,
					c.user_id
                FROM
                   app_image b,app_qna c
                WHERE
					   c.id= b.product_id
              and app_table='QNA' 
			   and b.is_default='Y'
					   and c.user_id ='".$this->user_id."' 
        group by c.id
				union all
				SELECT
					c.id,
					'' as product_title,
                    b.filename as image,
					b.app_table as app_table,
					b.created_date,
					c.user_id
                FROM
                   app_image b,app_sell c
                WHERE
					   c.id= b.product_id
             and app_table='SELL' 
			  and b.is_default='Y'
					   and c.user_id ='".$this->user_id."'	   
         group by c.id
			) a
			order by id desc 
			limit ".$offset.", ".$limit
					   ;
        // prepare query statement
		//echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }  
	
	 function getUserFollowingMixMix($limit,$offset,$following_user_id_str){
        // select all query
        $query = "
			select * from (
		
				SELECT
					c.id,
					c.title,
					(select title from shop_products  where product_id = c.owner_pet_id)  as product_title,
					c.description,
                    b.filename as image,
					b.app_table as app_table,
					b.created_date,
					c.user_id,
					(select count(*) from app_like a where a.content_id=c.id and a.table_name='app_post') as likecnt,
					(select count(*) from app_like a where a.content_id=c.id and a.user_id=c.user_id and a.table_name='app_post') as ownlike,
					(select count(*) from app_comment a where a.content_id=c.id and a.table_name='app_post') as commentcnt 
				
                FROM
                   app_image b,app_post c
                WHERE
					   c.id= b.product_id
						and app_table='LIFESTYLE'     
						and b.is_default='Y'
					   and c.user_id in (".$following_user_id_str.")
					   group by id
				union all
				SELECT
					c.id,
					c.title,
					(select title from shop_products  where product_id = c.owner_pet_id)  as product_title,
					c.description,
                    b.filename as image,
					b.app_table as app_table,
					b.created_date,
					c.user_id,
					(select count(*) from app_like a where a.content_id=c.id and a.table_name='app_qna') as likecnt,
					(select count(*) from app_like a where a.content_id=c.id and a.user_id=c.user_id and a.table_name='app_qna') as ownlike,
					(select count(*) from app_comment a where a.content_id=c.id and a.table_name='app_qna') as commentcnt 
					 
                FROM
                   app_image b,app_qna c
                WHERE
					   c.id= b.product_id
              and app_table='QNA' 
			  and b.is_default='Y'
					   and c.user_id in (".$following_user_id_str.")
					    group by id
				union all
				SELECT
					c.id,
					c.title,
					'' as product_title,
					c.description,
                    b.filename as image,
					b.app_table as app_table,
					b.created_date,
					c.user_id,
					(select count(*) from app_like a where a.content_id=c.id and a.table_name='app_sell') as likecnt,
					(select count(*) from app_like a where a.content_id=c.id and a.user_id=c.user_id and a.table_name='app_sell') as ownlike,
					(select count(*) from app_comment a where a.content_id=c.id and a.table_name='app_sell') as commentcnt 
					 
                FROM
                   app_image b,
				   app_sell c 
                WHERE
					   c.id= b.product_id 
					   and b.app_table='SELL'
					and b.is_default='Y'					   
					   and c.user_id in (".$following_user_id_str.")
					    group by id
				union all
				SELECT
                   c.product_id,
				   c.title,
				   '' as product_title,
					c.description,
				   shop_image.filename as image,
				   'shop_product',
				   c.created as created_date,
				   shop_store.user_id,
					(select count(*) from app_like b where b.content_id=c.product_id and b.table_name='shop_products') as likecnt,
					(select count(*) from app_like b where b.content_id=c.product_id and b.user_id=shop_store.user_id and b.table_name='shop_products') as ownlike,
					(select count(*) from shop_feedback b where b.product_id=c.product_id ) as commentcnt
					
                FROM
                     user,shop_store ,shop_products c left join shop_image on c.product_id = shop_image.product_id
                WHERE
					c.store_id=shop_store.id
					and shop_store.user_id = user.id
					and shop_image.is_default='Y'
                    and user.id in (".$following_user_id_str.")
					 group by shop_image.id
			) a
			order by created_date desc
			limit ".$offset.", ".$limit
					   ;
        // prepare query statement
	//	echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }  
}