<?php

if (!isset($_GET['id'])) {
    header('Location: index_blog.php');
    exit();
} else {
    $id = $_GET['id'];
}
//include database connection
require_once('./includes/connection.php');
if (!is_numeric($id)) {
    header('Location: index_blog.php');
}
$query = $db->prepare("SELECT title, body FROM posts WHERE post_id=:post_id");
$query->bindParam(':post_id', $id);
$query->execute();
$results = $query->fetch();
if (count($results) == 0) {
    header('Location: index_blog.php');
    exit();
}
// define variables and set to empty values
$nameErr = $emailErr = $commentErr = "";
$name = $email = $comment = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Votre nom est requis";
    } else {
        $name = test_input($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "Seulements lettres sont autorises";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Votre adresse mail est requise";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    if (empty($_POST["comment"])) {
        $comment = "";
        $commentErr = "Votre commentaire est requis";
    } else {
        $comment = test_input($_POST["comment"]);
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($addComment = $db->prepare("INSERT INTO comments(post_id, email_add, name, comment) VALUES (:post_id, :email, :name, :comment)")) {
    $addComment->bindParam(':post_id', $id);
    $addComment->bindParam(':email', $email);
    $addComment->bindParam(':name', $name);
    $addComment->bindParam(':comment', $comment);
    $addComment->execute();
    if ($addComment) {
        echo "Thank you! Your comment was added.";
    }
    $addComment->closeCursor();
} else {
    echo "Failed!'";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>Blog</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<style type="text/css">
	#container {
		padding: 10px;
		width: 800px;
		margin: auto;
		backgroung: white;
	}
	label {
		display: block;
	}
	.error {
		color: #FF0000;
	}
	</style>
</head>
<body>
	<nav>
		<div class="nav-wrapper">
			<a href="#" class="brand-logo center">Blog</a>
			<ul id="nav-mobile" class="left hide-on-med-and-down">
				<li><a href="./index.php">Accueil</a></li>
				<li><a href="new_post.php">Ceer un nouveau article</a></li>
				<li><a href="./index_blog.php">Fil d'actualites</a></li>
				<li><a class="right-align" href="./admin/logout.php">Deconnexion</a></li>
			</ul>
		</div>
	</nav>
	<div id="container">
		<div id="post">
			<?php
            echo "<h2>".$results['title']."</h2>";
            echo "<p>".$results['body']."</p>";
            ?>
		</div>
		<hr>
		<div id="addComments">
			<p><span class="error">* Remplissez tous les champs.</span></p>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?id=$id"?>" method="POST">
				Nom: <input type="text" name="name">
				<span class="error">* <?php echo $nameErr;?></span>
				<br><br>
				E-mail: <input type="text" name="email">
				<span class="error">* <?php echo $emailErr;?></span>
				<br><br>
				<i class="material-icons prefix">mode_edit</i>
				<textarea id="icon_prefix2" name="comment" class="materialize-textarea"></textarea>
				<span class="error">* <?php echo $commentErr;?></span>
				<br><br>
				<input type="hidden" name="post_id" value="<?php echo $id?>" />
				<div class='row'>
					<button type='submit'value="Submit" name='submit' class='col s12 btn btn-large waves-effect indigo'>Envoyer</button>
				</div>
			</form>

		</div>
		<hr>
		<div id="Comments">
			<?php
            $query = $db->query("SELECT * FROM comments WHERE post_id='$id' ORDER BY comment_id DESC");
            while ($row = $query->fetchObject()):
                ?>
				<div>
					<h5><?php echo $row->name?></h5>
					<blockquote><?php echo $row->comment?></blockquote>
				</div>
				<?php
            endwhile;
            ?>
		</div>
	</div>
</body>
</html>
