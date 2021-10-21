<?php 
header('Content-Type: application/json');

function user_login($request) {
	global $wpdb;
	$email = sanitize_email($request['email']);
	$password = $request['password'];

	$query = $wpdb->get_results( "SELECT password FROM wp_register WHERE email = '$email'" );
	$hash_password = $query[0]->password;
	if(password_verify($password, $hash_password)){
		$password = $hash_password;
	}
	  
	// $headers = apache_request_headers();
	// $token = $headers['Authorization'];
	// echo $token ."\n";

	$token = null;
	$headers = apache_request_headers();
	// echo $headers['Authorization'] . "\n";
	if(isset($headers['Authorization'])){
	    $matches = array();
	    //preg_match('/Token token="(.*)"/', $headers['Authorization'], $matches);
	    $matches = explode(' ', $headers['Authorization']);
	    if(isset($matches[1])){
	      $token = $matches[1];
	      $token_query = $wpdb->get_results( "SELECT token FROM wp_register WHERE token = '$token'" );
	      if($token_query){
	      	if(!empty($email) && !empty($password)){
	      		$query = $wpdb->get_results( "SELECT email, password FROM wp_register WHERE email = '$email' AND password = '$password'" );

	      		if($query){
	      			echo "Successfully logged in";
	      		}
	      		else{
	      			echo "Invalid email or password";
	      		}
	      	}
	      	else{
	      		echo "User data not found";
	      	}
	      }
	      else{
	      	echo "Please enter valid authentication token";
	      }
	    }
	} 
	else{
		echo "Authentication token is required";
	}
	// echo $token ."\n";

	
}



?>