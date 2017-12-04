<?php
session_start();

require_once('../includes/connection.php');

if (isset($_POST['submit'])) {
    $errMsg = '';
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == '') {
        $errMsg .= 'Vous devez rentrer votre identifiant<br>';
    }

    if ($password == '') {
        $errMsg .= 'Vous devez rentrer votre mot de passe<br>';
    }
    if ($errMsg == '') {
        $records = $db->prepare("SELECT user_id, username, password FROM users WHERE username=:username");
        $records->bindParam(':username', $username);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if (count($results) > 0 && (($password) === $results['password'])) {
            $_SESSION['user_id'] = $results['user_id'];
            header('location:../index.php');
            exit;
        } else {
            $errMsg .= 'identifiant ou mot de passe incoretes<br>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Blog</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
	<style type="text/css">
	body {
	  display: flex;
	  min-height: 100vh;
	  flex-direction: column;
	}

	main {
	  flex: 1 0 auto;
	}

	body {
	  background: #fff;
	}

	.input-field input[type=date]:focus + label,
	.input-field input[type=text]:focus + label,
	.input-field input[type=email]:focus + label,
	.input-field input[type=password]:focus + label {
	  color: #e91e63;
	}

	.input-field input[type=date]:focus,
	.input-field input[type=text]:focus,
	.input-field input[type=email]:focus,
	.input-field input[type=password]:focus {
	  border-bottom: 2px solid #e91e63;
	  box-shadow: none;
	}

	</style>
</head>
<body>
	<div class="section"></div>
	<main>
		<center>
			<div class="section"></div>

			<h5 class="indigo-text">S'identifier</h5>
			<div class="section"></div>

			<div class="container">
				<div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
					<?php
                        if (isset($errMsg)) {
                            echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$errMsg.'</div>';
                        }
                    ?>
					<form class="col s12" method="post" action="login.php">
						<div class='row'>
							<div class='col s12'>
							</div>
						</div>

						<div class='row'>
							<div class='input-field col s12'>
								<input class='validate' type='text' name='username' />
							</div>
						</div>

						<div class='row'>
							<div class='input-field col s12'>
								<input class='validate' type='password' name='password' id='password' />
							</div>
						</div>

						<br />
						<center>
							<div class='row'>
								<button type='submit'value="Submit" name='submit' class='col s12 btn btn-large waves-effect indigo'>Login</button>
							</div>
						</center>
					</form>
				</div>
			</div>
		</center>
		<div class="section"></div>
		<div class="section"></div>
	</main>
</body>
</html>
