<?php

    namespace SoulCity;

    class SoulCityIframeFront {


        /**
         * SoulCityIframeFront constructor.
         */
        public function __construct() {
            wp_enqueue_script('soul-city-front', plugin_dir_url( __FILE__ ) . '/assets/js/soul-city-front.js', array('iframeResizer'), '1.0.6.1', true);
            wp_enqueue_script('iframeResizer', plugin_dir_url( __FILE__ ) . '/assets/js/vendor/iframeResizer.js', array('jquery'), '3.5.1', true);
            add_filter ('the_content',  array($this, 'insertSubscribeNewsLetter'));
        }

        function insertSubscribeNewsLetter($content) {
            $post_author_id = $GLOBALS['post']->post_author;
            $user_info = get_userdata($post_author_id);
            $first_name = $user_info->first_name;
            $last_name = $user_info->last_name;

            $post_author = $first_name . ' ' . $last_name;
            if($post_author == " "){
                $post_author = $user_info->user_login;
            }
            $post_id = $GLOBALS['post']->ID;
            $show_iframe = get_post_meta( $post_id, '_show_iframe', true );
            $place_id = get_post_meta( $post_id, '_place_id', true );
            $place_about = get_post_meta( $post_id, '_place_about', true );
            $post_title = $GLOBALS['post']->post_title;
            $post_date = $GLOBALS['post']->post_date_gmt;
            $site_name = get_bloginfo('name');
            $site_domain = get_bloginfo('url');

            if(is_single() && $show_iframe) {
                $content.= '<iframe  data-post-title="' . $post_title . '" data-post-author="' . $post_author . '" data-post-date="' . $post_date . '" data-site-name="' . $site_name . '" data-site-domain="' . $site_domain . '" data-place-id="' . $place_id . '" data-place-name="' . $place_about . '" class="iframe-soul-city" frameborder="0" scrolling="no" style="width: 100%" src="http://soul-city.dev/app_dev.php/survey/add"></iframe>';
            }
            return $content;
        }
    }