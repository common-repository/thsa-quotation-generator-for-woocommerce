<div class="thsa_qg_wrapper">
    <div class="thsa_qg_inner">
        <div class="thsa_qg_table_con">
            <div class="thsa_qg_summary_box">
                <h3><?php esc_html_e('Summary', 'thsa-quote-generator'); ?></h3>
                <div class="thsa_qg_summary_box_numbers">
                    <p><?php esc_html_e('Original Total Amount:','thsa-quote-generator'); ?> <span class="thsa_qg_original_total_label">0</span></p>
                    <p><?php esc_html_e('Total Savings:','thsa-quote-generator'); ?> <span class="thsa_qg_total_savings_label">0</span></p>
                    <p><?php esc_html_e('Total Fee:','thsa-quote-generator'); ?> <span class="thsa_qg_total_fee_label">0</span></p>
                </div>
                
                <?php 
                    $status = null;
                    if(isset($params['data']['payment_type'])){
                        if($params['data']['payment_type'] == 'plan'){
                            $status = "active";
                        }
                    }
                ?>
                <div class="thsa_qg_summary_box_numbers thsa_qg_plan_summary <?php echo esc_attr( $status );  ?>">
                    <p><?php esc_html_e('Terms:','thsa-quote-generator)'); ?> <span class="thsa_qg_term_label"></span></p>
                    <p><?php esc_html_e('To Pay:','thsa-quote-generator)'); ?> <span class="thsa_qg_topay_label"></span></p>
                </div>  

                <div class="thsa_qg_total_field_wrap">
                        <?php esc_html_e('Total Today','thsa-quote-generator'); ?>
                        <input type="text" class="thsa_qg_total_field widefat right_text" placeholder="0" readonly>
                    </div>
            </div>
        </div>
    </div>
</div>