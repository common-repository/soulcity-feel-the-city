<?php

    /*
    Plugin Name: Soul.City
    Plugin URI: http://blog.soul.city/
    Description: Vote your “feeling” of a place
    Author: Soul.City
    Version: 1.0.6.2
    Author URI: http://blog.soul.city/
    Text Domain: soul-city-iframe
    Domain Path: /lang
    */

    namespace SoulCity;

    class SoulCityIframe {

        public function __construct() {
            include_once plugin_dir_path( __FILE__ ) . '/SoulCityIframeFront.php';
            include_once plugin_dir_path( __FILE__ ) . '/SoulCityIframeOption.php';
            include_once plugin_dir_path( __FILE__ ) . '/SoulCityIframeMeta.php';

            wp_enqueue_style('soul-city-iframe-style', plugin_dir_url( __FILE__ ) . '/assets/css/style.css', array(), '1.0.6.1');

            new SoulCityIframeFront();
            new SoulCityIframeOption();

            if ( is_admin() ) {
                add_action( 'load-post.php', array($this, 'call_SoulCityIframeMeta'));
                add_action( 'load-post-new.php', array($this, 'call_SoulCityIframeMeta'));
            }

            add_action('plugins_loaded', array($this, 'my_plugin_load_plugin_textdomain'));
        }

        public function call_SoulCityIframeMeta() {
            new SoulCityIframeMeta();
        }

        function my_plugin_load_plugin_textdomain() {
            load_plugin_textdomain( 'soul-city-iframe', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
        }
    }

    new SoulCityIframe();

?>
