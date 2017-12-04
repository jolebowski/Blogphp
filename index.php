<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./admin/login.php');
    exit();
}
//include database connection
require_once('./includes/connection.php');

$post_count = $db->query("SELECT * FROM posts");

$comment_count = $db->query("SELECT * FROM comments");

if (isset($_POST['submit'])) {
    $newCategory = $_POST['newCategory'];
    if (!empty($newCategory)) {
        $query = $db->prepare("INSERT INTO categories (category) VALUES (?)");
        $query->bindParam(1, $newCategory);
        $query->execute();
        $newCategory = filter_input(INPUT_POST, 'newCategory', FILTER_SANITIZE_URL);
        if ($query) {
            echo "Une nouvelle categorie a ete ajoute";
        } else {
            echo "Error";
        }
    } else {
        echo "Il manque le nom de la nouvelle categorie";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>Blog</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<style>
	#container {
		padding: 10px;
		width: 800px;
		margin: auto;
		backgroung: white;
	}
	#mainContent {
		clear: both;
		margin-top: 5px;
		font-size: 25px;
	}
	</style>
</head>
<body>
	<nav>
		<div class="nav-wrapper">
			<a href="#" class="brand-logo center">Blog</a>
			<ul id="nav-mobile" class="left hide-on-med-and-down">
				<li class="active"><a href="./index.php">Accueil</a></li>
				<li><a href="new_post.php">Creer un nouveau article</a></li>
				<li><a href="./index_blog.php">Fil d'actualites</a></li>
				<li><a class="right-align" href="./admin/logout.php">Deconnexion</a></li>
			</ul>
		</div>
	</nav>
	<div id="container">
		<div id="mainContent">
			<table>
				<tr>
					<td>Total d'articles</td>
					<td><?php echo $post_count->rowCount(); ?></td>
				</tr>
				<br>
				<tr>
					<td>Total de commentaires</td>
					<td><?php echo $comment_count->rowCount(); ?></td>
				</tr>
			</table>
			<br>
			<div id="categoryForm">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
					<label for="category">Ajoutez une nouvelle categorie: </label><input type="text" name="newCategory" />
					<button type='submit'value="Submit" name='submit' class='col s12 btn btn-large waves-effect indigo'>Envoyez</button>
				</form>
			</div>
		</div>
	</div>
	]</body>
	</html>
