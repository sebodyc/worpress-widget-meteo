<?php


class Meteo_Icon
{
    public $_weather;


    function __construct($argument)
    {
        if($argument >= 2 && $argument <= 5){$this->_weather ="a"; }
        elseif ($argument >= 6 && $argument <= 7) {$this->_weather ="b";}
        elseif ($argument >= 40 && $argument <= 48 || $argument >= 10 && $argument <= 12 || $argument >= 210 && $argument <= 212 )
        {$this->_weather ="c";}
        elseif ($argument >= 13 && $argument <= 15){$this->_weather ="d";}
        elseif ($argument >= 30 && $argument <= 32 || $argument >= 231 && $argument <= 235){$this->_weather ="e";}
        elseif ($argument >= 60 && $argument <= 68){$this->_weather ="f";}
        elseif ($argument >= 70 && $argument <= 78){$this->_weather ="g";}
        elseif ($argument >= 100 && $argument <= 142){$this->_weather ="h";}

    }
}






 ?>
