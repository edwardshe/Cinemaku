<?php
class GImages {
	private $results = array();
	public function __construct($query) {
		$url = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&rsz=8&q=" . urlencode($query);
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$body = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($body);

		foreach ($data->responseData->results as $result) {
		    $this->results[] = $result->url;
		}
	}

	public function get_links() {
		return $this->results;
	}
}
?>