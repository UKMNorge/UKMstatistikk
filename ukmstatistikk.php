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
        add_action('wp_ajax_UKMstatistikk_ajax', ['UKMstatistikk', 'ajax']);
        add_action('admin_menu', ['UKMstatistikk', 'meny'], 101);
    }

    /**
     * Håndterer alle ajax-kall
     *
     * @return void
     */
    public static function ajax()
    {
        if (is_array($_POST)) {
            static::addResponseData('POST', $_POST);
        }

        try {
            $supported_actions = [
                'filmerAjax',
            ];

            if (in_array($_POST['subaction'], $supported_actions)) {
                static::setupLogger();
                require_once('ajax/' . $_POST['subaction'] . '.ajax.php');
            } else {
                throw new Exception('Beklager, støtter ikke denne handlingen!');
            }
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
    public static function script()
    {
        wp_enqueue_script('WPbootstrap3_js');
        wp_enqueue_style('WPbootstrap3_css');
        wp_enqueue_script('TwigJS');
        // wp_enqueue_script('VUEjs', plugin_dir_url(__FILE__) . 'ukmvideresending.js');

        // Fra Webpack, legger til i footer
        // wp_enqueue_script('mainUKMstatistikkJS',plugin_dir_url(__FILE__) . '/build/index.js','','',true);
        echo '<script type="module" src="https://ukm.dev/2023-deatnu-tana-deatnu-tana-sorelv/wp-content/plugins/UKMstatstikk//build/index.js?ver=5.3.14"></script>';
    }

    /**
     * Registrer menyer
     *
     **/
    public static function meny()
    {
        add_action(
            'admin_print_styles-' .
                add_menu_page(

                    'Statistikk',
                    'Statistikk',
                    'editor',
                    'UKMstatistikk',
                    ['UKMstatistikk', 'renderAdmin'],
                    'dashicons-editor-removeformatting', #'//ico.ukm.no/paper-airplane-20.png',
                    90
                ),
            ['UKMstatistikk', 'script']
        );
    }
}


UKMstatistikk::init(__DIR__);
UKMstatistikk::hook();
