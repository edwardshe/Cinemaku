<?php
	require_once("pass.php");
	require_once("Clarifai.php");

    $auth = Clarifai::get_auth($client_id, $client_secret);

    $tags = Clarifai::get_tags("http://images4.fanpop.com/image/photos/22300000/The-Social-Network-Stills-mark-and-eduardo-22324211-1280-850.jpg", $auth);
    var_dump($tags);

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
		var_dump($matches);
	}
	else
	{
		echo "Connection failed.";
	}

	curl_close($ch_scrape);
?>
