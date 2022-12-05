<?php
/* 
Plugin Name: UKM Statistikk
Plugin URI: http://www.ukm.no
Description: UKM Norge statistikk
Author: UKM Norge / Kushtrim Aliu
Version: 1.0 
Author URI: http://www.ukm.no
*/

// use UKMNorge\Arrangement\Arrangement;
// use UKMNorge\Arrangement\Videresending\Mottaker;
// use UKMNorge\Innslag\Personer\Person;
// use UKMNorge\Meta\Write as WriteMeta;
// use UKMNorge\Sensitivt\Intoleranse;

require_once('UKM/Autoloader.php');


class UKMstatistikk extends UKMNorge\Wordpress\Modul
{
    public static $action = 'statistikkTemplate';
    public static $path_plugin = null;

    /**
     * Initier Videresending-objektet
     *
     **/
    public static function init($plugin_path)
    {
        parent::init($plugin_path);
    }

    /**
     * Hooker modulen inn i Wordpress
     *
     * @return void
     */
    public static function hook()
    {
        add_action('network_admin_menu', ['UKMstatistikk', 'meny'], 101);
        add_action('wp_ajax_UKMstatistikk_ajax', ['UKMstatistikk', 'ajax']);
    }

    /**
     * Håndterer alle ajax-kall
     *
     * @return void
     */
    public static function ajax()
    {   
        $reques_method = $_SERVER['REQUEST_METHOD'];
        $subAction = $_REQUEST['subaction'];

        try {
            require_once('ajax/' . $subAction . '.ajax.php');

        // Noe gikk galt
        } catch (Exception $e) {
            static::addResponseData('success', false);
            static::addResponseData('message', $e->getMessage());
            static::addResponseData('code', $e->getCode());
        }

        $data = json_encode(static::getResponseData());
        echo $data;
        die();
    }

    /**
     * Legg til alle scripts som videresendingen bruker
     * 
     * (og ja, det er en del!)
     *
     * @return void
     */
    public static function scripts_and_styles()
    {   
        wp_enqueue_script('WPbootstrap3_js');
        wp_enqueue_style('WPbootstrap3_css');

        // style
    	wp_enqueue_style('UKMstatistikkStyle', plugin_dir_url(__FILE__) . '/style/style.css');
    	wp_enqueue_style('UKMstatistikkTabs', plugin_dir_url(__FILE__) . '/style/tabs.css');

        wp_enqueue_script('mainUKMstatistikkJS', plugin_dir_url(__FILE__) . '/dist/build.js','','',true);
    }

    /**
     * Registrer menyer
     *
     **/
    public static function meny()
    {
        $page = add_menu_page(
            'UKM Norge Statistikk', 
            'Statistikk', 
            'superadmin', 
            'UKMstatistikk',
            ['UKMstatistikk','renderAdmin'], 
            'dashicons-welcome-view-site',
            401
        );
        add_action( 
            'admin_print_styles-' . $page, 
            ['UKMstatistikk', 'scripts_and_styles']
        );
    }
}


UKMstatistikk::init(__DIR__);
UKMstatistikk::hook();
