<?php 
/**
 * 
 * 
 * thsa_qg_common_class
 * class for global usage, contents methods that can be use through out admin and public
 * @since 1.2.0
 * @author thsa
 * 
 * 
 */
namespace thsa\qg\common;


class thsa_qg_common_class
{

	public $product_tag = 'product_tag';

    public $quote_slug_tag = 'thsa-quotation';


	/**
	 * 
	 * 
	 * pro version plugin id
	 * @since 1.2.2
	 * 
	 * 
	 */
	public $pro_id = 'thsa-quotation-generator-for-woocommerce-pro/thsa-quote-generator-pro.php';


	/**
	 * 
	 *
	 * pro version namespace
	 * @since 1.2.2
	 * 
	 * 
	 */
	public $pro_namespace = 'thsa\qg\pro\admin\thsa_quote_pro_admin_class';

    /**
     * 
     * 
     * set_template
     * render content via template html file
     * @since 1.2.0
     * @param file, array
     * @return file
     * 
     * 
     */
    public function set_template($file, $params = [])
    {
        if(!$file)
			return;

		if(strpos($file,'.php') === false)
			$file = $file.'.php';

		$other = null;
		if(isset($params['other'])){
			$other = $params['other'].'/';
		}

		if(isset($params['path'])){
			$other = $params['path'].'/';
		}

		$path = get_stylesheet_directory().'/'.THSA_QG_FOLDER.'/'.$other.'templates';		

		if(file_exists($path.'/'.$file)){
			include $path.'/'.$file;
		}else{
			include THSA_QG_PLUGIN_PATH.$other.'/templates/'.$file;
		}
    }


	/**
	 * 
	 * load_js
	 * load common js
	 * @since 1.2.0
	 * 
	 */
	public function load_js()
	{
		wp_register_script( THSA_QG_PREFIX.'-common-js', THSA_QG_PLUGIN_URL.'common/assets/thsa-qg-common.js', array('jquery') );
        wp_enqueue_script( THSA_QG_PREFIX.'-common-js' );
	}

	/**
	 * 
	 * 
	 * common_labels
	 * @since 1.2.0
	 * @param
	 * @return array
	 * 
	 * 
	 */
	public static function labels()
	{
		$labels = [
			'select' => __('Select','thsa-quote-generator'),
			'search_user' => __('Select User', 'thsa-quote-generator'),
			'enter_keywords' => __('Enter Keywords', 'thsa-quote-generator'),
			'no_products_added' => __('No products added', 'thsa-quote-generator'),
			'no_fees_added' => __('No fees added','thsa-quote-generator'),
			'processing' => __('Processing please wait...','thsa-quote-generator'),
			'confirm_message' => __('Are you sure you want to remove selected item(s)','thsa-quote-generator'),
			'saving' => __('Saving...','thsa-quote-generator'),
			'file_name' => __('File Name', 'thsa-quote-generator'),
			'file'	=> __('File', 'thsa-quote-generator'),
			'upload_' => __('Upload', 'thsa-quote-generator'),
			'add_file' => __('Add File', 'thsa-quote-generator'),
			'confirm' => __('Are you sure you want to remove', 'thsa-quote-generator'),
			'currency_confirm' => __('WARNING: Changing currency will break previous calculation please review the numbers', 'thsa-quote-generator'),
			'day' => __('day', 'thsa-quote-generator'),
			'week' => __('week', 'thsa-quote-generator'),
			'month' => __('month', 'thsa-quote-generator'),
			'year' => __('year', 'thsa-quote-generator')
		];
		return json_encode($labels);
	}

	/**
	 * 
	 * 
	 * sanitize_json
	 * @since 1.2.0
	 * @param json
	 * @return array
	 * 
	 * 
	 */
	public function sanitize_json($data)
	{
		$data = stripslashes($data);
        $data_ = (array) json_decode($data);
        array_map(function($key, $val){
            return [sanitize_text_field($key), sanitize_text_field($val)];
        },array_keys($data_),array_values($data_));
		return $data_;
	}

	/**
	 * 
	 * tab_manager
	 * @since 1.2.0
	 * @param array
	 * @return string
	 * 
	 */
	public function tab_settings_manager($data = [])
	{	
		if(empty($data))
			return;
		
		$data = apply_filters('thsa_quote_settings_tab_manager', $data);
		$tabs = null;
		foreach($data as $row){
			$tabs .= '<li class="'.$row['status'].'" data-target="'.$row['target'].'">'.$row['text'].'</li>';
		}
		return $tabs;
	}
	
