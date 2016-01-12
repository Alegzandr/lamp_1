<?php
	session_start();
	try
	{
	    $db = new PDO('mysql:host=localhost;dbname=exos_lamp;charset=utf8', 'root', 'root');
	}
	catch (Exception $e)
	{
	        die('Erreur : ' . $e->getMessage());
	}

	$query = $db->query('SELECT * FROM lamp040116');
	while($datas = $query->fetch())
	{
		$_SESSION['login'] = $datas['login'];
		$pass = $datas['password'];
	}

	if(isset($_POST['login']) && isset($_POST['password']))
	{
		if(isset($_SESSION['login']) && $pass === $_POST['password'])
		{
			$_SESSION['logged'] = true;
			header('Location: index.php');
			exit;
		}
	}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes">
    <title>Des papiers dans un bol - Login</title>
    <style>
        body {
            margin-top: 150px;
            background-color: #000;
            color: #fff;
            font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            text-align: center;
        }
        input {
            text-align: center;
        }
    </style>
    <script>
        window.onload = function(){
            document.forms['login'].elements['login'].focus();
        };
    </script>
</head>
<body>
	<h2>Accéder au jeu</h2>
    <form name="login" method="POST">
    	<input type="text" name="login" placeholder="Nom d'utilisateur"><br><br>
        <input type="password" name="password" placeholder="Mot de passe"><br><br>
        <input type="submit" value="S'identifier">
    </form>
</body>
</html>