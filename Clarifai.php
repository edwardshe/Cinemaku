<?php
	class Clarifai {
		public static function get_tags($url, $auth) {
			$ch_result = curl_init();
		    $header = 'Authorization: Bearer ' . $auth;

		    curl_setopt($ch_result, CURLOPT_URL, "https://api.clarifai.com/v1/tag/?url=" . $url); 
		    curl_setopt($ch_result, CURLOPT_HTTPHEADER, array(
			    $header
		    ));
		    curl_setopt($ch_result, CURLOPT_RETURNTRANSFER, true);
		    $ch_result_output = curl_exec($ch_result); 

			// close curl resource to free up system resources 
		    curl_close($ch_result);

		    return json_decode($ch_result_output)->results[0]->result->tag->classes;
		}

		public static function get_auth($client_id, $client_secret) {
			// create curl resource 
		    $ch_auth = curl_init(); 

		    $data = array("grant_type" => "client_credentials");                                                                    

		    // set url 
		    curl_setopt($ch_auth, CURLOPT_URL, "https://" . $client_id . ":" . $client_secret . "@api.clarifai.com/v1/token/"); 

		    curl_setopt($ch_auth, CURLOPT_POST, TRUE);
		    curl_setopt($ch_auth, CURLOPT_POSTFIELDS, $data);
		    curl_setopt($ch_auth, CURLOPT_RETURNTRANSFER, true);

		    //return the transfer as a string 
		    //curl_setopt($ch_auth, CURLOPT_RETURNTRANSFER, 1); 

		    // $ch_auth_output contains the output string 
		    $ch_auth_output = curl_exec($ch_auth);

		    // close curl resource to free up system resources 
		    curl_close($ch_auth);

		    return json_decode($ch_auth_output)->access_token;
		}
	}
?>