	/**
	 * 
	 * tab_content_manager
	 * @since 1.2.0
	 * @param array
	 * @return string
	 * 
	 */
	public function tab_content_manager($data = [])
	{
		if(empty($data))
			return;

		$data = apply_filters('thsa_quote_settings_content_manager', $data);
		$content = null;
		foreach($data as $row){
			if(is_array($row['content'])):
				$cl = $row['content'][0];
				$fn = $row['content'][1];
			?>
				<div class="thsa_qg_tab_content <?php echo esc_attr( $row['class'] ) .' '.esc_attr( $row['status'] ); ?>"><?php wp_kses_post( $cl->$fn() ); ?></div>
			<?php
			else:
			?>
				<div class="thsa_qg_tab_content <?php echo esc_attr( $row['class'] ).' '.esc_attr( $row['status'] ); ?>"><?php wp_kses_post( $row['content'] ); ?></div>
			<?php
			endif;
		}
		
	}


	/**
	 * 
	 * 
	 * price_html
	 * our own get_price_html version
	 * @since 1.2.0
	 * @param array
	 * @return string
	 * 
	 * 
	 */
	public function price_html( $data = [] )
	{	
		if( empty($data) )
			return;

		$data =	apply_filters('thsa_qg_get_price_html_before', $data );
		
		$price_html = '';
		$amount = $data['regular'];

		//we need to take the woo formatting
		$woocommerce_price_thousand_sep = get_option('woocommerce_price_thousand_sep');
		$woocommerce_price_decimal_sep = get_option('woocommerce_price_decimal_sep');
		$woocommerce_price_num_decimals = get_option('woocommerce_price_num_decimals');
		
		$currency = '<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol( $data['currency'] ).'</span>';

		if(isset($data['sale'])){
			$price_html .= '<del><span class="amount">'.$currency.number_format( $data['regular'], $woocommerce_price_num_decimals, $woocommerce_price_decimal_sep, $woocommerce_price_thousand_sep).'</span></del>';
			$amount = $data['sale'];
		}

		$price_html .= ' <ins><span class="amount">'.$currency.number_format($amount, $woocommerce_price_num_decimals, $woocommerce_price_decimal_sep, $woocommerce_price_thousand_sep).'</span></ins>';

		return apply_filters( 'thsa_qg_get_price_html', wp_kses_post( $price_html ) );
		
	}

	/**
	 * 
	 * 
	 * format_number
	 * format the number base from WOO settigns
	 * @since 1.2.0
	 * @param int or float
	 * @return float
	 * 
	 * 
	 */
	public function format_number( $args = 0 )
	{
		if( $args['amount'] == 0 || $args['amount'] == '')
			return;

		$woocommerce_price_thousand_sep = get_option('woocommerce_price_thousand_sep');
		$woocommerce_price_decimal_sep = get_option('woocommerce_price_decimal_sep');
		$woocommerce_price_num_decimals = get_option('woocommerce_price_num_decimals');

		return apply_filters(
			'thsa_qg_formatted_amount',
			number_format($args['amount'], $woocommerce_price_num_decimals, $woocommerce_price_decimal_sep, $woocommerce_price_thousand_sep)
		);

	}

	/**
	 * 
	 * 
	 * render_quotation
	 * @since 1.2.0
	 * @param int
	 * @return html
	 * 
	 * 
	 */
	public function render_quotation( $id = 0 )
	{
		
		if( $id == 0 )
			return;

			$quote = get_post_meta($id,'thsa_quotation_data',true);

            if( empty($quote) ){
                return __('#Error: No quotation available','thsa_quote_generator');
            }

			if( isset($quote['expiry']) ){
				$date = new \DateTime($quote['expiry']);
				$now = new \DateTime();
				if($date < $now) {
					return apply_filters( 'thsa_qg_quote_expired_text', __('Quotation is expired','thsa_quote_generator') );
				}
			}  
            
            $plan_product = [];
            if($quote['payment_type'] == 'plan'){
                if(isset($quote['plan_product_id'])){
                    $plan_prod = wc_get_product( $quote['plan_product_id'] );
                    $plan_product[] = [
                        'id' => $plan_prod->get_id(),
                        'text' => strtoupper($plan_prod->get_title()),
                        'price_html' => '--',
                        'price_number' => $plan_prod->get_price_html(),
                        'price_regular_number' => $plan_prod->get_regular_price(),
                        'price_sale_number' => $plan_prod->get_sale_price(),
                        'qty' => '--',
                        'amount' => '--'
                    ];
                }
            }

            if($quote){
                //get products
                $products = [];
                $total = 0;
                if(!empty($quote['products'])){
                    foreach($quote['products'] as $product){
                        $product_details = wc_get_product($product[0]);
                        $total = $total + ($product_details->get_price() * $product[1]);
                        $products[$product[0]] = [
                            'id' => $product_details->get_id(),
                            'text' => $product_details->get_title(),
                            'price_html' => $product_details->get_price_html(),
                            'price_number' => $product_details->get_price(),
                            'price_regular_number' => $product_details->get_regular_price(),
                            'price_sale_number' => $product_details->get_sale_price(),
                            'qty' => $product[1],
                            'amount' => ($quote['payment_type'] == 'upfront')? wc_price($product_details->get_price() * $product[1]) : __('included', 'thsa-quote-generator')
                        ];
                    }
                }

                if($quote['payment_type'] == 'plan'){
                    $products = $plan_product + $products;
                    $discounted_total = $plan_prod->get_price();
                }else{
                    $discount = ($quote['fixed_amount_discount'])? $quote['fixed_amount_discount'] : 0;
                    $discounted_total = $total - $discount;
                }
                    

                //fees
                $fees = 0;
                $fee_labels = [];
                if(isset($quote['fees'])){
                    foreach($quote['fees'] as $fee){
                        $fee_ = ($fee['fee_amount'])? $fee['fee_amount'] : 0;
                        $fees += $fee_;
                        $fee_labels[] = [
                            'name' => $fee['fee_name'],
                            'amount' => $fee['fee_amount']
                        ];
                    }
                }
                $quote['fees'];
                $grand_total = $discounted_total + $fees;

                //summary area
                $summary = [
                    'Subtotal' => wc_price($total),
                    'Discount' => '-' . wc_price($quote['fixed_amount_discount']),
                    'Terms' => (isset($plan_product[0]))? $plan_product[0]['price_number'] : null,
                    'Fees'  => $fee_labels,
                    'Total Today' => wc_price($grand_total)
                ];

                $summary = apply_filters('thsa_qg_quote_summary_before',  $summary);

				$settings = get_option('thsa_quotation_settings');
				return [
					'path' => 'public', 
					'products' => $products, 
					'data' => $quote,
					'grand_total' => $grand_total, 
					'undiscounted' => $total,
					'qid' => $id,
					'labels' => $summary,
					'style' => ( isset( $settings['plates'] ) )? $settings['plates'] : null
				];

            }else{
				return __('#Error: No quotation available','thsa_quote_generator');
            }
		
	}

