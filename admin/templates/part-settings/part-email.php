<div class="thsa_qg_email_settings">
    <div class="thsa_qg_email_content">
        <p>
            <label>
                <?php esc_html_e('From Email','thsa-quote-generator'); ?><br/>
                <input type="email" class="thsa_email_set_field thsa_email_set_email" placeholder="Email Address" value="<?php echo esc_attr( $params['email'] ); ?>">
            </label>
        </p>
        <p>
            <label>
                <?php esc_html_e('Email Title','thsa-quote-generator'); ?><br/>
                <input type="text" class="thsa_email_set_field thsa_email_set_title" placeholder="Quotation Email Title" value="<?php echo esc_attr( $params['title'] ); ?>">
            </label>
        </p>
        <br/>
        <div>
            <?php
                $content = html_entity_decode($params['message']);
                $args  = ['media_buttons' => true, 'editor_height' => 400 ];
                wp_editor( $content, 'thsaqgemailcontent', $args ); 
            ?>
            <ul>
                <li><?php echo esc_html( $params['shortcodes'][0].' - ' ); ?><?php esc_html_e('Customer Name', 'thsa-quote-generator'); ?></li>
                <li><?php echo esc_html( $params['shortcodes'][1].' - ' ); ?><?php esc_html_e('Quotation Breakdown', 'thsa-quote-generator'); ?></li>
                <li><?php echo esc_html( $params['shortcodes'][2].' - ' ); ?><?php esc_html_e('Quotation checkout button', 'thsa-quote-generator'); ?></li>
            </ul>
        </div>
        <br/>
        <p>
            <input type="button" class="button button-primary button-large thsa_qg_save_email_settings" value="<?php esc_attr_e('Save Changes','thsa-quote-generator'); ?>">
            <input type="button" class="button button-secondary button-large thsa_qg_reset_email_settings" value="<?php esc_attr_e('Reset','thsa-quote-generator'); ?>">
            <span class="thsa_qg_response thsa_qg_response_email"><?php esc_html_e('Changes has been save','thsa-quote-generator'); ?></span>
        </p>
    </div>
</div>