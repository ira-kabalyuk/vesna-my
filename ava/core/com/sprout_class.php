<?php
class Com_sprout{
	private static $key="SproutVideo-Api-Key:1ddf622d5aafcb912de3f55f103771ee";

	

	static function get_token(){
		
    $url='https://api.sproutvideo.com/v1/upload_tokens';
    
    $headers=array();
      
    $headers[] = self::$key;
    $body='{"seconds_valid": 1800}';	

	$ch = curl_init();
  		// $data=json_encode($json_value);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
 // curl_setopt($ch, CURLOPT_HEADER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0');

  //curl_setopt($ch,CURLOPT_USERPWD, self::$user.':'.self::$passw);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);

  $temp = json_decode($output) ; 

  
  //add_log("sprout",$output);
	return $temp->token;
}


static function delete($id){
		$headers=array();
       $headers[] = self::$key; 
     //$headers[] = 'X-HTTP-Method-Override: DELETE'; 
    	
    	$url="https://api.sproutvideo.com/v1/videos/".$id;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0');
  	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
  	
  	$output = curl_exec($ch);
 	curl_close($ch);
	$temp = json_decode($output) ; 
	//add_log("sprout_delete",$output);
	if(isset($temp->id)){
		return true;
	}else{
		return false;
	}
	
}

static function get_poster($vid){
	$headers=array();
       $headers[] = self::$key; 
    	$url="https://api.sproutvideo.com/v1/videos/".$vid;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0');
  	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
  	
  	$output = curl_exec($ch);
 	curl_close($ch);
	$temp = json_decode($output) ; 
	add_log("sprout_poster",$output);
	if(isset($temp->id)){
		return $temp->assets->poster_frames;
	}else{
		return array();
	}

}


static function set_poster($vid,$poster){
	$headers=array();
       $headers[] = self::$key; 
    	$url="https://api.sproutvideo.com/v1/videos/".$vid;

    	 $body='{"posterframe_number": '.$poster.'}';	


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0');
  	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
  	//curl_setopt($ch, CURLOPT_PUT, 1);
  	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
  	
  	$output = curl_exec($ch);
   
 	curl_close($ch);
 
	//$temp = json_decode($output) ; 
	add_log("sprout_setposter",$output);
	_emit("update_video",$vid,"update",$output);
	

}



}