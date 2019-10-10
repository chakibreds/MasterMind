<?php
class MasterMind
{
    private $_secret; //la combinaison secrète ;String
    private $_taille = 0; //la taille du mot secret ;int
    private $_essais = array(); // des instance of Essais
    private $_nbEssais = 0; // nombre d'essais ;int

    public function __construct($taille)
    {
        $this->_taille = $taille;
        for ($i = 0; $i < $taille; $i++) {
            $this->_secret .= rand(0, 9);
        }
        $this->_nbEssais = 0;
        $this->_essais = array();
    }
    public function getTaille()
    {
        return $this->_taille;
    }
    public function getSecret()
    {
        return $this->_secret;
    }
    public function getNbEssais()
    {
        return $this->_nbEssais;
    }
    public function getEssai($i)
    {
        return $this->_essais[$i];
    }
    private function essaiCorrecte($essai)
    {
        if (strlen($essai->getEssai()) == $this->_taille && is_string($essai->getEssai())) {
            for ($i = 0; $i < $this->_taille; $i++) {
                if ($essai->getEssai()[$i] < '0' || $essai->getEssai()[$i] > '9') {
                    return false;
                }
            }
            return true;
        }
    }
    //la fonction qui test un mot avec le mot secret
    public function firstTest($essai)
    {

        if ($this->essaiCorrecte($essai) == true) {
            $this->_essais[$this->_nbEssais] = $essai;
            $this->_essais[$this->_nbEssais]->test($this->_secret);
            $this->_nbEssais++;
        } else {
            echo 'erreur de saisie : ' . $essai->getEssai() . ' n\'est pas acceptable <br>';
        }
    }

    public function test($essai)
    {
        if ($this->essaiCorrecte($essai) == true) {
            
            //if ($this-> getNbEssais() !=0 && $essai->getEssai() != ($this->_essais[$this->_nbEssais - 1]->getEssai())) {
                $this->_essais[$this->_nbEssais] = $essai;
                $this->_essais[$this->_nbEssais]->test($this->_secret);
                $this->_nbEssais++;
           // } else {
                //echo 'raffraichissement <br>';
            //}
        } else {
            echo 'erreur de saisie : ' . $essai->getEssai() . ' n\'est pas acceptable <br>';
        }
    }
    public function setEssais($essai, $i)
    {
        $_essais[$i] = $essai;
    }
    public function isWinner()
    {
        return  $this->getNbEssais() > 0 && ($this->getEssai($this->getNbEssais() - 1)->getEssai() == $this->getSecret());
    }
}


class Essais
{
    private $_bp = 0; //nombre de lettre bien placées
    private $_mp = 0; // nombre de lettre mal placées
    private $_essai = ""; //la chaine d'essai


    public function __construct($essai, $bp, $mp)
    {
        $this->_essai = $essai;
        $this->_bp = $bp;
        $this->_mp = $mp;
    }

    public function getBp()
    {
        return $this->_bp;
    }
    public function getMp()
    {
        return $this->_mp;
    }
    public function getEssai()
    {
        return $this->_essai;
    }

    public function test($secret)
    {
        $tmp = $secret;
        $essai = $this->_essai;
        for ($i = 0; $i < strlen($secret); $i++) {
            if ($essai[$i] == $tmp[$i]) {
                $this->_bp++;
                $essai[$i] = '*';
                $tmp[$i] = '*';
            }
        }
        for ($i = 0; $i < strlen($secret); $i++) {
            for ($j = 0; $j < strlen($secret); $j++) {
                if ($essai[$i] != '*' and $tmp[$j] != '*' and $essai[$i] == $tmp[$j]) {
                    $this->_mp++;
                    $essai[$i] = '*';
                    $tmp[$j] = '*';
                }
            }
        }
    }
}

class Couple
{
    private $_x;
    private $_y;

    public function __construct($x, $y)
    {
        $this->_x = $x;
        $this->_y = $y;
    }
    public function getX()
    {
        return $this->_x;
    }
    public function getY()
    {
        return $this->_y;
    }
}
