<?php

/*
cette classe recupere la reponse specifique de l'appel http a lapi
puis definit des variables

*/

class Set_Meteo{

        public $_weather;
        public $_tmax;
        public $_tmin;
        public $_weather_icon;

        function __construct($request,$day){

            // j inclus le tableau avec les correspondance index description
            require plugin_dir_path( dirname( __FILE__ ) ) . 'meteo/meteo-array.php';
            // je dejisonise la requette avec la meteo
            $cities = json_decode($request)->forecast;
            //je set lindex meteo qui correspond a la description recu dans une variable
            $weather_id = $cities[$day]->{'weather'};


            //ici le [0] correspond au jour meme
            $this->_tmax = $cities[$day]->{'tmax'};
            $this->_tmin = $cities[$day]->{'tmin'};
            $this->_weather = $meteo_array[$weather_id];

            /* creation dune condition qui me permet dassimiler la description de la meteo a une icone
            la condition est un peu longue car il y a beaucoup de cas qui sont assez similaire donc
            jai simplifie */

            if($weather_id >= 1 && $weather_id <= 5){$this->_weather_icon ="image/meteoicones/03d.png"; }
            elseif ($weather_id == 0) {$this->_weather_icon ="image/meteoicones/01d.png";}
            elseif ($weather_id >= 6 && $weather_id <= 7) {$this->_weather_icon ="image/meteoicones/50d.png";}
            elseif ($weather_id >= 40 && $weather_id <= 48 || $weather_id >= 10 && $weather_id <= 12 || $weather_id >= 210 && $weather_id <= 212 )
            {$this->_weather_icon ="image/meteoicones/09d.png";}
            elseif ($weather_id >= 13 && $weather_id <= 15){$this->_weather_icon ="image/meteoicones/09d.png";}
            elseif ($weather_id >= 30 && $weather_id <= 32 || $weather_id >= 231 && $weather_id <= 235){$this->_weather_icon ="image/meteoicones/13d.png";}
            elseif ($weather_id >= 60 && $weather_id <= 68){$this->_weather_icon ="image/meteoicones/13d.png";}
            elseif ($weather_id >= 70 && $weather_id <= 78){$this->_weather_icon ="image/meteoicones/13d.png";}
            elseif ($weather_id >= 100 && $weather_id <= 142){$this->_weather_icon ="image/meteoicones/11d.png";}


        }

}

 ?>
