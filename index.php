<?php
session_start();
require 'MasterMind.php';
if (isset($_POST['load'])) {
    if (isset($_COOKIE['nom'])) {
        $_SESSION['nom'] =  $_COOKIE['nom'];
        $_SESSION['jeu'] = $_COOKIE['jeu'];
    }
    else{
        echo 'aucun chargement possible<br>';
        unset($_POST);
    }
}
    if (isset($_POST['exit'])) {
    unset($_POST);
    unset($_SESSION);
    session_destroy();
}
if (isset($_POST['save'])) {
    setcookie('jeu', $_SESSION['jeu'], Time() + (3600 * 24));
    setcookie('nom', $_SESSION['nom'], Time() + (3600 * 24));
    unset($_SESSION);
    unset($_POST);
    session_destroy();
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
        <?php if(isset($_COOKIE['nom']) && isset($_COOKIE['jeu'])){ ?><input type="submit" name="load" value="Load Partie" id="load"><?php } ?>

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
                <label for="essai">Tentez votre chance : </label>
                <br>
                <input type="text" name="essai" id="essai">
                <br>
                <input type="submit" name="submit1" value="Tentez votre chance">
                <br>
                <input type="submit" name="exit" value="Nouvelle Partie" id="exit">
                <br>
                <input type="submit" name="save" value="Save Partie" id="save">

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
                $game->test($essai);
                echo 'secret : ' . $game->getSecret();
                if (!$game->isWinner()) {
                    ?>
                <h1>MasterMind</h1>
                <h2><?php echo 'bonjour ' . $_SESSION['nom'] ?></h2>
                <form action="" method="POST">
                    <label for="essai">Retentez votre chance : </label>
                    <br>
                    <input type="text" name="essai" id="essai">
                    <br>
                    <input type="submit" name="submit2" value="Retentez votre chance">
                    <br>
                    <input type="submit" name="exit" value="Nouvelle Partie" id="exit">
                    <br>
                    <input type="submit" name="save" value="Save Partie" id="save">
                </form>
                <?php
                        for ($i = 0; $i < $game->getNbEssais(); $i++) {
                            echo '<br>essai numéro ' . ($i + 1) . ' est : ' . $game->getEssai($i)->getEssai()  . ' avec ' . $game->getEssai($i)->getBp() . ' lettres bien placées' . ' et ' . $game->getEssai($i)->getMp() . ' lettres mal placées <br>';
                        }
                        $_SESSION['jeu'] = serialize($game);
                    } else {
                        echo 'FELECITATION ' . $_SESSION['nom'] . ' VOUS AVEZ RÉUSSI EN ' . $game->getNbEssais() . ' ESSAIS. <br> BRAVOOOOO !!!!!!!!';
                        unset($_POST);
                        unset($_SESSION);
                        setcookie("nom", "", time() - 1000);
                        setcookie("jeu", "", time() - 1000);
                        unset($_COOKIE);
                        session_destroy();
                        ?>
                <button onclick="javascript:window.location.reload()">Rejouer</button>
                        <?php
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