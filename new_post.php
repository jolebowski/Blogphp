<?php

session_start();
//include database connection
require_once('./includes/connection.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ./admin/login.php');
    exit();
}
if (isset($_POST['submit'])) {
    if ($_FILES['photo']['error']) {
        switch ($_FILES['photo']['error']) {
            case 1: // UPLOAD_ERR_INI_SIZE
            echo "La taille du fichier est plus grande que la limite autorisée par le serveur (paramètre upload_max_filesize du fichier php.ini).";
            break;
            case 2: // UPLOAD_ERR_FORM_SIZE
            echo "La taille du fichier est plus grande que la limite autorisée par le formulaire (paramètre post_max_size du fichier php.ini).";
            break;
            case 3: // UPLOAD_ERR_PARTIAL
            echo "L'envoi du fichier a été interrompu pendant le transfert.";

            break;
            case 4: // UPLOAD_ERR_NO_FILE
            echo "La taille du fichier que vous avez envoyé est nulle.";
            break;
        }
    } else {
        //s'il n'y a pas d'erreur alors $_FILES['nom_du_fichier']['error']
        //vaut 0
        echo "Aucune erreur dans le transfert du fichier.<br />";
        if ((isset($_FILES['photo']['name'])&&($_FILES['photo']['error'] == UPLOAD_ERR_OK))) {
            $chemin_destination = 'photos/';
            //déplacement du fichier du répertoire temporaire (stocké
            //par défaut) dans le répertoire de destination
            move_uploaded_file($_FILES['photo']['tmp_name'], $chemin_destination.$_FILES['photo']['name']);
            echo "Le fichier ".$_FILES['photo']['name']." a été copié dans le répertoire photos";
        } else {
            echo "Le fichier n'a pas pu être copié dans le répertoire photos.";
        }
    }
    //get the blog data
    $title = $_POST['title'];
    $body = $_POST['body'];
    $photo = $_POST['photo'];
    $category = $_POST['category'];
    $query = $db->prepare("INSERT INTO posts(user_id, title, body, photo, category_id, posted) VALUES (:user_id, :title, :body, :photo, :category, :date)");
    $query->bindParam(':user_id', $user_id);
    $query->bindParam(':title', $title);
    $query->bindParam(':body', $body);
    $query->bindParam(':photo', $photo);
    $query->bindParam(':category', $category);
    $query->bindParam(':date', $date);
    $user_id = $_SESSION['user_id'];
    date_default_timezone_set("Europe/Paris");
    $date = date("Y-m-d H:i:s");
    $body = htmlentities($body);
    $photo = $_FILES['photo']['name'];
    $query->execute();
    if ($title && $body  && $photo && $category) {
        if ($query) {
            echo "Post publie";
        } else {
            echo "Error";
        }
    } else {
        echo "Veuillez remplir les champs";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>Blog</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
	<style>
	#container {
		padding: 10px;
		width: 800px;
		margin: auto;
		backgroung: white;
	}
	#wrapper {
		margin: auto;
		width: 800px;
	}
	label {
		display: block;
	}
	select {
		display: block;
	}
	</style>
</head>
<body>
	<nav>
		<div class="nav-wrapper">
			<a href="#" class="brand-logo center">Blog</a>
			<ul id="nav-mobile" class="left hide-on-med-and-down">
				<li><a href="./index.php">Accueil</a></li>
				<li class="active"><a href="new_post.php">Ceer un nouveau article</a></li>
				<li><a href="./index_blog.php">Fil d'actualites</a></li>
				<li><a class="right-align" href="./admin/logout.php">Deconnexion</a></li>
			</ul>
		</div>
	</nav>
	<div class="section"></div>

	<div id="wrapper">
		<div id="content">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST"  enctype="multipart/form-data">

				<i class="material-icons prefix">title</i>
				<input type="text" name="title" />
				<i class="material-icons prefix">mode_edit</i>
				<textarea id="icon_prefix2" name="body" class="materialize-textarea"></textarea>

				<input type="hidden" name="MAX_FILE_SIZE" value="2097152"/>
				<p>Choisissez une photo avec une taille inférieure à 2 Mo.</p>
				<div class="btn">
					<input type="file" name="photo"/>>
				</div>
				<label>Category:</label>

				<select name="category">

					<?php
                    $query = $db->query("SELECT * FROM categories");
                    while ($row = $query->fetchObject()) {
                        echo "
						<option value='".$row->category_id."'>".$row->category."</option>";
                    }
                    ?>

				</select>
				<br/>
				<br/>
				<center>
					<div class='row'>
						<button type='submit'value="Submit" name='submit' class='col s12 btn btn-large waves-effect indigo'>Envoyer</button>
					</div>
				</center>
			</form>
		</div>
	</div>
</body>
</html>
