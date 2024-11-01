<?php

    namespace SoulCity;

    class SoulCityIframeOption {


        /**
         * SoulCityIframeOption constructor.
         */
        public function __construct() {
            if($_GET['page'] == 'soul_city_iframe-settings'){
                wp_enqueue_script('soul-city-option', plugin_dir_url( __FILE__ ) . '/assets/js/soul-city-option.js', array(), '1.0.6.1', true);
            }
            add_action('admin_menu', array($this, 'soul_city_iframe_menu'));
            add_action( 'admin_init', array($this, 'soul_city_iframe_settings') );
        }

        public function soul_city_iframe_menu(){
            add_menu_page('Soul City Iframe', 'Soul City Iframe', 'administrator', 'soul_city_iframe-settings', array($this, 'soul_city_iframe_settings_page'), 'dashicons-admin-generic');
        }

        function soul_city_iframe_settings() {
            //register_setting( 'soul_city_iframe-settings-group', 'iframe_language' );
            register_setting( 'soul_city_iframe-settings-group', 'show_iframe_default' );
        }

        function soul_city_iframe_settings_page() {
            ?>
            <div class="wrap">
                <h2>Soul City Iframe</h2>

                <form method="post" action="options.php">
                    <?php settings_fields( 'soul_city_iframe-settings-group' ); ?>
                    <?php do_settings_sections( 'soul_city_iframe-settings-group' ); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <!--<th scope="row"><?php /*echo __('Language', 'soul-city-iframe'); */?></th>
                        <?php
                                /*                            $selected_anguage = esc_attr( get_option('iframe_language') );
                                                        */?>
                        <td>
                            <select name="iframe_language">
                                <option value="english" <?php /*echo $selected_anguage == 'english' ? 'selected' : '' */?>>English</option>
                                <option value="french" <?php /*echo $selected_anguage == 'french' ? 'selected' : '' */?>>Français</option>
                            </select>
                        </td>-->
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php echo __('Enable for all post', 'soul-city-iframe'); ?></th>
                            <?php
                                $value = esc_attr( get_option('show_iframe_default') );
                                if( $value != "" ) {
                                    $checked = "checked";
                                } else {
                                    $checked = "";
                                }
                            ?>
                            <td>
                                <input type="checkbox" name="show_iframe_default" value="yes" <?php echo $checked ?>/>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>

                <div class="">
                    <p><?php echo __('Voting Statistics'); ?></p>
                    <table class="table-stats wp-list-table striped">
                        <thead>
                        <tr>
                            <th><?php echo __('Blog Post', 'soul-city-iframe'); ?></th>
                            <th><?php echo __('Total Vote', 'soul-city-iframe'); ?></th>
                            <th><?php echo __('Vote per Feeling', 'soul-city-iframe'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <script>
                        var blogName = '<?php echo get_bloginfo('name'); ?>';
                    </script>
                </div>
            </div>
            <?php
        }
    }
