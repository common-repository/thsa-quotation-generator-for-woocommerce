/**
 * 
 * 
 * admin js
 * @since 1.2.0
 * @author thsa
 * 
 * 
 */

var labels_ = JSON.parse(thsaqgvars.labels);

jQuery(document).ready(function(){

    //check if required plugin is active
    jQuery('.thsa_qg_payment_type').find('option').each(function(){
        if(jQuery(this).val() == 'plan'){
            if(!thsaqgvars.has_subscriptions){
                jQuery('.thsa_qg_discount_notice').css('display','inline-block');
                jQuery(this).prop('disabled', true);
            }else{
                jQuery(this).prop('disabled', false);
            }
        }
    });

    if(!thsaqgvars.has_currencies && thsaqgvars.upgraded_features ){
        jQuery('.thsa_qg_currency_notice').css('display','inline-block');
    }

    thsa_qg_calculate();

    jQuery('.thsa_qg_payment_type').click(function(){
        if(jQuery(this).val() == 'upfront'){
            jQuery('.thsa_qg_plan_fields').hide();
        }else{
            jQuery('.thsa_qg_plan_fields').show();
        }
    });

    jQuery('.thsa_admin_qg_tab li').click(function(){
        var get_parent = jQuery(this).parent('ul');
        jQuery(get_parent).find('li').removeClass('active');
        jQuery(this).addClass('active');
        var get_target = jQuery(this).data('target');
        jQuery('.thsa_tab_content .thsa_tab_child').removeClass('active');
        jQuery(get_target).addClass('active');
       
        if(get_target == '.thsa_new_tab'){
            jQuery('.thsa_qg_customer_type').val(1);
        }else{
            jQuery('.thsa_qg_customer_type').val(2);
        }
    });

    jQuery('.thsa_qg_select_woo').selectWoo({ 
        placeholder: labels_.select});
    jQuery('.thsa_qg_select_woo_inline').selectWoo({
        width: '250px', 
        placeholder: labels_.select});
     jQuery('.thsa_qg_select_woo_inline_half').selectWoo({
        width: '50%', 
        placeholder: labels_.select});
    jQuery('.thsa_qg_product_select').selectWoo({
        width: '100%',
        placeholder: labels_.select
    });

    jQuery('.thsa_qg_customer_select').selectWoo({ 
        placeholder: labels_.search_user,
        width: '100%',
        minimumInputLength: 1,
        ajax: {
            url: thsaqgvars.ajaxurl,
            data: function (params) {
                var query = {
                    search: params.term,
                    action: thsaqgvars.customer_action,
                    nonce: thsaqgvars.nonce
                }
                return query;
            },
            processResults: function( data ) {
              
				var options = [];
				if ( data ) {
			
					var parsed = JSON.parse(data);
                    for(var x in parsed){
                        options.push(
                            {
                                id: parsed[x].id,
                                text: parsed[x].id + ' - ' + parsed[x].text
                            }
                        );
                    } 
				
				}
				return {
					results: options
				};
			},
			cache: true
        }
    });

    jQuery('.thsa_qg_customer_select').change(function(){
        var id = jQuery(this).val();
        jQuery.ajax({
            method: "POST",
            url: thsaqgvars.ajaxurl,
            data: { 
                action: thsaqgvars.customer_details, 
                id: id,
                nonce: thsaqgvars.nonce
            }
            }).done(function( response ) {
                if(response){
                    var details = JSON.parse(response);
                    jQuery('.thsa_qg_name span').text(details.fullname);
                    jQuery('.thsa_qg_email span').text(details.email_address);
                    jQuery('.thsa_qg_billing span').html(details.billing_address);
                }
            }
        );
    });


    //initialize the product select
    thsa_qg_load_select();

    jQuery('.thsa_qg_filter_option').change(function(){
        jQuery('.thsa_qg_product_select').val('').change();
    });

    
    jQuery('.thsa_qg_add_product').click(function(){
        var type = jQuery('.thsa_qg_filter_option').val();
       
        if(!jQuery(this).val())
            return;

        switch(type){
            case 'product':
                thsa_qg_gen_selected_product(jQuery('.thsa_qg_product_select'));   
                break;
            case 'category':
                thsa_qg_gen_selected_category(jQuery('.thsa_qg_product_select'), 'category');
                break;
            case 'tag':
                thsa_qg_gen_selected_category(jQuery('.thsa_qg_product_select'), 'tag');
                break;
        }

        setTimeout(function(){
            jQuery('.thsa_qg_product_select').val('').change();
        },5);
            
    });

    jQuery('.thsa_qg_remove_added_item').click(function(){

        var source = jQuery(this).data('source');
        var parent_wrap = null;
        var colspan = 0;
        var selectall = null;
        var no_product_class = null;
        var no_item_text = null;
        
        switch(source){
            case 'products':
                parent_wrap = 'thsa_qg_selected_products';
                colspan = 5;
                selectall = 'thsa_qg_select_all';
                no_product_class = 'thsa_qg_no_product';
                no_item_text = labels_.no_products_added;
            break;
            case 'fee':
                parent_wrap = 'thsa_qg_added_fees';
                colspan = 4;
                selectall = 'thsa_qg_fee_select_all';
                no_product_class = 'thsa_qg_no_fee';
                no_item_text = labels_.no_fees_added;
            break;
        }

        if(confirm(labels_.confirm_message)){
            jQuery('.'+parent_wrap+' tr').each(function(){
                var parent = jQuery(this);
                var ftd = jQuery(this).find('td:first-child');
                var selected = jQuery(ftd).find('input[type=checkbox]');
                if(jQuery(selected).is(':checked')){
                    jQuery(parent).remove();
                }
            });
        }
        jQuery('.'+selectall).prop('checked',false);

        if(jQuery('.'+parent_wrap+' tr').length == 0){
            var parent_tr = thsa_field_generator(
                {
                    type: 'tr',
                    attributes: [
                        {
                            attr: 'class',
                            value: no_product_class
                        }
                    ]
                }
            );
            var none_td = thsa_field_generator(
                {
                    type: 'td',
                    attributes: [
                        {
                            attr: 'colspan',
                            value: colspan
                        }
                    ]
                }
            );

            var center = thsa_field_generator(
                {
                    type: 'center',
                    text: no_item_text
                }
            );

            jQuery(none_td).append(center);
            jQuery(parent_tr).append(none_td);
            jQuery('.'+parent_wrap).append(parent_tr);
        }

        thsa_qg_calculate();
    });

    jQuery('.thsa_qg_select_all').click(function(){
        var pro_status = jQuery(this).is(':checked');
        jQuery('.thsa_qg_selected_products tr').each(function(){
            var ftd = jQuery(this).find('td:first-child');
            jQuery(ftd).find('input[type=checkbox]').prop('checked', pro_status);
        });
    }); 

    jQuery('.thsa_qg_fee_select_all').click(function(){
        
        var is_checked = false;
        if(jQuery(this).is(':checked')){
            is_checked = true;
        }
        jQuery('.thsa_qg_added_fees tr').each(function(){
            var td = jQuery(this).find('td');
            var checkk = jQuery(td).find('input[type=checkbox]');
            if(is_checked){
                jQuery(checkk).prop('checked',true);   
            }else{
                jQuery(checkk).prop('checked',false);   
            }
        });
    });

    //add fee
    jQuery('.thsa_qg_add_fee').click(function(){

        //get name
        var name = jQuery('.thsa_qg_fee_name').val();
        var amount = jQuery('.thsa_qg_fee_amount').val();
        //var recur = jQuery('.thsa_qg_fee_recurring').val();

        if(!name || !amount)
            return;

       

        jQuery('.thsa_qg_no_fee').remove();

        var parent = jQuery('.thsa_qg_added_fees');
        var parent_tr = thsa_field_generator(
            {
                type: 'tr',
                attributes: [
                    {
                        attr: 'data-fee',
                        value: amount
                    }
                ]
            }
        );
        var td = thsa_field_generator(
            {
                type: 'td',

            }
        );
        var checkbox = thsa_field_generator(
            {
                type: 'input',
                attributes: [
                    {
                        attr: 'type',
                        value: 'checkbox'
                    }
                ]
            }
        );
        jQuery(td).append(checkbox);

        //generate hidden field
        var hidden_value = {
            fee_name: name,
            fee_amount: amount
        };
        var hidden_field = thsa_field_generator(
            {
                type: 'input',
                attributes: [
                    {
                        attr: 'name',
                        value: 'thsa_qg_added_fee[]'
                    },
                    {
                        attr: 'value',
                        value: JSON.stringify(hidden_value)
                    },
                    {
                        attr: 'type',
                        value: 'hidden'
                    }
                ]
            }
        );
        jQuery(td).append(hidden_field);
        
        var td_feename = thsa_field_generator(
            {
                type: 'td',
                text: name

            }
        );
        
        var td_amount = thsa_field_generator(
            {
                type: 'td',
                text: thsa_qg_format_numbers( parseFloat(amount) )
            }
        );
       
        jQuery(parent_tr).append(td);
        jQuery(parent_tr).append(td_feename);
        jQuery(parent_tr).append(td_amount);
        jQuery(parent).append(parent_tr);

        //clear fields
        jQuery('.thsa_qg_fee_name').val('');
        jQuery('.thsa_qg_fee_amount').val('');

        thsa_qg_calculate('fee');
    });


    //fix amount on keyup
    jQuery('.thsa_qg_fix_amount').on('keyup', function(){
        thsa_qg_calculate('fixed');
    });
    jQuery('.thsa_qg_percent_amount').on('keyup', function(){
        thsa_qg_calculate('percent');
    });


    //plan
    jQuery('.thsa_qg_payment_type').change(function(){
        var getstatus = jQuery(this).val();
        if(getstatus == 'upfront'){
            jQuery('.thsa_qg_plan_summary').removeClass('active');
            jQuery('.thsa_qg_plan_manage_button').removeClass('active');
        }else{
            jQuery('.thsa_qg_term_number').val('');
            jQuery('.thsa_qg_plan_term').val('day');
            jQuery('.thsa_qg_plan_summary').addClass('active');
            jQuery('.thsa_qg_plan_manage_button').addClass('active');
        }
        jQuery('.thsa_qg_plan_settings').removeClass('active');
        thsa_qg_calculate();
    });

    jQuery('.thsa_qg_term_number').on('change',function(){
        thsa_qg_calculate('term');
    });
    
    jQuery('.thsa_qg_plan_term').change(function(){

        //update expiry content
        var duration = jQuery(this).val();

        if( duration ){
            var dura = 90;
            var dura_label = labels_.day;

            switch( duration ){
                case 'week':
                    dura = 52;
                    dura_label = labels_.week;
                    break;
                case 'month':
                    dura = 24;
                    dura_label = labels_.month;
                    break;
                case 'year':
                    dura = 5;
                    dura_label = labels_.year;
                    break;
            }

            jQuery('.thsa_qg_term_number').find('option').each(
                function(){
                    if( jQuery( this ).val() != '' ){
                        jQuery( this ).remove();
                    }
                }
            );
            
            var plural = ( dura_label == 'day' || dura_label == 'week' || dura_label == 'month' || dura_label == 'year' )? 's' : '';
            for( var xx = 1; xx <= dura; xx++ ){
                var nplural = ( xx > 1 )? plural : '';
                jQuery('.thsa_qg_term_number').append( new Option(xx+' '+dura_label+nplural , xx) );
            }

        }

        thsa_qg_calculate('term');
    });

    jQuery('body').on('keyup','.thsa_qg_added_product_qty',function(){
        var get_qty = jQuery(this).val();
        var get_td = jQuery(this).closest('td');
        var get_tr = jQuery(get_td).closest('tr');
        var get_price = jQuery(get_tr).data('price-num');
        get_price = (get_price)? parseFloat(get_price) : 0;
        get_qty = (get_qty)? parseFloat(get_qty) : 0;
        var amount = get_price * get_qty;
        jQuery(get_tr).find('td:last-child').text( thsa_qg_format_numbers(amount) );
        
        jQuery(get_tr).find('td.thsa_qg_product_amount').attr('data-amount', amount);
        thsa_qg_calculate();
    });


    //send email
    jQuery('.thsa_qg_send_email').click(function(){
        var get_id = jQuery(this).data('id');
        //show popup
        jQuery('.thsa_qg_preview_email').show();
        if(get_id > 0){
            jQuery.ajax({
                method: "POST",
                url: thsaqgvars.ajaxurl,
                data: { 
                    action: thsaqgvars.send_email,
                    id: get_id,
                    type: 'preview',
                    nonce: thsaqgvars.nonce
                }
                }).done(function( response ) {
                    if(response){
                        var details = JSON.parse(response);
                        if(details.status == 'success'){
                            jQuery('.thsa_qg_preview_email_content_get').html(details.message);
                           
                        }
                    }

                    jQuery('.thsa_qg_loader').hide();
                    
                }
            );
        }
    });


    jQuery('#thsa_qg_close_preview').click(function(){
        jQuery('.thsa_qg_preview_email').hide();
        jQuery('.thsa_qg_preview_email_content_get').html('');
    });


    jQuery('#thsa_qg_send_email_last').click(function(){
        jQuery('#thsa_qg_close_preview').prop('disabled',true);
        jQuery(this).prop('disabled',true);
        jQuery(this).val('Sending...');
        var get_id = jQuery(this).data('id');
        jQuery.ajax({
            method: "POST",
            url: thsaqgvars.ajaxurl,
            data: { 
                action: thsaqgvars.send_email,
                id: get_id,
                type: 'send',
                nonce: thsaqgvars.nonce
            }
            }).done(function( response ) {
                if(response){
                    var details = JSON.parse(response);
                    if(details.status == 'success'){
                        alert('Email is sent!');
                    }else{
                        alert(details.message);
                    }
                }

                jQuery('#thsa_qg_send_email_last').prop('disabled',false);
                jQuery('#thsa_qg_close_preview').prop('disabled',false);
                jQuery('#thsa_qg_send_email_last').val('Send');
                
            }
        );
    });

    //add dl file
    jQuery('.thsa_qg_add_dl_file').click(function(){
        //thsa_qg_dl_file_con
        var tr = thsa_field_generator(
            {
                type: 'tr'
            }
        );
        var td1 = thsa_field_generator(
            {
                type: 'td'
            }
        );
        var name = thsa_field_generator(
            {
                type: 'input',
                attributes: [
                    {
                        attr: 'type',
                        value: 'text'
                    },
                    {
                        attr: 'class',
                        value: 'widefat'
                    },
                    {
                        attr: 'placeholder',
                        value: labels_.file_name
                    },
                    {
                        attr: 'name',
                        value: 'thsa_qg_file_name[]'
                    }
                ]
            }
        );
        jQuery(td1).append(name);
        var td2 = thsa_field_generator(
            {
                type: 'td'
            }
        )
        var file = thsa_field_generator(
            {
                type: 'input',
                attributes: [
                    {
                        attr: 'type',
                        value: 'text'
                    },
                    {
                        attr: 'class',
                        value: 'widefat thsa_upload_url_text'
                    },
                    {
                        attr: 'placeholder',
                        value: labels_.file
                    },
                    {
                        attr: 'readonly',
                        value: 'true'
                    },
                    {
                        attr: 'name',
                        value: 'thsa_qg_file_url[]'
                    }
                ]
            }
        );
        jQuery(td2).append(file);

        var td3 = thsa_field_generator(
            {
                type: 'td',
                attributes: [
                    {
                        attr: 'width',
                        value: '5%'
                    }
                ]
            }
        );
        var button = thsa_field_generator(
            {
                type: 'input',
                attributes: [
                    {
                        attr: 'type',
                        value: 'button'
                    },
                    {
                        attr: 'class',
                        value: 'button button-primary widefat thsa_qg_upload_file'
                    },
                    {
                        attr: 'value',
                        value: labels_.upload_
                    }
                ]
            }
        );
        jQuery(td3).append(button);

        var td4 = thsa_field_generator(
            {
                type: 'td',
                attributes: [
                    {
                        attr: 'width',
                        value: '2%'   
                    }
                ]
            }
        );

        var span = thsa_field_generator(
            {
                type: 'span',
                attributes: [
                    {
                        attr: 'class',
                        value: 'dashicons dashicons-dismiss thsa_remove_file_download'
                    }
                ]
            }
        );

        jQuery(td4).append(span);
        jQuery(tr).append(td1);
        jQuery(tr).append(td2);
        jQuery(tr).append(td3);
        jQuery(tr).append(td4);
        jQuery('.thsa_qg_dl_file_con').append(tr);

    });

    jQuery('.thsa_qg_dl_option').click(function(){
        if(jQuery(this).is(':checked')){
            jQuery('.thsa_qg_event_action_downloadable').addClass('active');
        }else{
            jQuery('.thsa_qg_event_action_downloadable').removeClass('active');
        }
    });

    //if (jQuery('.thsa_qg_upload_file').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            jQuery('body').on('click', '.thsa_qg_upload_file',function(e) {
                e.preventDefault();
                var button = jQuery(this);
                wp.media.editor.send.attachment = function(props, attachment) {
                    var tr = jQuery(button).closest('tr');
                    jQuery(tr).find('.thsa_upload_url_text').val(attachment.url);
                };
                wp.media.editor.open(button);
                return false;
            });
        }
    //}

    jQuery('.thsa_qg_manage_plan_settings').click(function(){
        jQuery('.thsa_qg_plan_settings').addClass('active');
    });

    jQuery('body').on('click','.thsa_remove_file_download', function(){
        var tr = jQuery(this).closest('tr');
        var td = jQuery(tr).find('td:nth(0)');
        var get_name = jQuery(td).find('input').val();
        if(confirm(labels_.confirm +' '+ get_name +'?')){
            jQuery(tr).remove();   
        }
    });

});

