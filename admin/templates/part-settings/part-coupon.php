<div class="thsa_qg_email_settings">
        <p>
            <?php $status = null;
                if( isset($params['settings']['individual_usage']) ){
                    if( $params['settings']['individual_usage'] == 'yes' ){
                        $status = 'checked';
                    }
                }else{
                    $status = null;
                }
            ?>
            <label><input type="checkbox" class="thsa_qg_settings_individual_use" <?php echo esc_attr($status); ?>> <?php esc_html_e('Individual Use', 'thsa-quote-generator'); ?></label>
        </p>
        <p>
            <?php 
                $product_ids = ( isset($params['settings']['product_ids']) )? $params['settings']['product_ids'] : null; 
                $product_ids = explode(',', $product_ids);
            ?>
            <label><?php esc_html_e('Products', 'thsa-quote-generator'); ?><br/>
                <select class="thsa_qg_settings_coupon_pids thsa_qg_select_woo_inline_half" multiple>
                    <?php foreach($params['products'] as $product): ?>
                        <option value="<?php echo esc_attr( $product['id'] ); ?>"><?php echo esc_html( $product['title'] ); ?></option>
                        <?php if(in_array($product['id'], $product_ids)): ?>
                            <option value="<?php echo esc_attr( $product['id'] ); ?>" selected><?php echo esc_html( get_the_title($product['id']) ); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <p>
            <?php 
                $exclude_ids = ( isset( $params['settings']['exclude_ids'] ) )? $params['settings']['exclude_ids'] : null; 
                $exclude_ids = explode( ',', $exclude_ids );
            ?>
            <label><?php esc_html_e('Exclude Products', 'thsa-quote-generator'); ?><br/>
                <select class="thsa_qg_settings_coupon_exclude_pids thsa_qg_select_woo_inline_half" multiple>
                    <?php foreach($params['products'] as $product): ?>
                        <option value="<?php echo esc_attr( $product['id'] ); ?>"><?php echo esc_html( $product['title'] ); ?></option>
                        <?php if( in_array( $product['id'], $exclude_ids ) ): ?>
                            <option value="<?php echo esc_attr( $product['id'] ); ?>" selected><?php echo esc_html( get_the_title( $product['id']) ); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <p>
            <?php $usage_limit = ( isset( $params['settings']['usage_limit'] ) )? $params['settings']['usage_limit'] : null;  ?>
            <label><?php esc_html_e('Usage Limit','thsa-quote-generator'); ?><br/>
                <input type="number" class="thsa_email_set_field thsa_qg_settings_usage_limit" placeholder="1" value="<?php echo esc_attr( $usage_limit ); ?>">
            </label>
        </p>
        <p>
            <?php $expiry_date = ( isset( $params['settings']['expiry_date'] ) )? $params['settings']['expiry_date'] : null;  ?>
            <label><?php esc_html_e('Expiry Date','thsa-quote-generator'); ?><br/>
                <input type="date" class="thsa_email_set_field thsa_qg_settings_expiry_date" value="<?php echo esc_attr( $expiry_date ); ?>">
            </label>
        </p>
        <p>
            <?php $expiry_date = null;
                if(isset($params['settings']['before_tax'])){
                    if($params['settings']['before_tax'] == 'yes'){
                        $expiry_date = 'checked';
                    }
                }else{
                    $expiry_date = 'checked';
                }
            ?>
            <label><input type="checkbox" class="thsa_qg_settings_apply_before_tax" <?php echo esc_html($expiry_date); ?>> <?php esc_html_e('Apply Before Tax', 'thsa-quote-generator'); ?></label>
        </p>
        <p>
            <?php $free_shipping = null;
                if( isset( $params['settings']['free_shipping'] ) ){
                    if( $params['settings']['free_shipping'] == 'yes' ){
                        $free_shipping = 'checked';
                    }
                }else{
                    $free_shipping = null;
                }
            ?>
            <label><input type="checkbox" class="thsa_qg_settings_free_shipping" <?php echo esc_attr( $free_shipping ); ?>> <?php esc_html_e('Free Shipping', 'thsa-quote-generator'); ?></label>
        </p>
        <br/>
        <p>
            <input type="submit" class="button button-primary thsa_qg_settings_save_coupon" value="<?php esc_html_e( 'Save Changes', 'thsa-quote-generator' ); ?>"> 
            <span class="thsa_qg_response thsa_qg_coupon_message"><?php esc_html_e('Changes has been save','thsa-quote-generator'); ?></span>
        </p>
</div>