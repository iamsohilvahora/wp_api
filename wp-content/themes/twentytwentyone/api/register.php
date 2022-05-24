<?php
header('Content-Type: application/json');

function user_register($request) {
	global $wpdb;
	$username = $request['username'];
	$email = sanitize_email($request['email']);
	
	$password = password_hash($request['password'], PASSWORD_DEFAULT);
	$token = bin2hex(random_bytes(64));
	// echo $token; 
	// dd();

	if(!empty($username) && !empty($email) && !empty($password) && !empty($token)){
		$select_query = $wpdb->get_results( "SELECT email FROM wp_register WHERE email = '$email'" );

		if($select_query){
			echo "User already registered with these email";
		}
		else{
			$insert_query = $wpdb->insert('wp_register', array("username" => $username, "email" => $email, "password" => $password, "token"=>$token), array("%s", "%s", "%s", "%s"));

			if($insert_query){
				echo "User Data Inserted Successfully \n";
				echo json_encode(array('Bearer Token'=>$token));
			}
			else{
				echo "There is an error to insert user data";
			}
		}
	}
	else{
		echo "All fields are required";
	}
}



?>