<?php
session_start();

if (!$_SESSION['logged']) {
    header('Location: login.php');
    exit;
}

include('../config/dbconf.php');
global $config;
$pdo = new PDO($config['host'], $config['user'], $config['password']);
$stmt = $pdo->prepare('SELECT best_score FROM exo040116 WHERE login = :login');
$stmt->bindParam('login', $_SESSION['login']);
$stmt->execute();
$result = $stmt->fetch();
$_SESSION['hiscore'] = $result['best_score'];

if (isset($_POST['reset'])) {
    unset($_SESSION['choice']);
    unset($_SESSION['tries']);
}

if (isset($_POST['logout'])) {
    unset($_SESSION['login']);
    $_SESSION['logged'] = false;
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['choice'])) {
    echo('<h2>Tentative n°' . $_SESSION['tries'] . '</h2>');
} else {
    $_SESSION['choice'] = rand(1, 100);
    $_SESSION['tries'] = 1;
    echo('<h2>Tentative n°' . $_SESSION['tries'] . '</h2>');
}

$response = null;
if (empty($_POST['guess']) || !isset($_POST['guess'])) {
    $response = "Pas de nombre";
} else {
    $guess = $_POST['guess'];

    if ($guess > $_SESSION['choice']) {
        $response = "C'est moins";
        $_SESSION['tries']++;
    } else if ($guess < $_SESSION['choice']) {
        $response = "C'est plus";
        $_SESSION['tries']++;
    } else {
        $response = "C'est gagné";
        unset($_SESSION['choice']);

        if ($_SESSION['hiscore'] === 0 || $_SESSION['tries'] < $_SESSION['hiscore']) {
            $_SESSION['hiscore'] = $_SESSION['tries'];
            $stmt = $pdo->prepare('UPDATE exo040116 SET best_score = :score WHERE login = :login');
            $stmt->bindParam('login', $_SESSION['login']);
            $stmt->bindParam('score', $_SESSION['hiscore']);
            $stmt->execute();
        }
        unset($_SESSION['tries']);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes">
    <title>Des papiers dans un bol</title>
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
</head>
<body>
<form name="game" method="POST">
    <input type="text" name="guess" autofocus><br><br>
    <input type="submit" value="Envoyer">
    <input type="submit" value="Reset" name="reset">
</form>

<?php
echo $response;
if ($_SESSION['hiscore'] != 0) {
    echo('<br>Meilleur score : ' . $_SESSION['hiscore']);
}
?>

<br><br>
<form name="logged" method="POST">
    <input type="submit" value="Se déconnecter" name="logout">
</form>
</body>
</html>