function thsa_qg_load_select()
{

    jQuery('.thsa_qg_product_select').selectWoo({ 
        placeholder: labels_.enter_keywords,
        width: '100%',
        minimumInputLength: 1,
        ajax: {
            url: thsaqgvars.ajaxurl,
            data: function (params) {
                var get_filter = jQuery('.thsa_qg_filter_option').val();
                var get_currency = ( thsaqgvars.upgraded_features )? jQuery('.thsa_qg_currency').val() : thsaqgvars.default_currency;
                var query = {
                    search: params.term,
                    action: thsaqgvars.product_options,
                    nonce: thsaqgvars.nonce,
                    filter: get_filter,
                    currency: get_currency
                }
                return query;
            },
            processResults: function( data ) {
                var options = [];
                    if ( data ) {
                
                        var parsed = JSON.parse(data);
                        for(var x in parsed){
                            options.push(
                                {
                                    id: parsed[x].id,
                                    text: parsed[x].text,
                                    price_html: parsed[x].price_html,
                                    price_number: parsed[x].price_number,
                                    price_regular_number: parsed[x].price_regular_number,
                                    price_sale_number: parsed[x].price_sale_number
                                }
                            );
                        } 
                    
                    }
                    return {
                        results: options
                    };
            },
            cache: true
        }
    });

}

