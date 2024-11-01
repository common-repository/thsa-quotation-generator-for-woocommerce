<div class="thsa_qg_col-6">
    <?php if(isset($params['from_email'])): ?>
        <p><a href="<?php echo esc_url($params['checkout_url']); ?>" <?php if( isset( $params['from_email']) ): ?> style="padding: 10px 20px; background: #f2f2f2; border: 1px solid #CCC; margin: 20px 0;" <?php endif; ?>><?php esc_html_e('Proceed to checkout','thsa-quote-generator'); ?></a></p>
    <?php else: ?>
        <input type="button" data-q-id="<?php esc_attr_e($params['qid'],'thsa-quote-generator'); ?>" class="thsa_qg_proceed_checkout" value="<?php esc_attr_e('Proceed to checkout','thsa-quote-generator'); ?>">
    <?php endif; ?>
</div>