	/**
     * 
     * 
     * 
     * currency
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function currency( $args = 0 )
    {   
        if( $args == 0 )
            return;
        
        $args = sanitize_text_field($args);
        $quote = get_post_meta($args, 'thsa_quotation_data', true);

        if( $quote ){

            $currency = ( strlen( $quote['currency'] ) > 0 )? $quote['currency'] : get_woocommerce_currency();

            if ( is_plugin_active( 'woocommerce-aelia-currencyswitcher/woocommerce-aelia-currencyswitcher.php' ) ) {
                $_POST['aelia_cs_currency'] = $currency;
            }

        }else{
            return;
        }

    }

	/**
	 * 
	 * 
	 * 
	 * 
	 * render_inline_style
	 * @since 1.2.0
	 * @param
	 * @return
	 * 
	 * 
	 */
	public function render_inline_style( $args = null)
	{
		if( !isset($args) )
			return;

		$style = null;
		foreach($args as $arg){
			switch( $arg['type'] ){
				case 'thsa_qg_text_color':
				case 'thsa_qg_header_text_color':
				case 'thsa_qg_total_font_color':
					if(!empty($arg['value']))
						$style .= 'color: '.$arg['value'].';';
					break;
				case 'thsa_qg_header_color':
				case 'thsa_qg_total_row_color':
				case 'thsa_qg_background_color':
					if(!empty($arg['value']))
						$style .= 'background-color: '.$arg['value'].';';
					break;
				case 'thsa_qg_border_color':
					if(!empty($arg['value']))
						$style .= 'border: 1px solid '.$arg['value'].';';
					break;
				case 'thsa_qg_total_font_size':
					if(!empty($arg['value']))
						$style .= 'font-size: '.$arg['value'].'px;';
					break;
				case 'thsa_qg_padding':
					if(!empty($arg['value']))
						$style .= 'padding: '.$arg['value'].'px;';
					break;
					break;
			}	
		}
		
		//escape css
		return esc_attr( $style );
	}

	/**
	 * 
	 * 
	 * pro_features
	 * @since 1.2.0
	 * @param mixed
	 * @return mixed
	 * 
	 * 
	 */
	public function pro_features( $data = null, $type = null )
	{
		
		if ( is_plugin_active( $this->pro_id ) ) {
			$pro = $this->pro_namespace;
			$pro_content = new $pro();
			switch ( $type ) {
				case 'settings-quotation':
					$pro_content->load_qoutation_settings( $data );
					break;
				case 'edit-email':
					$pro_content->load_edit_email( $data );
					break;
				case 'currency':
					$pro_content->load_currency( $data );
					break;
				case 'payment-plan':
					$pro_content->load_payment_plan( $data );
					break;
				case 'payment-plan-settings':
					$pro_content->load_payment_plan_settings( $data );
					break;
				case 'payment-plan-settings-area':
					$pro_content->load_payment_plan_settings_area( $data );
					break;
				case 'payment_plan_settings_data_save':
					return $pro_content->load_plan_settings_save( $data );
					break;
				case 'payment-plan-term-area':
					return $pro_content->payment_plan_term_area( $data );
					break;
				case 'upgraded':
					return true;
					break;
				default:
					return $data;
				break;
			}
		} else {
			return null;
		}

	}

}
?>