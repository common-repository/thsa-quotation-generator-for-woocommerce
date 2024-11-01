<div class="thsa_qg_row thsa_qg_total_foot">
    <div class="thsa_qg_col-6">
        <strong><?php if(isset($params['data']['expiry'])){ esc_attr_e('Quotation expires on '.date('M d, Y',strtotime($params['data']['expiry'])),'thsa-quote-generator'); } ?></strong>
    </div>
</div>