function thsa_qg_gen_selected_product(obj)
{
    var data = jQuery(obj).selectWoo('data')[0];      
    thsa_generate_field_to(data);
}

function thsa_generate_field_to(data)
{

    //check first if the data is added
    var is_added = false;
    jQuery('.thsa_qg_selected_products tr').each(function(){
        if(data.id == jQuery(this).data('selected')){
            is_added = true;
        }
    });

    if(is_added){
        setTimeout(function(){
            jQuery('.thsa_qg_product_select').val('').change();
        },5);
        return;
    }

    jQuery('tr.thsa_qg_no_product').remove();

    var item_price = data.price_number;
    item_price = (item_price)? parseFloat(item_price) : 0;
    var amount = item_price * 1;

    var body = jQuery('.thsa_qg_selected_products');
        //data
        var parent_tr = thsa_field_generator(
            {
                type: 'tr',
                attributes: [
                    {
                        attr: 'class',
                        value: 'thsa_parent_tr'
                    },
                    {
                        attr: 'data-selected',
                        value: data.id
                    },
                    {
                        attr: 'data-selected-name',
                        value: data.text
                    },
                    {
                        attr: 'data-price-num',
                        value: amount
                    }
                ]
            }
        );

        var td_option = thsa_field_generator(
            {
                type: 'td'
            }
        );

        var option_field = thsa_field_generator(
            {
                type: 'input',
                attributes: [
                    {
                        attr: 'type',
                        value: 'checkbox'
                    }
                ]
            }
        );
        jQuery(td_option).append(option_field);

        //generate hidden field
        var hidden_field = thsa_field_generator(
            {
                type: 'input',
                attributes: [
                    {
                        attr: 'name',
                        value: 'thsa_qg_added_product[]'
                    },
                    {
                        attr: 'value',
                        value: data.id
                    },
                    {
                        attr: 'type',
                        value: 'hidden'
                    }
                ]
            }
        );
        
        jQuery(td_option).append(hidden_field);

        var td_option_name = thsa_field_generator(
            {
                type: 'td',
                text: data.text
            }
        );
        
        var td_option_price = thsa_field_generator(
            {
                type: 'td'
            }
        );

        jQuery(td_option_price).html(data.price_html);

        var td_option_qty = thsa_field_generator(
            {
                type: 'td'
            }
        );

        var td_option_qty_field = thsa_field_generator(
            {
                type: 'input',
                attributes: [
                    {
                        attr: 'name',
                        value: 'thsa_qg_added_product_qty[]'
                    },
                    {
                        attr: 'class',
                        value: 'thsa_qg_added_product_qty'
                    },
                    {
                        attr: 'value',
                        value: 1
                    },
                    {
                        attr: 'type',
                        value: 'number'
                    }
                ]
            }
        );

        jQuery(td_option_qty).append(td_option_qty_field);

        var td_option_amount = thsa_field_generator(
            {
                type: 'td',
                text: thsa_qg_format_numbers( amount ),
                attributes: [
                    {
                        attr: 'class',
                        value: 'thsa_qg_product_amount'
                    },
                    {
                        attr: 'data-amount',
                        value: data.price_number
                    }
                ]
            }
        );

        jQuery(parent_tr).append(td_option);
        jQuery(parent_tr).append(td_option_name);
        jQuery(parent_tr).append(td_option_price);
        jQuery(parent_tr).append(td_option_qty);
        jQuery(parent_tr).append(td_option_amount);
        jQuery(body).append(parent_tr);

        thsa_qg_calculate('product');
}

