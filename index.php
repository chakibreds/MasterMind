<?php
session_start();
require 'MasterMind.php';
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
                <input type="text" name="nom" id="nom" required>
                <br>
                <input type="submit" value="Jouer">
            </form>
        <?php
        } else if (isset($_POST['nom']) || !isset($_SESSION['jeu'])) {   //premier essai
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
                <input type="text" name="essai" id="essai" required>
                <br>
                <input type="submit" name="submit1" value="Tentez votre chance">
            </form>

            <?php
            } else {
                // autre

                if (isset($_POST['essai']))
                    $_SESSION['essai'] = $_POST['essai'];
                $essai = new Essais($_SESSION['essai'], 0, 0);
                $game = unserialize($_SESSION['jeu']);
                //echo 'secret : ' . $game->getSecret();
                $game->test($essai);
                if (!$game->isWinner()) {
                    ?>
                <h1>MasterMind</h1>
                <form action="" method="POST">
                    <label for="essai">Retentez votre chance : </label>
                    <br>
                    <input type="text" name="essai" id="essai" required>
                    <br>
                    <input type="submit" name="submit2" value="Retentez votre chance">
                </form>
                <?php
                for ($i = 0; $i < $game->getNbEssais(); $i++) {
                    echo '<br>essai numéro ' . ($i + 1) . ' est : ' . $game->getEssai($i)->getEssai()  . ' avec ' . $game->getEssai($i)->getBp() . ' lettres bien placées' . ' et ' . $game->getEssai($i)->getMp() . ' lettres mal placées <br>';
                }
                $_SESSION['jeu'] = serialize($game);
            } else {
                echo 'FELECITATION ' . $_SESSION['nom'] . ' VOUS AVEZ RÉUSSI EN ' . $game->getNbEssais() . ' ESSAIS. <br> BRAVOOOOO !!!!!!!!';
                session_destroy();
                unset($_POST);
                unset($_SESSION);
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