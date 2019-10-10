<?php
session_start();
require 'MasterMind.php';
if(isset($_POST['exit']))
{
    session_destroy();
    unset($_POST);
    unset($_SESSION);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MasterMind</title>
    <style>
        .cadre {
            border-style: dashed;
            border-color: purple;
            width: 60%;
            min-height: 400px;
            margin: auto;
            padding: 25px;
        }

        .cadre>form {
            border-style: solid;
            border-color: blue;
            text-align: center;
            margin: 25px;
            padding: 25px;

        }

        .cadre>h1 {
            text-align: center;
        }

        .cadre>h2 {
            text-align: center;
        }

        .author {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 30px;
            background: whitesmoke;
            height: 100px;
        }
    </style>
</head>

<body>
    <div class="cadre">
        <?php

        if (!isset($_POST['nom']) && !isset($_SESSION['nom'])) {
            // première connexion

            ?>
            <h1>MasterMind</h1>
            <h2>Bienvenue au jeu du MasterMind</h2>
            <p><b>Les règles : </b>Le jeu crèe un code aléatoire caché à 4 chiffres différents. Le joueur tente à chaque coup une
                combinaison de 4 chiffres différents et le jeu lui répond en indiquant le nombre de chiffres bien placés et le nombre
                de chiffres mal placés.</p>
            <form action="" method="POST">
                <label for="nom">Votre nom : </label>
                <br>
                <input type="text" name="nom" id="nom">
                <br>
                <input type="submit" value="Jouer">
                <br>
                <input type="submit" name="load" value ="Load Partie" id="load">
                
            </form>
        <?php
        } else if (isset($_POST['nom']) || !isset($_SESSION['jeu']) || isset($_POST['load'])) {   //premier essai
            if(isset($_COOKIE['nom']))
            {
                $_SESSION['nom'] =  $_COOKIE['nom'];
                $game = unserialize($_COOKIE['jeu']);
                $_SESSION['jeu']=serialize($game);                
            }
            if (isset($_POST['nom'])) {
                $_SESSION['nom'] =  $_POST['nom'];
            }
            if (!isset($_SESSION['jeu'])) {
                $game = new MasterMind(4);
                $_SESSION['jeu'] = serialize($game);
                //echo 'secret : ' . $game->getSecret();
            }
            ?>
            <h1>MasterMind</h1>
            <form action="" method="POST">
                <label for="essai">Tetentez votre chance : </label>
                <br>
                <input type="text" name="essai" id="essai">
                <br>
                <input type="submit" name="submit1" value="Tentez votre chance">
                <br>
                <input type="submit" name="exit" value ="Nouvelle Partie" id="exit">
                <br>
                <input type="submit" name="save" value ="Save Partie" id="save">

            </form>

            <?php
            } else {
                // autre
                /*if (isset($_SESSION['essai1'])|| isset($_POST['essai1'])) {
                    $_SESSION['essai1'] = $_POST['essai1'];
                    $essai = new Essais($_SESSION['essai1'], 0, 0);
                    $game = unserialize($_SESSION['jeu']);
                    //echo 'secret : ' . $game->getSecret();
                    $game->firstTest($essai);
                }*/
               

                if (isset($_POST['essai'])) {
                    $_SESSION['essai'] = $_POST['essai'];
                }
                $essai = new Essais($_SESSION['essai'], 0, 0);
                $game = unserialize($_SESSION['jeu']);
                if (isset($_POST['save'])) {
                    setcookie('jeu',serialize($game),Time()+(3600*24));
                    setcookie('nom',$_SESSION['nom'],Time()+(3600*24));
                    session_destroy();
                }
                    $game->test($essai);
                echo 'secret : ' . $game->getSecret();
                if (!$game->isWinner()) {
                    ?>
                <h1>MasterMind</h1>
                <h2><?php echo 'bonjour '.$_COOKIE['nom']?></h2>
                <form action="" method="POST">
                    <label for="essai">Retentez votre chance : </label>
                    <br>
                    <input type="text" name="essai" id="essai" >
                    <br>
                    <input type="submit" name="submit2" value="Retentez votre chance">
                    <br>
                    <input type="submit" name="exit" value ="Nouvelle Partie" id="exit">
                    <br>
                    <input type="submit" name="save" value ="Save Partie" id="save">    
                </form>
        <?php
                for ($i = 0; $i < $game->getNbEssais(); $i++) {
                    echo '<br>essai numéro ' . ($i + 1) . ' est : ' . $game->getEssai($i)->getEssai()  . ' avec ' . $game->getEssai($i)->getBp() . ' lettres bien placées' . ' et ' . $game->getEssai($i)->getMp() . ' lettres mal placées <br>';
                }
                $_SESSION['jeu'] = serialize($game);
            } else {
                echo 'FELECITATION ' . $_SESSION['nom'] . ' VOUS AVEZ RÉUSSI EN ' . $game->getNbEssais() . ' ESSAIS. <br> BRAVOOOOO !!!!!!!!';
                session_destroy();
                cookie_sestroy();
                unset($_POST);
                unset($_SESSION);
                unset($_COOKIE);
            }
        }
        ?>
        <div class="author">
            <p style="text-align:center">Created by ELHOUITI Chakib &copy; OCT. 2019</p>
        </div>
    </div>
    <?php /*
if (isset($_SESSION['jeu'])) {
    $game = unserialize($_SESSION['jeu']);
    echo 'secret : ' . $game->getSecret();
} else {
    $game = new MasterMind(4);
    $_SESSION['jeu'] = serialize($game);
    //$game = unserialize($_SESSION['jeu']);
}
?>
    <form action="" method="POST">
    <label for="essai">essai : </label>
    <input type="text" name="essai" id="essai" size="4" required>
    <input type="submit" value="Continuer">
    
    <?php
    if (isset($_POST['essai'])) { //&& $_POST['essai'] != $game->getEssai($game->getNbEssais() - 1)->getEssai()) {
        
        $essai = new Essais($_POST['essai'], 0, 0);
        $game->test($essai);
        
        for ($i = 0; $i < $game->getNbEssais(); $i++) {
            echo '<br>essai numéro ' . $i . ' est : ' . $game->getEssai($i)->getEssai()  . ' avec ' . $game->getEssai($i)->getBp() . ' lettres bien placées' . ' et ' . $game->getEssai($i)->getMp() . ' lettres mal placées <br>';
        }
        $_SESSION['jeu'] = serialize($game);
    }
    if($game->isWinner()){
        session_destroy();
    }
    
    */ ?>
    <br>
    </form>

</body>

</html>