function thsa_qg_gen_selected_category(obj, type = null){
    jQuery('.thsa_qg_product_select, .thsa_qg_filter_option').selectWoo("enable", false);
    
    var term = jQuery(obj).val();
    var currency = ( thsaqgvars.upgraded_features )? jQuery('.thsa_qg_currency').val() : thsaqgvars.default_currency;

    jQuery.ajax({
        method: "POST",
        url: thsaqgvars.ajaxurl,
        data: { 
            action: thsaqgvars.product_from_cat, 
            term: term,
            nonce: thsaqgvars.nonce,
            type: type,
            currency: currency
        }
        }).done(function( response ) {
            if(response){
                var details = JSON.parse(response);
                for(var x in details){
                    thsa_generate_field_to(details[x]);
                }
            }
            jQuery('.thsa_qg_product_select, .thsa_qg_filter_option').prop("disabled", false);
        }
    );

}

//caculate numbers
function thsa_qg_calculate(source = null)
{
    //calculate products
    if(jQuery('.thsa_qg_selected_products tr').length > 0){
        //there is products lets calculate
        switch(source){
            case 'percent':
                get_set_total = thsa_qg_product_total();
                var percent = thsa_qg_percent_calculation(get_set_total);
                var fee = thsa_qg_fee_calculation();
    
                var type = jQuery('.thsa_qg_payment_type').val();
                if(type == 'upfront'){
                    get_set_total = (get_set_total - percent) + fee;
                }else{
                    get_set_total = (get_set_total - discount);
                    var term = thsa_qg_term_calculation(get_set_total);
                    if(term > 0){
                        get_set_total = term + fee;
                    }else{
                        get_set_total = get_set_total + fee;
                    }
                }
                break;
            case 'term':
            case 'product':
            case 'fixed':
            case 'fee':
            default:
                get_set_total = thsa_qg_product_total();
                var discount = thsa_qg_fixed_calculation(get_set_total);
                var fee = thsa_qg_fee_calculation();
                var type = jQuery('.thsa_qg_payment_type').val();
                if(type == 'upfront'){
                    get_set_total = (get_set_total - discount) + fee;
                }else{
                    get_set_total = (get_set_total - discount);
                    var term = thsa_qg_term_calculation(get_set_total);
                    if(term > 0){
                        get_set_total = term + fee;
                    }else{
                        get_set_total = get_set_total + fee;
                    }
                }
                break;
        }
        
        jQuery('.thsa_qg_total_field').val( thsa_qg_format_numbers( get_set_total ) );
        thsa_qg_update_label();
        
    }
}

