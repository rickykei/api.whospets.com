<?php
    define( 'API_SERVER_ACCESS_KEY', 'AAAAi600M1M:APA91bF674Ea8uKwWyvwoIpaXHOsQwZHMSaXHB4Fm6leraPjUpslXVcW3Jxm3YzJWlZAy7j7gOvsv0bViTxEYh-AJGSM9q1hAQVm74-Yb5bSWmBQ4bqLBWB19loc0GaanppKDCuX0INM' );
	
    $token 	 = $_GET['device_id'];/*FCM 接收端的token*/
	$message = $_GET['message'];/*要接收的內容*/
	$title 	 = $_GET['title'];  /*要接收的標題*/
	$content_id = $_GET['content_id'];  /*要接收的標題*/
	$app_table  = $_GET['app_table'];  /*要接收的標題*/
	
    /*
    notification組成格式 官方範例
    {
      "message":{
        "token":"bk3RNwTe3H0:CI2k_HHwgIpoDKCIZvvDMExUdFQ3P1...",
        "notification":{
          "title":"Portugal vs. Denmark",
          "body":"great match!"
        }
      }
    }
    */
    
    $content = array
    (
        'title'	=> $title,
		'body' 	=> $message,
		'app_table' => $app_table,
		'content_id' => $content_id
    );
	$fields = array
	(
        //'to'		    =>"f4WpUobUs_c:APA91bERazPn7H1wh8guucqx5EmZNOc4qezXFt-dW7caopnY0s-bPlkn9Ic0ls-aGWVcOkbzrEeAlDRKJLCRu2yvOkQF3KuvIkEeRhGcwX_JXZZ6zbKRHrnRY4eHRmEq7TjZKuGIq8ze","eFZn_vDoDY8:APA91bFi45TMDF7o5PIMSlfPnnDRTEWQ_go6lLkZaLlbH3zG7MBNpqgCnF9x3y-wxmgdZOaPSQQ74EUjr8xJzcTe82IOOYxrIJrX9gbpxNqre-UpOxMzrUl7P008C9-8wRJBESq8Z6cn",
		'to' => $token,
		'notification'	=> $content
		
	);
	
	//firebase認證 與 傳送格式
	$headers = array
	(
		'Authorization: key='. API_SERVER_ACCESS_KEY,
		'Content-Type: application/json'
	);
	
	/*curl至firebase server發送到接收端*/
	$ch = curl_init();//建立CURL連線
	curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );//關閉CURL連線
	//result 是firebase server的結果
	echo $result;
	
	?>