<?php
    require_once("Recommender.php");
    $res = $RecommenderController->get_top_recommendations("stepbrothers", 5);

    if(is_null($res))
    	echo "Oops, we couldn't find that movie!\n";
    else
    	var_dump($res);
?>
