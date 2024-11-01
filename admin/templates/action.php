<div class="thsa_qg_wrapper">
    <div class="thsa_qg_inner thsa_qg_action">
        <?php if( isset( $params['has'] ) ): ?>
        <p>
            <a href="javascript:void(0);" data-id="<?php echo esc_attr( $params['id'] ); ?>" class="button button-primary widefat thsa_qg_send_email"><?php esc_html_e('Preview and send quotation','thsa-quote-generator'); ?></a>
        </p>

        <?php $this->pro_features(null, 'edit-email'); ?>

        <?php else: ?>
            <p><center><?php esc_html_e('No action is available', 'thsa-quote-generator'); ?></center></p>
        <?php endif; ?>
    </div>
</div>
<div class="thsa_qg_preview_email">
    <div class="thsa_qg_preview_email_content"><span class="thsa_qg_loader"><?php esc_html_e('Please wait...','thsa-quote-generator'); ?></span>
        <div class="thsa_qg_preview_email_content_get"></div>
        <div class="thsa_qg_preview_email_content_footer">
            <input type="button" data-id="<?php echo esc_attr( $params['id'] ); ?>" class="button button-primary" id="thsa_qg_send_email_last" value="<?php esc_attr_e('Send','thsa-quote-generator'); ?>"> <input type="button" class="button button-secondary" id="thsa_qg_close_preview" value="<?php esc_attr_e('Close','thsa-quote-generator'); ?>">
        </div>
    </div>
</div>
<div class="thsa_qg_manage_content">
    <div class="thsa_qg_manage_content_content">
        <div class="thsa_qg_manage_content_content_get">
            <?php
                $args  = ['media_buttons' => true, 'editor_height' => 580 ];
                wp_editor( $params['email_content'], 'thsaqgmanageemailcontent', $args ); 
            ?>
        </div>
        <div class="thsa_qg_manage_content_content_footer">
            <input type="button" data-id="<?php echo esc_attr( $params['id'] ); ?>" class="button button-primary" id="thsa_qg_manage_content_last" value="<?php esc_attr_e('Save','thsa-quote-generator'); ?>"> <input type="button" class="button button-secondary" id="thsa_qg_close_manage_content" value="<?php esc_attr_e('Close','thsa-quote-generator'); ?>"> <span class="thsa_qg_response thsa_qg_manange_email_con"><?php esc_html_e('Changes has been saved', 'thsa-quote-generator'); ?></span>
        </div>
    </div>
</div>