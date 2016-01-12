<?php
	session_start();

    include('../config/dbconf.php');
    global $config;
    $pdo = new PDO($config['host'], $config['user'], $config['password']);
    $stmt = $pdo->prepare('SELECT * FROM exo040116 WHERE login = :login');
    $stmt->bindParam('login', $_POST['login']);
    $stmt->execute();
    $result = $stmt->fetch();
    if(!isset($_POST['login']))
    {
        $errorMessage = null;
    }
    elseif($result === false)
    {
        $errorMessage = 'Nom d\'utilisateur introuvable';
    }
    elseif($_POST['password'] != $result['password'])
    {
        $errorMessage = 'Mot de passe incorrect';
    }
    else
    {
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['logged'] = true;
        header('Location: index.php');
        exit;
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
        p {
            color: crimson;
        }
    </style>
</head>
<body>
	<h2>Accéder au jeu</h2>
    <form name="login" method="POST">
    	<input type="text" name="login" placeholder="Nom d'utilisateur" autofocus><br><br>
        <input type="password" name="password" placeholder="Mot de passe"><br><br>
        <input type="submit" value="S'identifier">
    </form>
    <p>
        <?php
            echo($errorMessage);
        ?>
    </p>
</body>
</html>