function thsa_qg_product_total()
{
    var temp_product_total = 0;
    jQuery('.thsa_qg_selected_products tr').each(function(){
        //var price = jQuery(this).data('price-num');
        var price = jQuery(this).find('td.thsa_qg_product_amount').attr('data-amount');
        price = (price)? parseFloat(price) : 0;
        temp_product_total += price;
    });
    return temp_product_total;
}

function thsa_qg_fixed_calculation(get_set_total = 0)
{
    if(get_set_total == 0)
        return 0;

    var get_dis_fixed = jQuery('.thsa_qg_fix_amount').val();
    get_dis_fixed = (get_dis_fixed)? get_dis_fixed : 0;
    get_dis_fixed = parseFloat(get_dis_fixed);
    var temp_percent = get_dis_fixed / get_set_total * 100;
    if(temp_percent > 0){
        jQuery('.thsa_qg_percent_amount').val(thsa_qg_round_number(temp_percent));
    }else{
        jQuery('.thsa_qg_percent_amount').val('');
    }
    
    return get_dis_fixed;
}

function thsa_qg_percent_calculation(get_set_total = 0)
{
    if(get_set_total == 0)
        return 0;

    var get_dis_percent = jQuery('.thsa_qg_percent_amount').val(); 
    get_dis_percent = (get_dis_percent)? get_dis_percent : 0;
    get_dis_percent = parseFloat(get_dis_percent);   
    get_dis_percent = get_dis_percent / 100;
    var tem_am = get_set_total * get_dis_percent;
    if(tem_am > 0){
        jQuery('.thsa_qg_fix_amount').val(thsa_qg_round_number(tem_am));
    }else{
        jQuery('.thsa_qg_fix_amount').val('');
    }
    
    return tem_am;
}

