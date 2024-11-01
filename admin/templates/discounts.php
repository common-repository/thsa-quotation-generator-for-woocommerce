<div class="thsa_qg_wrapper">
    <div class="thsa_qg_inner">
        <h3><?php esc_html_e('Discounts', 'thsa-quote-generator'); ?></h3>
        <div class="thsa_qg_table_con">
            
        <div><div class="thsa_qg_notices thsa_qg_discount_notice no_margin"><span class="dashicons dashicons-info"></span> <?php esc_html_e('No subscription plugin is detected') ?> <a href="javascript:void(0);" class="thsa_qg_see_supported">see supported plugins</a></div></div>

            <label class="main_label"><?php esc_html_e('Payment Type','thsa-quote-generator'); ?></label>
            <table border="0" class="thsa_qg_discount_table1">
                <?php $payment_type = (isset($params['data']['payment_type']))? $params['data']['payment_type'] : null; ?>
                <tr>
                    <td>
                        <select class="thsa_qg_payment_type widefat" name="thsa_qg_payment_type">
                            <option value="upfront" <?php echo ($payment_type == 'upfront')? 'selected' : null; ?>><?php esc_html_e('Upfront Payment', 'thsa-quote-generator'); ?></option>
                            <?php $this->pro_features( $payment_type, 'payment-plan'); ?>
                        </select>
                    </td>

                    <?php $this->pro_features( $payment_type, 'payment-plan-settings'); ?>

                    <td>
                        <?php 
                            $selected = 'checked';
                            if(isset($params['data']['allow_payment_type_edit'])){
                                $selected = ($params['data']['allow_payment_type_edit'] == 'Y')? 'checked' : null;
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php $this->pro_features( $params['data'], 'payment-plan-settings-area' ); ?>

        <div class="thsa_qg_table_con">
            <label class="main_label"><?php esc_attr_e('Discount Value','thsa-quote-generator'); ?></label>
            <table border="0" class="thsa_qg_discount_table2">
                <tr>
                    <td valign="middle">
                        <input type="text" class="widefat thsa_qg_fix_amount right_text" name="thsa_qg_fix_amount" value="<?php
                            if(isset($params['data']['fixed_amount_discount'])){
                                echo esc_attr( $params['data']['fixed_amount_discount'] );
                            }
                        ?>" placeholder="Fix discount">
                    </td>
                    <td valign="middle">
                        <label><input type="text" class="thsa_qg_percent_amount" name="thsa_qg_percent_amount" placeholder="<?php esc_attr_e( 'Percentage discount', 'thsa-quote-generator' ) ?>"><span class="thsa_qg_tail_text">%</span></label>
                    </td>

                    <?php $this->pro_features( $params['data'], 'payment-plan-term-area' ); ?>
     
                </tr>
            </table>
        </div>

    </div>
</div>