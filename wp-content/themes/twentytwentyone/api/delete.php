<?php
header('Content-Type: application/json');

	function user_delete($request){
		global $wpdb;
		$user_id = $request['id'];

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
		      	$delete_query = $wpdb->query("DELETE FROM wp_register WHERE id = '$user_id' ");

		      	if($delete_query){
		      		echo "User deleted successfully";
		      	}
		      	else{
		      		echo "There is an error to delete user data";
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