function thsa_qg_fee_calculation()
{
    var total_fee = 0;
    jQuery('.thsa_qg_added_fees tr').each(function(){
        var get_fee = jQuery(this).data('fee');
        if(get_fee){
            get_fee = parseFloat(get_fee);
            total_fee = total_fee +  get_fee;
        }
    });
    return total_fee;
}

function thsa_qg_term_calculation(get_set_total = 0)
{
    if(get_set_total == 0)
        return 0;

    var term = jQuery('.thsa_qg_term_number').val();
    if(!term)
        return 0;

    term = parseInt(term);
    term = (term % 1 != 0)? 0 : term;

    if(term > 1){
        return get_set_total / term;
    }else{
        return 0;
    }

}

function thsa_qg_round_number(amount = 0)
{
  
    if(amount < 0)
        return 0;

    var round_set = thsaqgvars.round_settings;
    round_set = JSON.parse(round_set);
    switch(round_set.round){
        case 'off':
            amount = Math.round(amount);
            break;
        case 'up':
            amount = Math.ceil(amount);
            break;
        case 'down':
            amount = Math.floor(amount);
            break;
        default:
            amount = amount;
            break;
    }   

    if(round_set.decimal > 0){
        amount = amount.toFixed(round_set.decimal);
    }

    return amount;
    
}

