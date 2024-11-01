<?php

    namespace SoulCity;

    class SoulCityIframeMeta {
        /**
         * Hook into the appropriate actions when the class is constructed.
         */
        public function __construct() {
            wp_enqueue_script('soul-city-map', plugin_dir_url( __FILE__ ) . '/assets/js/soul-city-map.js', array(), '1.0.6.1', true);
            add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
            add_action( 'save_post', array( $this, 'save' ) );
        }

        /**
         * Adds the meta box container.
         */
        public function add_meta_box( $post_type ) {
            $post_types = array('post');   //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
                add_meta_box(
                    'some_meta_box_name'
                    ,__( 'Soul City Iframe', 'soul-city-iframe' )
                    ,array( $this, 'render_meta_box_content' )
                    ,$post_type
                    ,'normal'
                    ,'default'
                );
            }
        }

        /**
         * Save the meta when the post is saved.
         *
         * @param int $post_id The ID of the post being saved.
         */
        public function save( $post_id ) {

            /*
             * We need to verify this came from the our screen and with proper authorization,
             * because save_post can be triggered at other times.
             */

            // Check if our nonce is set.
            if ( ! isset( $_POST['soul_city_iframe_inner_custom_box_nonce'] ) )
                return $post_id;

            $nonce = $_POST['soul_city_iframe_inner_custom_box_nonce'];

            // Verify that the nonce is valid.
            if ( ! wp_verify_nonce( $nonce, 'soul_city_iframe_inner_custom_box' ) )
                return $post_id;

            // If this is an autosave, our form has not been submitted,
            // so we don't want to do anything.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
                return $post_id;

            // Check the user's permissions.
            if ( 'page' == $_POST['post_type'] ) {

                if ( ! current_user_can( 'edit_page', $post_id ) )
                    return $post_id;

            } else {

                if ( ! current_user_can( 'edit_post', $post_id ) )
                    return $post_id;
            }

            /* OK, its safe for us to save the data now. */

            // Sanitize the user input.
            $mydata = sanitize_text_field( $_POST['soul_city_iframe_show_iframe_field'] );
            // Update the meta field.
            update_post_meta( $post_id, '_show_iframe', $mydata );


            // Sanitize the user input.
            $place_about = sanitize_text_field( $_POST['soul_city_iframe_place_field'] );
            // Update the meta field.
            update_post_meta( $post_id, '_place_about', $place_about );

            // Sanitize the user input.
            $place_about = sanitize_text_field( $_POST['soul_city_iframe_place_id_field'] );
            // Update the meta field.
            update_post_meta( $post_id, '_place_id', $place_about );

        }


        /**
         * Render Meta Box content.
         *
         * @param WP_Post $post The post object.
         */
        public function render_meta_box_content( $post ) {

            // Add an nonce field so we can check for it later.
            wp_nonce_field( 'soul_city_iframe_inner_custom_box', 'soul_city_iframe_inner_custom_box_nonce' );

            // Use get_post_meta to retrieve an existing value from the database.
            $value = get_post_meta( $post->ID, '_show_iframe', true );

            if( $value == true ) {
                $checked = "checked";
            } else {
                $default = esc_attr( get_option('show_iframe_default') );
                if( $default != "" ) {
                    $checked = "checked";
                } else {
                    $checked = "";
                }
            }

            // Display the form, using the current value.
            echo '<label for="soul_city_iframe_show_iframe_field">';
            _e( 'Show the Soul City Iframe on this post', 'soul_city_iframe' );
            echo '</label> <br>';
            echo '<input type="checkbox" id="soul_city_iframe_show_iframe_field" name="soul_city_iframe_show_iframe_field" value="yes" ' . $checked . ' /><br><br>';

            $about_value = get_post_meta( $post->ID, '_place_about', true );
            $id_value = get_post_meta( $post->ID, '_place_id', true );

            echo '<label for="soul_city_iframe_place_field">';
            _e( 'What this post is talking about ?', 'soul_city_iframe' );
            echo '</label> <br>';
            echo '<input type="text" id="soul_city_iframe_place_field" name="soul_city_iframe_place_field"  value="' . esc_attr( $about_value ) . '" />';
            echo '<div id="soul_city_iframe_map"></div>';
            echo '<input class="hidden" type="text" id="soul_city_iframe_place_id_field" name="soul_city_iframe_place_id_field"  value="' . esc_attr( $id_value ) . '" />';
        }
    }
