<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes">
    <title>Des papiers dans un bol - Leaderboard</title>
    <link rel="stylesheet" href="../css/styles.css" type="text/css">
</head>
<body>
<h2>Leaderboard</h2>
<?php
$topPlayers = array();
$topScores = array();

include('../../config/dbconf.php');
global $config;
$pdo = new PDO($config['host'], $config['user'], $config['password']);
$stmt = $pdo->prepare('SELECT login, best_score FROM ex040116 WHERE best_score IS NOT NULL ORDER BY best_score ASC LIMIT 10');
$stmt->bindParam('login', $_SESSION['login']);
$stmt->execute();
while ($result = $stmt->fetch()) {
    array_push($topPlayers, $result['login']);
    array_push($topScores, $result['best_score']);
}
$stmt->closeCursor();

echo('<br><table><tr><td>Numéro</td><td>Joueur</td><td>Meilleur score</td></tr>');
for ($i = 0; $i < 10; $i++) {
    if (isset($topScores[$i])) {
        echo('<tr><td>' . ($i + 1) . '</td><td>' . $topPlayers[$i] . '</td><td>' . $topScores[$i] . '</td></tr>');
    }
}
echo('</table>');
?>

<br>
<form name="navigate">
    <input type="button" value="Précédent" name="previous">
</form>

<script src="../js/script.js"></script>
</body>
</html>
