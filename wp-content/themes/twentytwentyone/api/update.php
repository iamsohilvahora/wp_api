<?php
    header('Content-Type: application/json');

	function user_update($request){
		global $wpdb;
		$user_id = $request['id'];
		$username = $request['username'];
		$email = $request['email'];
		$password = $request['password'];

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
	      		$update_query = $wpdb->update('wp_register', array('username' => $username, 'email' => $email, 'password' => $password), array('id' => $user_id));

	      		if($update_query){
	      			echo "User data updated successfully \n";
	      			$select_query = $wpdb->get_results("SELECT * FROM wp_register WHERE id = '$user_id' ");

	      			if($select_query){
	      				echo json_encode(array('username'=>$select_query[0]->username, 'email'=>$select_query[0]->email, 'password'=>$select_query[0]->password));
	      			}
	      		}
	      		else{
	      			echo "There is an error to update user data";
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
	}



?>