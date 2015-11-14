<?php
	require_once("pass.php");
	require_once("Clarifai.php");
    require_once("GImages.php");

    $auth = Clarifai::get_auth($client_id, $client_secret);

    $still_search = new GImages("The Social Network");
    $still_urls = $still_search->get_links();

    $query_tags = array();
    foreach($still_urls as $still_url) {
        $tags = Clarifai::get_tags($still_url, $auth);
        if (is_array($tags)) {
            foreach($tags as $tag) {
                if (!in_array($tag, $query_tags))
                {
                    $query_tags[] = $tag;
                }
            }
        }
    }

    var_dump($query_tags);

	$ch_scrape = curl_init('https://www.tastekid.com/movies/like/Dog-Day-Afternoon');
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
		//var_dump($matches);
	}
	else
	{
		echo "Connection failed.";
	}

	curl_close($ch_scrape);
?>
