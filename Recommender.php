<?php
	require_once("pass.php");
	require_once("Clarifai.php");
    require_once("Search.php");

	class Recommender {

		public function __construct($id, $secret) {
			$this->client_id = $id;
			$this->client_secret = $secret;
		}

	    public function get_top_recommendations($query_name) {
		    $auth = Clarifai::get_auth($this->client_id, $this->client_secret);

		    $query = new Search($query_name);
		    $query_links = $query->get_links(); // Get links to query stills using Google Images API
		    $matches = $this->get_initial_recommendations($query_name); // Get initial recommended movies

		    $links = array();
		    $links[$query_name] = $query_links; // Array containing links
		    foreach ($matches as $match) { // Get stills to each of the initial recommended movies
		        $match_search = new Search($match);
		        $links[$match] = $match_search->get_links(); // Add links to array
		    }

		    $tags_array = Clarifai::get_unique_tags($links, $auth); // Get tags using Clarifai API
		    
		    $results = array();
		    foreach (array_slice($tags_array, 1, 5) as $key => $tags) {
		        $results[$key] = count(array_intersect($tags, $tags_array[$query_name]));
		    }
		    arsort($results);
		    
		    return $results;
		}

		private function get_initial_recommendations($query_name) {
			$ch_scrape = curl_init('https://www.tastekid.com/movies/like/' . urlencode($query_name));
			curl_setopt($ch_scrape, CURLOPT_RETURNTRANSFER, true);

			$html = curl_exec($ch_scrape);

			if (curl_error($ch_scrape))
			    die(curl_error($ch_scrape));

			// Get the status code
			$status = curl_getinfo($ch_scrape, CURLINFO_HTTP_CODE);

			if($status == 200)
			{
				preg_match_all('/<span class="tk-Resource-title">(.*)<\/span>/', $html, $matches);
				$matches = array_slice($matches[1], 1, 5);
			}
			else
			{
				echo "Connection failed.";
			}

			curl_close($ch_scrape);

			return $matches;
		}

		private $client_id;
		private $client_secret;

	}

	$RecommenderController = new Recommender($client_id, $client_secret);

?>