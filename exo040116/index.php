<?php
    session_start();

    if(!$_SESSION['logged'])
    {
        header('Location: login.php');
        exit;
    }

    include('../pdo.php');
	$log = $_SESSION['login'];
    $query = $db->prepare('SELECT best_score FROM exo040116 WHERE login = :username');
	$query->bindParam(':username', $log , PDO::PARAM_STR);
	$query->execute();
    while($datas = $query->fetch())
    {
        $his = $datas['best_score'];
    }
    $query->closeCursor();
    $_SESSION['hiscore'] = $his;

    if(isset($_SESSION['choice']))
    {
        echo('<h2>Tentative n°'.$_SESSION['tries'].'</h2>');
    }
    else{
        $_SESSION['choice'] = rand(1, 100);
        $_SESSION['tries'] = 1;
        echo('<h2>Tentative n°'.$_SESSION['tries'].'</h2>');
    }

    $response = null;
    if(empty($_POST['guess']) || !isset($_POST['guess']))
    {
        $response = "Pas de nombre";
    }
    else
    {
        $guess = $_POST['guess'];

        if($guess > $_SESSION['choice'])
        {
            $response = "C'est moins";
            $_SESSION['tries']++;
        }
        else if($guess < $_SESSION['choice'])
        {
            $response = "C'est plus";
            $_SESSION['tries']++;
        }
        else
        {
            $response = "C'est gagné";
            unset($_SESSION['choice']);

            if($_SESSION['hiscore'] === 0 || $_SESSION['tries'] < $_SESSION['hiscore'])
            {
                $_SESSION['hiscore'] = $_SESSION['tries'];
                $his = $_SESSION['hiscore'];
                $query = $db->prepare('UPDATE lamp040116 SET best_score = ":score" WHERE login = ":username"');
                $query->bindParam(':score', $his , PDO::PARAM_STR);
                $query->bindParam(':username', $log , PDO::PARAM_STR);
                $query->execute();
                $query->closeCursor();
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
    <script>
        window.onload = function(){
            document.forms['game'].elements['guess'].focus();
        };
    </script>
</head>
<body>
    <form name="game" method="POST">
        <input type="text" name="guess"><br><br>
        <input type="submit">
    </form>

    <?php
        echo $response;
	    if($_SESSION['hiscore'] != 0)
	    {
	        echo('<br>Meilleur score : '.$_SESSION['hiscore']);
	    }
    ?>
</body>
</html>