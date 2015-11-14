<?php
	require_once("pass.php");
	require_once("Clarifai.php");
    require_once("Search.php");

    $start = microtime(true);

    $auth = Clarifai::get_auth($client_id, $client_secret);

    $query_name = "Step Brothers";
    $query = new Search($query_name);
    $query_links = $query->get_links();

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
		$matches = array_slice($matches[0], 1, 5);
	}
	else
	{
		echo "Connection failed.";
	}

    $links = array();
    $links[$query_name] = $query_links;
    foreach ($matches as $match)
    {
        $match_search = new Search($match);
        $links[$match] = $match_search->get_links();
    }
    $tags_array = Clarifai::get_unique_tags($links, $auth);
    $results = array();
    foreach (array_slice($tags_array, 1, 5) as $key => $tags) {
        $results[$key] = count(array_intersect($tags, $tags_array[$query_name]));
    }
    arsort($results);
    var_dump($results);

	curl_close($ch_scrape);

    echo microtime(true) - $start;
?>
