<?php
	require_once("pass.php");
	require_once("Clarifai.php");
    require_once("Search.php");

    $start = microtime(true);

    $auth = Clarifai::get_auth($client_id, $client_secret);

    $query = new Search("The Social Network");
    $query_tags = $query->get_tags($auth);

	$ch_scrape = curl_init('https://www.tastekid.com/movies/like/Step-Brothers');
	curl_setopt($ch_scrape, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt(... other options you want...)

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

    $match_tags = array();
    foreach ($matches as $match)
    {
        $match_search = new Search($match);
        $match_tags[] = $match_search->get_tags($auth);
    }
    var_dump($match_tags);

	curl_close($ch_scrape);

    echo microtime(true) - $start;
?>
