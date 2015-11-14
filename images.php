<?php
$url = "https://ajax.googleapis.com/ajax/services/search/images?" .
       "v=1.0&q=step%20brothers";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$body = curl_exec($ch);
curl_close($ch);

$data = json_decode($body);

foreach ($data->responseData->results as $result) {
    $results[] = array('url' => $result->url, 'alt' => $result->title);
}

print_r($results);
?>