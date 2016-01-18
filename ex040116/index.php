<?php
session_start();
if (!$_SESSION['logged']) {
    header('Location: ./login');
    exit;
}
include('../config/dbconf.php');
global $config;
$pdo = new PDO($config['host'], $config['user'], $config['password']);
$stmt = $pdo->prepare('SELECT best_score FROM ex040116 WHERE login = :login');
$stmt->bindParam('login', $_SESSION['login']);
$stmt->execute();
$result = $stmt->fetch();
$_SESSION['best_score'] = $result['best_score'];
if (isset($_POST['reset'])) {
    unset($_SESSION['choice']);
    unset($_SESSION['tries']);
}
if (isset($_POST['reset-bs'])) {
    $stmt = $pdo->prepare('UPDATE ex040116 SET best_score = NULL WHERE login = :login');
    $stmt->bindParam('login', $_SESSION['login']);
    $stmt->execute();
}
if (isset($_POST['logout'])) {
    $stmt = $pdo->prepare('UPDATE ex040116
                          SET last_choice = :choice,
                          last_tries = :tries
                          WHERE login = :login');
    $stmt->bindParam('choice', $_SESSION['choice']);
    $stmt->bindParam('tries', $_SESSION['tries']);
    $stmt->bindParam('login', $_SESSION['login']);
    $stmt->execute();
    unset($_SESSION['login']);
    unset($_SESSION['choice']);
    unset($_SESSION['tries']);
    $_SESSION['logged'] = false;
    header('Location: ./login');
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
    $stmt = $pdo->prepare('UPDATE ex040116 SET last_guess = :guess WHERE login = :login');
    $stmt->bindParam('guess', $guess);
    $stmt->bindParam('login', $_SESSION['login']);
    $stmt->execute();
    if ($guess > $_SESSION['choice']) {
        $response = "C'est moins !";
        $_SESSION['tries']++;
    } else if ($guess < $_SESSION['choice']) {
        $response = "C'est plus !";
        $_SESSION['tries']++;
    } else {
        $response = "C'est gagné !";
        unset($_SESSION['choice']);
        if (!isset($_SESSION['best_score']) || ($_SESSION['tries'] < $_SESSION['best_score'])) {
            $_SESSION['best_score'] = $_SESSION['tries'];
            $stmt = $pdo->prepare('UPDATE ex040116 SET best_score = :score WHERE login = :login');
            $stmt->bindParam('score', $_SESSION['best_score']);
            $stmt->bindParam('login', $_SESSION['login']);
            $stmt->execute();
        }
        $stmt = $pdo->prepare('UPDATE ex040116
                              SET last_choice = NULL,
                              last_guess = NULL,
                              last_tries = NULL
                              WHERE login = :login');
        $stmt->bindParam('login', $_SESSION['login']);
        $stmt->execute();
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
    <link rel="stylesheet" href="./css/styles.css" type="text/css">
</head>
<body>
<form name="logged" method="POST">
    <input type="button" value="Leaderboard" name="lb">
    <input type="submit" value="Se déconnecter" name="logout">
</form>

<form name="game" method="POST">
    <input type="text" name="guess" autofocus><br><br>
    <input type="submit" value="Envoyer">
    <input type="submit" value="Reset game" name="reset">
    <input type="submit" value="Reset best score" name="reset-bs">
</form>

<?php
echo '<p>' . $response . '</p>';

if (isset($_SESSION['choice'])) {
    echo '<p>Indice : ' . $_SESSION['choice'] . '</p>';
}

if (isset($_SESSION['best_score'])) {
    echo '<p>Meilleur score : ' . $_SESSION['best_score'] . '</p>';
} else {
    echo '<p>Pas de meilleur score.</p>';
}
?>

<script src="./js/script.js"></script>
</body>
</html>