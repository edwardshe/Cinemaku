<?php
class Search {
	private $still_urls = array();
	private $tags = array();
	public function __construct($query) {
		$url = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&rsz=4&q=" . urlencode($query);
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$body = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($body);

		foreach ($data->responseData->results as $result) {
		    $this->still_urls[] = $result->url;
		}
	}

	public function get_links() {
		return $this->still_urls;
	}

	public function get_tags($auth) {
		foreach($this->still_urls as $still_url) {
	        $tags_search = Clarifai::get_tags($still_url, $auth);
	        if (is_array($tags_search)) {
	            foreach($tags_search as $tag) {
	                if (!in_array($tag, $this->tags))
	                {
	                    $this->tags[] = $tag;
	                }
	            }
	        }
	    }
		return $this->tags;
	}
}
?>