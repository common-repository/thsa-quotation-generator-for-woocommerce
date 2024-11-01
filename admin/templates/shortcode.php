<div class="thsa_qg_wrapper">
    <div class="thsa_qg_inner thsa_qg_action thsa_qg_shortcodes_">
        <p><b><?php esc_html_e( 'Quotation Table', 'thsa-quote-generator' ); ?></b><br/><?php echo ( !empty( $params['shortcode'][0] ) )? esc_html( $params['shortcode'][0] ) : null; ?></p>
        <p><b><?php esc_html_e( 'Checkout Button', 'thsa-quote-generator' ); ?></b><br/><?php echo ( !empty( $params['shortcode'][1] ) )? esc_html( $params['shortcode'][1] ) : null; ?></p>
        <p><?php esc_html_e('Access the shortcodes dynamically by passing the id to the URL instead of assigning it in the shortcode.' , 'thsa-quote-generator'); ?> <b><?php echo esc_html( 'e.g. sample.com/quotation?q_id='.$params['id'] ); ?></b></p>
    </div>
</div>