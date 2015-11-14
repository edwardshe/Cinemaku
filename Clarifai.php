<?php
	interface ClarifaiInterface {
		public static function get_unique_tags(array $url, $auth);
		public static function get_multi_tags(array $url, $auth);
		public static function get_tags($url, $auth);
		public static function get_auth($client_id, $client_secret);
	}

	class Clarifai implements ClarifaiInterface {

		public static function get_unique_tags(array $url, $auth) {
			$tags_array = self::get_multi_tags($url, $auth);

			$ret = array();
			foreach($tags_array as $key => $tags) {
				$unique_tags = array();
				for($i = 0; $i < count($tags); $i++) {
					foreach($tags[$i] as $tag) {
						if(!in_array($tag, $unique_tags))
							array_push($unique_tags, $tag);
					}
				}
				$ret[$key] = $unique_tags;
			}

			return $ret;
		}

		public static function get_multi_tags(array $url, $auth) {
			$mh = curl_multi_init();
			$header = 'Authorization: Bearer ' . $auth;
			 
			foreach($url as $key => $links) {
				for($i = 0; $i < count($links); $i++) {
					$ch[$key][$i] = curl_init();

					curl_setopt($ch[$key][$i], CURLOPT_URL, "https://api.clarifai.com/v1/tag/?url=" . $links[$i]); 
					curl_setopt($ch[$key][$i], CURLOPT_HTTPHEADER, array(
						$header
					));
					curl_setopt($ch[$key][$i], CURLOPT_RETURNTRANSFER, true);

					//add the two handles
					curl_multi_add_handle($mh, $ch[$key][$i]);
				}
			}

			$running = null;
			do {
				curl_multi_exec($mh, $running);
			} while($running > 0);

			$ret = array();
			foreach($url as $key => $links) {
				$tags = array();
				for($i = 0; $i < count($links); $i++) {
					if(strcmp(json_decode(curl_multi_getcontent($ch[$key][$i]))->status_code, "OK") == 0)
						array_push($tags, json_decode(curl_multi_getcontent($ch[$key][$i]))->results[0]->result->tag->classes);

					//close the handles
					curl_multi_remove_handle($mh, $ch[$key][$i]);
				}
				$ret[$key] = $tags;
			}

			curl_multi_close($mh);
			return $ret;
		}

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