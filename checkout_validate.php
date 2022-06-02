<?php

    add_action( 'woocommerce_after_checkout_validation', 'cvfwoo_validate', 10, 2);
    
    function cvfwoo_validate( $fields, $errors ){
        $user_values = get_option( 'cvfwoo_filters', array('') );
        $notice = get_option( 'cvfwoo_notice', "One of your fields triggered our firewall!" );
        
        $entry = strtolower( $filter );
        
        // for each filter added, check if filter value found in a field
        foreach ($user_values as $entry) {
            echo $entry;
            
            $entry = strtolower( $entry );
            
            foreach ($fields as $field) {
                // convert to string and lowercase
                $field = strval( $field );
                $field = strtolower( $field );
                
                if ( strpos( $field, $entry ) > -1 ) {
                    echo "Found $entry";
                    $errors->add( 'validation', $notice );
                }
            }
        }
    }

?>