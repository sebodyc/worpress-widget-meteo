<?php

/*
Plugin Name: meteo
Plugin URI:
Description: Un pluggin meteo qui permet a  l-utilisateur de choisir sa ville
le pluggin montre la temperature min ,max une description du temps et une icone meteo sur deux jours
Author: sebodyc
Version: 1.0.0
*/


//ajout de la fonction pour load le css
function load_plugin_css()
    {
        wp_enqueue_style( 'stylemet', plugin_dir_url( __FILE__ ) . 'css/stylemet.css',array(),'1.0.0','all' );
    }
add_action( 'wp_enqueue_scripts', 'load_plugin_css' );





add_action( 'widgets_init' , 'meteo_init' );

//j'inclus les classe que j'ai besoin pour utiliser ce pluggin

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'meteo/class-meteo-erreurs.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'meteo/class-set-meteo.php';



function meteo_init(){

        register_widget("widgetMeteo");
    }


    class widgetMeteo extends WP_Widget{

        // Constructeur du widgets
        function widgetMeteo(){

            parent::WP_Widget('AAFmet', $name = 'ma meteo', array('description' => 'Affichage de la meteo des villes'));
        }

        //  Mise en forme
        function widget($args,$instance){


        //je recupere le resultat de recherche et le token et je les insere dans ma requette
            $search = $_POST["ville"];

        //si la recherche nets pas renseignee je met une valeur de base
        //si elle est renseignee je la met en memoire via updateoption
            if (!isset($search)){$search = 13015;}
            else {update_option('town_preferency', $search);}

        // get an option
            $search = get_option('town_preferency');
            $token_api = $instance['token'];
            $url='https://api.meteo-concept.com/api/location/cities?token='.$token_api.'&search='.$search;
            $data = file_get_contents('https://api.meteo-concept.com/api/location/cities?token='.$token_api.'&search='.$search);
        //je dejisonise le resultat pour avoir acces au nom de la ville plus a linsee pour la prochaine requette
        //la deuxieme requette forecast ne marche que avec le num insee et rien d autre
            $cities = json_decode($data)->cities;

            foreach ($cities as $val){

                //je recupere linsee de la ville demandee et son nom
                    $insee = $val->insee;
                    $town_name = $val->name;
                    $town_cp = $val->cp;
                }


            //ici je set linsee pour savoir les info

            $data1 = file_get_contents('https://api.meteo-concept.com/api/forecast/daily?token='.$token_api.'&insee='.$insee);

            //je creer une instance de la classe set meteo pour recuperer les donnees
            //le premier parametre cest l url et le second c est lindex correspondant au jours
            // 0= le jour meme 1= le lendemain 2=le surlendemain ...
            // jutilise la fct plugin dir url qui me permet davoir le chemin du fichier nimporte ou sur le site

            $meteodujour= new Set_Meteo($data1,0);
            $tmax = $meteodujour->_tmax;
            $tmin = $meteodujour->_tmin;
            $weather_description= $meteodujour->_weather;
            $weather_icon = plugin_dir_url( __FILE__ ).$meteodujour->_weather_icon;

            $meteodedemain= new Set_Meteo($data1,1);
            $tmax1 = $meteodedemain->_tmax;
            $tmin1 = $meteodedemain->_tmin;
            $weather_description1= $meteodedemain->_weather;
            $weather_icon1 = plugin_dir_url( __FILE__ ). $meteodedemain->_weather_icon;

            //je recupere les erreurs si erreur il y a
            $set_error= new Meteo_Erreurs($url);
            $error= $set_error->_error;

            echo $args['before_widget'];

            // je creer un formulaire qui permet de recuperer la ville par son nom ou code postal
?>


    <form  action="" method="post">
        <label for="name">ville ou code postal ou insee</label>
        <input type="text" id="ville" name="ville" required>
    </form>

<?php

            // j affiche les info dans mon widget
            //si jamais la variable erreur contien une erreur je l'affiche

            if (isset($error)){echo '<h3> Attention'.$error.'</h3>';}


            echo '<h2> Prevision méteo pour ' .$town_name.' '.$town_cp.'</h2>';
            echo '<div class="hh">
                     <p> Aujourd\'hui T°Max:  ' .$tmax.'°C  , T°Min: ' .$tmin.'°C </p>
                     <p>'.$weather_description.'</p>
                     <img src="'.$weather_icon.'" alt "'.$weather_description.'" >
                  </div>
                  <div>
                     <p> Demain T°Max:  ' .$tmax1.'°C  , T°Min: ' .$tmin1.'°C</p>
                     <p>'.$weather_description1.'</p>
                     <img src="'.$weather_icon1.'" alt "'.$weather_description1.'" >
                  </div>';


            echo $args['after_widget'];

    }

    // Récupération des paramètres
    function update($new_instance, $old_instance){

        $instance = $old_instance;
        //Récupération des paramètres envoyés
        $instance['token'] = strip_tags($new_instance['token']);
        return $instance;

    }

    // Paramètres dans l’administration
    function form($instance){

        // Etape 1 - Définition de variable token
        $token = esc_attr($instance['token']);
        // Etape 2 - Ajout du champs
?>

    <p>
    <label for="<?php echo $this->get_field_id('token'); ?>">
    <?php echo 'Token:'; ?>
    <input class="widefat" id="<?php echo $this->get_field_id('$token'); ?>" name="<?php echo $this->get_field_name('token'); ?>" type="text" value="<?php echo $token; ?>" />
    </label>
    </p>

<?php

    }

// Fin du widget
    }

?>
