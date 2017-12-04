<?php

//include database connection
require_once('./includes/connection.php');

// get record of database
$record_count = count($db->query("SELECT COUNT(post_id) FROM posts")->fetchAll());

$per_page = 5;

$query = $db->prepare("SELECT * FROM posts INNER JOIN categories ON categories.category_id=posts.category_id ORDER BY post_id DESC LIMIT $per_page");
$query->execute();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>Blog</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
</head>
<style>
#container {
	padding: 10px;
	width: 800px;
	margin: auto;
	background: white;
}

body {
  background-color: #ffffff;
}

.card {
  background-color: #f4f4f4;
  width: 600px;
  margin: 10px auto;
  box-shadow: 1px 1px 10px 1px rgba(0,0,0,0.7);
}

.card-header {
  overflow: hidden;
  width: 100%;
  max-height: 200px;
}

.card-header img{
  width: 100%;
}

.card-content {
  width: 85%;
  margin: 35px auto;
}

.card-content h3 {
  font-size: 25px;
  margin-bottom: 0;
  color: #303F9F;
  font-family: 'Montserrat', sans-serif;
}

.card-content h4 {
  font-size: 14px;
  margin-top: 0;
  color: #FF5252;
  font-family: 'Montserrat', sans-serif;
}

.card-content p {
  color: #727272;
  font-size: 12px;
  font-family: 'Open Sans', sans-serif;

}

.card-footer {
  border-top: solid 1px #B6B6B6;
  padding: 5px;
}

.card-footer ul {
  padding: 0;
  width: 90%;
  margin: auto;
  text-align: center;
}

.card-footer ul li{
  display: inline-block;
  list-style: none;
  margin: 5px;
}

.card-footer ul li i {
  font-size: 2em;
  color: #3F51B5;
}
</style>
<body>
	<nav>
		<div class="nav-wrapper">
			<a href="#" class="brand-logo center">Blog</a>
			<ul id="nav-mobile" class="left hide-on-med-and-down">
				<li><a href="./index.php">Accueil</a></li>
				<li><a href="new_post.php">Ceer un nouveau article</a></li>
				<li class="active"><a href="./index_blog.php">Fil d'actualites</a></li>
				<li><a class="right-align" href="./admin/logout.php">Deconnexion</a></li>
			</ul>
		</div>
	</nav>
	<div id="container">
		<?php
        while ($article = $query->fetch()):
            $lastspace = strrpos($article['body'], ' ');
            ?>
			<div class="card">
				<div class="card-header">
					<?php
                    if ($article['photo'] != "") {
                        echo "<img src='photos/".$article['photo']."' width='200px' height='200px'/>";
                    }
                    echo "<hr />";
                    ?>
				</div>
				<div class="card-content">
				  <h3><?php echo $article['title']?></h3>
				  <h4><?php echo substr($article['body'], 0, $lastspace)."<a href='post.php?id={$article['post_id']}'> Voir plus de details</a>"?></h4>
				  <p>Category: <?php echo $article['category']?></p>
  				  <p>Datant du: <?php echo $article['posted']?></p>
				</div>
				<div class="card-footer">
					<?php echo "<a href='delete.php?id={$article['post_id']}'> Supprimer</a>"?>
				</div>

				<?php
            endwhile;
            ?>
		</div>
	</div>
</body>
</html>
