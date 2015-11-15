<?php
    require_once("Recommender.php");
    if(isset($_GET['q'])) {
    	$movies = $RecommenderController->get_top_recommendations($_GET['q']);
    } else {
    	header("Location: index.html");
    }
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>CINEMAKU - Results</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
	</head>
	<body>
		<!-- Header -->
			<header id="header">
				<h1><a href="index.html">CINE<font color="orange">MAKU</font></a></h1>
				<nav id="nav">
					<ul>
					<form method="get" action="" align="right">
					<input type="text" name="	q" alt="Search" placeholder="Try another search" maxlength="256" size="32" style="width: 300px;"/>
					</form>
					</ul>
				</nav>
				
			</header>	

		<!-- Main -->
			<section id="main" class="wrapper">
				<div class="container">
					<header class="major">
						<h2>You should check out:</h2>
						<p>Recommendations are based on visual analysis and comparison of your movie to related films</p>
					</header>
						<ol>
							<?php
								foreach($movies as $movie) {
									echo '<li>';
									echo '<div class="container">';
									echo '<div class="left">' . $movie . '</div>';
									echo '</div>';
									echo '</li>';
								}
							?>
						</ol>
				</div>
			</section>
	</body>
</html>