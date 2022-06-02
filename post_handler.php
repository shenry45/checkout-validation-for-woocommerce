<?php 
    /*-----
    HANDLES POST INFO FROM FORMS
    -----*/

    function cvfwoo_post_sanitize() {
        // removes before and after whitespace also filtering off HTML
        if ( isset( $_POST ) && !isset( $_POST['delete_filter'] ) ) {
            foreach( $_POST as $key=>$value ) {
                $value = sanitize_text_field( $value );
                
                if ( $value === "" ) {
                    cvfwoo_fail_notice( "You have entered an invalid entry." );
                    $_POST = [];
                    break;
                } else {
                    $_POST[$key] = $value;
                }
            }
        }

        return cvfwoo_post_handler();
    }

    function cvfwoo_post_handler() {
        $cur_filters = get_option( 'cvfwoo_filters' );

        // FORM SUBMISSION HANDLER
        if( isset( $_POST['cvfwoo_new_filter'] ) ) {
            // ADD NEW FILTER TO DB
            $new_filter = sanitize_text_field( $_POST['cvfwoo_new_filter'] );

            array_push( $cur_filters, strtolower( $new_filter ) );

            update_option( 'cvfwoo_filters', $cur_filters );

            if ( $cur_filters === get_option( 'cvfwoo_filters' ) ) {
                cvfwoo_success_notice();
            } else {
                cvfwoo_fail_notice( "Something went wrong adding in your new filter." );
            }
        } elseif ( isset( $_POST['cvfwoo_notice'] ) ) {
            // UPDATE FAILED WOO CHECKOUT NOTICE
            $new_notice = sanitize_text_field( $_POST['cvfwoo_notice'] );

            update_option( 'cvfwoo_notice', $new_notice );

            if ( $new_notice === get_option( 'cvfwoo_notice' ) ) {
                cvfwoo_success_notice();
            } else {
                cvfwoo_fail_notice( "Something went wrong updating the notice." );
            }
        } elseif ( isset( $_POST['delete_filter'] ) ) {
            // DELETE FILTER FROM DB
            $needle = sanitize_text_field( $_POST['submit'] );
            $needle_pos = array_search( $needle, $cur_filters );
            
            if ( $needle_pos > -1 ) {                
                if ( in_array( $needle, $cur_filters ) ) {
                    unset( $cur_filters[$needle_pos] );
                    update_option( 'cvfwoo_filters', $cur_filters );
                    cvfwoo_success_notice();
                } else {
                    cvfwoo_fail_notice("The submitted filter does not exist.");
                }
            }
        }

        // DISPLAY HTML
        return cvfwoo_settings_page_html( $cur_filters );
    }

    function cvfwoo_success_notice() {
        ?>
        <div class="notice notice-success is-dismissable">
            <p>Settings saved.</p>
        </div>
        <?php
    }

    function cvfwoo_fail_notice( $msg ) {
        ?>
        <div class="notice notice-error is-dismissable">
            <p><?php esc_html_e( $msg ); ?></p>
        </div>
        <?php
    }

?>