<?php
/**
 * Restrict user to select different currency if already has any 
 * Processing order.
 *
 * $param array $posted Contain fields filled in checkout form
 * @return void
 */
function yasglobal_checkout_validation( $posted ) {
    // Get Currency of WooCommerce
    $currency = get_woocommerce_currency();
    if ( ! empty( $currency ) ) {
        // Get user id for logged in user otherwise 0
        $user_id = get_current_user_id();
        // Check user is logged in or not
        if ( 0 == $user_id ) {
            // If not logged in then get Billing Email Address and load user
            $checkout_email = $posted['billing_email'];
            $user = get_user_by( 'email', $checkout_email );

            // If user not found then return
            if ( ! isset( $user->ID ) ) {
                return;
            }
            $user_id = $user->ID;
        }

        if ( ! empty( $user_id ) && is_numeric( $user_id ) ) {
            $customer_orders = get_posts( array(
                'numberposts' => -1,
                'meta_key'    => '_customer_user',
                'meta_value'  => $user_id,
                'post_type'   => wc_get_order_types(),
                'post_status' => array( 'wc-processing' ),
                'orderby'     => 'ID',
                'order'       => 'ASC',
            ) );
            if ( ! empty( $customer_orders )
                && isset( $customer_orders[0]->ID ) ) {
                global $wpdb;

                $sql_query = "
                    SELECT postmeta.`meta_value` AS currency
                    FROM $wpdb->postmeta postmeta
                    WHERE postmeta.`post_id` = " . $customer_orders[0]->ID ."
                        AND postmeta.`meta_key` = '_order_currency'";
                $prev_currency = $wpdb->get_row( $sql_query );
                if ( isset( $prev_currency->currency )
                    && ! empty( isset( $prev_currency->currency ) ) ) {
                    if ( $prev_currency->currency !== $currency ) {
                        wc_add_notice( 'You are not allowed to checkout additional orders in a different currency. Please change the currency.', 'error' );
                    }
                }
            }
        }
    }
}
add_action( 'woocommerce_after_checkout_validation', 'yasglobal_checkout_validation' );
