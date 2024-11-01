/**
 * 
 * 
 * settings js
 * @since 1.2.0
 * @author thsa
 * 
 * 
 */
jQuery(document).ready(function(){
    Coloris({
        el: '.coloris',
        theme: 'large',
        themeMode: 'light' // light, dark, auto,
    });

    jQuery('.thsa_qg_settings_tab li').click(function(){
        var get_target = jQuery(this).data('target');
        jQuery('.thsa_qg_settings_tab li').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.thsa_settings_content .thsa_qg_tab_content').removeClass('active');
        jQuery('.thsa_settings_content .'+get_target).addClass('active');
    });

    jQuery('.thsa_save_gen_ettings').click(function(){
        jQuery(this).val(labels_.saving);
        jQuery(this).prop('disabled', true);

        var checkout_page = jQuery('.thsa_qg_settings_pages').val();
        jQuery.ajax({
            method: "POST",
            url: thsaqgvars.ajaxurl,
            data: { 
                action: thsaqgvars.save_settings, 
                type: 'general',
                checkout: checkout_page,
                nonce: thsaqgvars.nonce
            }
            }).done(function( response ) {
                if(response){
                    response = JSON.parse(response);
                    if(response.status == 'success'){
                        jQuery('.thsa_qg_response_general').show();
                        jQuery('.thsa_save_gen_ettings').val('Save');
                        jQuery('.thsa_save_gen_ettings').prop('disabled', false);
                        setTimeout(function(){
                            jQuery('.thsa_qg_response_general').hide();
                        }, 10000);
                    }
                }
            }
        );
    });

    jQuery('.thsa_qg_save_email_settings, .thsa_qg_reset_email_settings').click(function(){

        var mode = jQuery(this).val();

        if( mode == 'Save Changes'){
            jQuery(this).val('Saving...');
        }else{
            jQuery(this).val('Reseting...');
        }
       
        jQuery(this).prop('disabled', true);

        var from_email = jQuery('.thsa_email_set_email').val();
        var title = jQuery('.thsa_email_set_title').val();
        var content = tinymce.get('thsaqgemailcontent').getContent({format: 'raw'});

        jQuery.ajax({
            method: "POST",
            url: thsaqgvars.ajaxurl,
            data: { 
                action: thsaqgvars.save_settings, 
                type: 'email',
                from_email: from_email,
                title: title,
                content: content,
                nonce: thsaqgvars.nonce,
                mode: mode
            }
            }).done(function( response ) {
                if(response){
                    response = JSON.parse(response);
                    if(response.status == 'success'){
                        jQuery('.thsa_qg_response_email').show();
                        jQuery('.thsa_qg_save_email_settings').val('Save Changes');
                        jQuery('.thsa_qg_save_email_settings, .thsa_qg_reset_email_settings').prop('disabled', false);
                        jQuery('.thsa_qg_reset_email_settings').val('Reset');
                        setTimeout(function(){
                            jQuery('.thsa_qg_response_email').hide();
                        }, 10000);
                    }
                }
            }
        );
        
    });

    jQuery('.thsa_qg_save_email_preview').click(function(){
        jQuery('.thsa_qg_email_preview_wrap').show();
        jQuery.ajax({
            method: "POST",
            url: thsaqgvars.ajaxurl,
            data: { 
                action: thsaqgvars.preview_email,
                nonce: thsaqgvars.nonce
            }
            }).done(function( response ) {
                console.log(response);
                if(response){
                    response = JSON.parse(response);
                    if(response.status == 'success'){
                        jQuery('.thsa_qg_email_prev_content').html(response.message);
                    }
                }
            }
        );
    });

    jQuery('.thsa_qg_settings_save_coupon').click(function(){
        jQuery(this).val('Saving...');
        jQuery(this).prop('disabled', true);

        var individual_usage = (jQuery('.thsa_qg_settings_individual_use').is(':checked'))? 'yes' : null;
        var product_ids = (jQuery('.thsa_qg_settings_coupon_pids').val())? jQuery('.thsa_qg_settings_coupon_pids').val() : null;
        var exclude_ids = (jQuery('.thsa_qg_settings_coupon_exclude_pids').val())? jQuery('.thsa_qg_settings_coupon_exclude_pids').val() : null;
        var usage_limit = (jQuery('.thsa_qg_settings_usage_limit').val())? jQuery('.thsa_qg_settings_usage_limit').val() : null;
        var expiry_date = (jQuery('.thsa_qg_settings_expiry_date').val())? jQuery('.thsa_qg_settings_expiry_date').val() : null;
        var before_tax = (jQuery('.thsa_qg_settings_apply_before_tax').is(':checked'))? 'yes' : null;
        var free_shipping = (jQuery('.thsa_qg_settings_free_shipping').is(':checked'))? 'yes' : null;

        jQuery.ajax({
            method: "POST",
            url: thsaqgvars.ajaxurl,
            data: { 
                action: thsaqgvars.save_settings, 
                type: 'coupon',
                individual_usage: individual_usage,
                product_ids: (product_ids)? product_ids.join(',') : null,
                exclude_ids: (exclude_ids)? exclude_ids.join(',') : null,
                usage_limit: usage_limit,
                expiry_date: expiry_date,
                before_tax: before_tax,
                free_shipping: free_shipping,
                nonce: thsaqgvars.nonce
            }
            }).done(function( response ) {
                if(response){
                    response = JSON.parse(response);
                    if(response.status == 'success'){
                        jQuery('.thsa_qg_coupon_message').show();
                        jQuery('.thsa_qg_settings_save_coupon').val('Save');
                        jQuery('.thsa_qg_settings_save_coupon').prop('disabled', false);
                        setTimeout(function(){
                            jQuery('.thsa_qg_coupon_message').hide();
                        }, 10000);
                    }
                }
            }
        );
    });


});