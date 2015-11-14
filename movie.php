<?php
	require_once("pass.php");

	// create curl resource 
    $ch = curl_init(); 

    $data = array("grant_type" => "client_credentials");                                                                    

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://" . $client_id . ":" . $client_secret . "@api.clarifai.com/v1/token/"); 

    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //return the transfer as a string 
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 


    $ch2 = curl_init();
    $header = 'Authorization: Bearer ' . json_decode($output)->access_token;

    curl_setopt($ch2, CURLOPT_URL, "https://api.clarifai.com/v1/tag/?url=http://www.clarifai.com/img/metro-north.jpg"); 
    curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
	    $header
    ));
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    $output2 = curl_exec($ch2); 

    var_dump(json_decode($output2)->results[0]->result->tag->classes);

    // close curl resource to free up system resources 
    curl_close($ch);
    curl_close($ch2);
?>
