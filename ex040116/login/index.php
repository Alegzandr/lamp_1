<?php
session_start();

if (empty($_POST['login'])) {
    $errorMessage = null;
} else {
    $_POST['login'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($_POST['login']))));
    include('../../config/dbconf.php');
    global $config;
    $pdo = new PDO($config['host'], $config['user'], $config['password']);
    $stmt = $pdo->prepare('SELECT * FROM ex040116 WHERE login = :login');
    $stmt->bindParam('login', $_POST['login']);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result === false) {
        $errorMessage = 'Nom d\'utilisateur introuvable';
    } elseif (sha1($_POST['password']) != $result['password']) {
        $errorMessage = 'Mot de passe incorrect';
    } else {
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['logged'] = true;
        header('Location: ../');
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
    <link rel="stylesheet" href="../css/styles.css" type="text/css">
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