function thsa_qg_update_label()
{
   var get_set_total = thsa_qg_product_total();
   var fee = thsa_qg_fee_calculation();
   var discounts = jQuery('.thsa_qg_fix_amount').val();
   discounts = (discounts)? discounts : 0;
   jQuery('.thsa_qg_original_total_label').text( thsa_qg_format_numbers(get_set_total) );
   discounts = parseFloat(discounts);
   jQuery('.thsa_qg_total_savings_label').text( thsa_qg_format_numbers(discounts) );
   jQuery('.thsa_qg_total_fee_label').text( thsa_qg_format_numbers(fee) );

   //plan
   var type = jQuery('.thsa_qg_payment_type').val();
   if(type == 'plan'){
        var term = jQuery('.thsa_qg_term_number').val();
        var plan = jQuery('.thsa_qg_plan_term').val();
        var total = jQuery('.thsa_qg_total_field').val();

        if(term > 0){
            term = parseInt(term);
            discounts = parseFloat(discounts);
            var temp_total = get_set_total - discounts;
            var term_num = temp_total / term;
            term_num = thsa_qg_round_number(term_num);
            term_text = term_num+' per '+plan;
            jQuery('.thsa_qg_term_label').text(term_text);

            var topay = term_num * term;
            jQuery('.thsa_qg_topay_label').text( thsa_qg_format_numbers (topay) );
        }else{
            jQuery('.thsa_qg_term_label').text('');
            jQuery('.thsa_qg_topay_label').text('');
        }
        
   }
   
}

function thsa_qg_format_numbers( amount = 0 ) {

    if(amount == 0 || amount == '')
        return 0;

    var round_set = thsaqgvars.round_settings;
    round_set = JSON.parse(round_set);

    if(round_set.decimal > 0){
        amount = amount.toFixed(round_set.decimal);
    }

    var parts = amount.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    
    return parts.join(".");

}