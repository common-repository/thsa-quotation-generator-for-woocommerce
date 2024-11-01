/**
 * 
 * 
 * 
 * 
 */
 var labels_ = JSON.parse(thsaqg_public_vars.labels);
jQuery(document).ready(function(){
    jQuery('.thsa_qg_proceed_checkout').click(function(){
        var get_id = jQuery(this).data('q-id');
        jQuery(this).val(labels_.processing);
        jQuery(this).prop('disabled',true);
        window.location = thsaqg_public_vars.thsa_qg_checkout_url+get_id;
    });
});