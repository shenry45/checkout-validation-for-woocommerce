<?php

    /**
     * Plugin Name:     Checkout Validation for WooCommerce
     * Plugin URI:      https://github.com/shenry45/WooCommerce-Custom-Validation
     * Description:     Custom filters and validation notice for WooCommerce checkout.
     * Version:         1.0.0
     * Author:          Shawn Henry
     * Author URI:      http://shawnphenry.com/
     * License:         GPLv2 or later
     * License URI:     http://www.gnu.org/licenses/gpl-2.0.html
     **/

    /* PLUGIN OPTIONS */

    require( 'checkout_validate.php' );
    require( 'post_handler.php' );

    register_activation_hook( __FILE__, 'cvfwoo_activate_options' );
    register_uninstall_hook( __FILE__, 'cvfwoo_uninstall_options' );

    function cvfwoo_activate_options() {
        // add options if not already found
        if ( !get_option( 'cvfwoo_filters' ) ) {
            add_option( 'cvfwoo_filters', array('') );
        }

        if ( !get_option( 'cvfwoo_notice' ) ) {
            add_option( 'cvfwoo_notice', 'You order has been blocked. Please contact the site owner for assistance.' );
        }
    }

    function cvfwoo_uninstall_options() {
        delete_option( 'cvfwoo_filters' );
        delete_option( 'cvfwoo_notice' );
    }

    /* BACKEND PAGE */
    function cvfwoo_add_settings_page() {
        // add_options_page( 'WooCommerce Checkout Validation', 'WooCommerce Checkout Validation', 'manage_woocommerce', 'cvfwoo', 'cvfwoo_post_sanitize', 5 );
        add_submenu_page( 'woocommerce', 'WooCommerce Checkout Validation', 'Checkout Validation', 'manage_options', 'cvfwoo', 'cvfwoo_post_sanitize', 5 );
    }
    add_action( 'admin_menu', 'cvfwoo_add_settings_page' );

    function cvfwoo_settings_page_html( $filters ) {
        ?>

        <h1>WooCommerce Checkout Validation</h1>
        <form action="#" method="post">
            <h2>WooCommerce Fail Notice</h2>
            <p>Enter in your custom failure notice that will be shown at checkout.</p>
            <label style="display:block;">Notice</label>
            <input id="cvfwoo_notice" name="cvfwoo_notice" type="text" value="<?php esc_attr_e( get_option( 'cvfwoo_notice', '' ) ); ?>" />
            <input name="submit" type="submit" value="Update Notice" style="display:block;margin:20px 0px;" />
        </form>

        <hr>

        <form action="#" method="post">
            <h2>Add Custom Filter</h2>
            <p>Enter in your custom filter value. Both upper and lowercase characters are checked the same. No HTML will be accepted.</p>
            <label for="cvfwoo_new_filter">Value</label>
            <input id="cvfwoo_new_filter" name="cvfwoo_new_filter" type="text" minlength="2" style="display:block;" />
            <input name="submit" type="submit" value="Add Filter" style="display:block;margin:20px 0px;" />
        </form>

        <hr>

        <form action="#" method="post">
            <h2>Current Filters</h2>
            <p>Click any current filter to remove it from the database.</p>
            <p>*All filters show lowercase but uppercase will also be filtered out.</p>
            <ul>
                <?php
                $sorted_cur_filters = asort( $filters);
                foreach ( $filters as $filter ) {
                    ?>
                    <li style="display:inline;">
                        <input name="submit" type="submit" value="<?php esc_html_e( $filter ) ?>" />
                    </li>
                    <?php
                }
                ?>
            </ul>
            <input name="delete_filter" style="display:none" />
        </form>
    
        <?php
    }

?>