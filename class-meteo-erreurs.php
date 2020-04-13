<?php

/*
 cette classe va recuperer les entetes envoyes par le serveur en réponse à une requete HTTP de lapi meteoicones
 si il y a des codes erreurs elle va
 les stoker dans $_error
 */

class Meteo_Erreurs{

     public $_error;


    function __construct($url){

        //recuperation des entetes et stokage de celle qui nous interresse dans $code
        $header = get_headers($url);
        $code = substr($header[0], 9, 3);
        //une condition qui stoke le message erreur dans $_error si lerreur repertoriee est presente
        switch ($code){

            case '401':
                $this->_error ="le token est erronné";
                break;

            case '404':
                $this->_error ="url inconue";
                break;

            case '400':
                $this->_error =" ville inconue";
                break;

            case '500':
                $this->_error ="error serveur";
                break;

            case '503':
                $this->_error = "api momentanement indisponible";
                break;

            default:
                // code...
                break;
        }
    }
}


 ?>
