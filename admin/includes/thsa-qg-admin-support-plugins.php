<?php 
/**
 * 
 * 
 * thsa_qg_admin_support_plugins
 * @since 1.2.0
 * @author thsa
 * 
 * 
 */
namespace thsa\qg\admin\support\plugins;
use thsa\qg\common\thsa_qg_common_class;

defined( 'ABSPATH' ) or die( 'No access area' );


class thsa_qg_admin_support_plugins extends thsa_qg_common_class
{

    /**
     * 
     * 
     * determin if the woocommerce subscription is active
     * 
     * 
     */
    var $woocommerce_subscription = false;

    /**
     * 
     * 
     * determine if the aelia woocommerce currency switcher is active
     * 
     * 
     */
    var $aelia_woo_currency_switcher = false;

    /**
     * 
     * check if any supported plugin is available for subscriptions
     * 
     */
    var $has_subscriptions = false;

    /**
     * 
     * 
     * check if any supported plugin available for currencies
     * 
     * 
     */
    var $has_currencies = false;

    public function __construct()
    {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $this->woocommerce_subscription = is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' );
        $this->aelia_woo_currency_switcher = is_plugin_active( 'woocommerce-aelia-currencyswitcher/woocommerce-aelia-currencyswitcher.php' );

        $subscriptions_ = [ $this->woocommerce_subscription ];
        foreach($subscriptions_ as $subs){
            if( $subs ){
                $this->has_subscriptions = true;
                break;
            }
        }

        $currencies_ = [ $this->aelia_woo_currency_switcher ];
        foreach($currencies_ as $cur){
            if( $cur ){
                $this->has_currencies = true;
                break;
            }
        }

    }
    
    /**
     * 
     * 
     * 
     * setttings
     * @since 1.2.0
     * @param string - assigned plugin name
     * @return array
     * 
     * 
     * 
     */
    public function settings( $plugin = null )
    {
        if(!$plugin)
            return;

        switch($plugin)
        {
            case 'aelia':
                return  $this->aelia_currency_switcher();
                break;
        }
    }

    /**
     * 
     * 
     * aelia_currency_switcher
     * plugin: aelia currency switcher
     * @since 1.2.0
     * @param
     * @return
     * 
     */
    public function aelia_currency_switcher()
    {
        if ( $this->aelia_woo_currency_switcher ) {
            //do stuffs for aelia
            $settings = get_option('wc_aelia_currency_switcher');
            return apply_filters('thsa_qg_aelia_settings', 
                [
                    'enabled_currencies' => $settings['enabled_currencies'],
                    'exchange_rates' => $settings['exchange_rates']
                ]
            );
        }else{
            return;
        }
    }

    /**
     * 
     * 
     * currency_values
     * @since 1.2.0
     * @param array
     * @return array
     * 
     * 
     */
    public function currency_values( $args = [] )
    {
        
        $price_html = $args['product_prices']['price_html'];
        $price_number = $args['product_prices']['price_number'];
        $price_regular_number = $args['product_prices']['price_regular_number'];
        $price_sale_number = $args['product_prices']['price_sale_number'];
        
        /**
         * 
         * 
         * aelia currency switcher
         * 
         * 
         */
        if ( $this->aelia_woo_currency_switcher ) {
            //check if manually added
            $currency_prices = get_post_meta($args['product_id'], '_regular_currency_prices', true);
            $currency_prices = json_decode($currency_prices);
            $currency_prices = (array) $currency_prices;

            if(!empty($currency_prices)){
                    
                $currency_sale_prices = get_post_meta($args['product_id'], '_sale_currency_prices', true);
                $currency_sale_prices = json_decode($currency_sale_prices);
                $currency_sale_prices = (array) $currency_sale_prices;

                if( !empty($currency_sale_prices) ){

                    $price_sale_ = ( !empty($currency_sale_prices[ $args['currency'] ]) )? $currency_sale_prices[ $args['currency'] ] : get_post_meta( $args['product_id'], '_price', true);
                    $price_ = ( !empty($currency_prices[ $args['currency'] ]) )? $currency_prices[ $args['currency'] ] : get_post_meta( $args['product_id'], '_price', true);

                    $price_html = $this->price_html(
                        [
                            'sale' => $price_sale_,
                            'regular' => $price_,
                            'currency' => $args['currency']
                        ]
                    );
                    $price_number = $price_sale_;
                    $price_regular_number = $price_sale_;
                    $price_sale_number = $price_sale_;

                }else{
                    $price_ = ( !empty($currency_prices[ $args['currency'] ]) )? $currency_prices[ $args['currency'] ] : get_post_meta( $args['product_id'], '_price', true);
                    $price_html = $this->price_html(
                        [
                            'regular' => $price_,
                            'currency' => $args['currency']
                        ]
                    );

                    $price_number = $price_;
                    $price_regular_number = $price_;
                }

                return [
                    'price_html' => $price_html,
                    'price_number' => $price_number,
                    'price_regular_number' => $price_regular_number,
                    'price_sale_number' => $price_sale_number
                ];

            }else{
                //compute rate if product has no manual coversion
                $settings = $this->aelia_currency_switcher();

                if(isset($settings)){

                    $get_sale_price = get_post_meta( $args['product_id'], '_sale_price', true );
                    if( !empty($get_sale_price) ){

                        $price_regular_number = get_post_meta( $args['product_id'], '_regular_price', true );

                        if( isset($settings['exchange_rates'][ $args['currency'] ]['rate']) ){
                            $mark_up = $settings['exchange_rates'][$args['currency']];
                            $rate = $settings['exchange_rates'][$args['currency']]['rate'];
                            
                            if(isset( $mark_up['rate_markup'] ) && $mark_up['rate_markup'] != ''){
                                
                                if( strpos($mark_up['rate_markup'],'%') !== false ){
                                    $temp_markup = str_replace('%','',$mark_up['rate_markup']);
                                    $temp_rate =  $rate * ($temp_markup / 100 ) + $rate;
                                }else{
                                    $temp_rate = $rate + $mark_up['rate_markup'];
                                }
                                $price_regular_number = $price_regular_number * $temp_rate;
                                $get_sale_price = $get_sale_price * $temp_rate;

                            }else{
                                //no markup
                                $price_regular_number = $price_regular_number * $rate;
                                $get_sale_price = $get_sale_price * $rate;
                            }
                        }

                        $price_html = $this->price_html(
                            [
                                'sale' => $get_sale_price,
                                'regular' => $price_regular_number,
                                'currency' => $args['currency']
                            ]
                        );
                        $price_number = $get_sale_price;
                        $price_sale_number = $get_sale_price;
                        
                    }else{

                        $price_regular_number = get_post_meta( $args['product_id'], '_price', true );

                        if( isset($settings['exchange_rates'][ $args['currency'] ]['rate']) ){
                            $mark_up = $settings['exchange_rates'][ $args['currency'] ];
                            $rate = $settings['exchange_rates'][ $args['currency'] ]['rate'];
                            
                            if(isset( $mark_up['rate_markup'] )){

                                if( strpos($mark_up['rate_markup'],'%') !== false ){
                                    $temp_markup = str_replace('%','',$mark_up['rate_markup']);
                                    $temp_rate =  $rate * ($temp_markup / 100 ) + $rate;
                                }else{
                                    $temp_rate = $rate + $mark_up['rate_markup'];
                                }
                                $price_regular_number = $price_regular_number * $temp_rate;
    
                            }else{
                                //no markup
                                $price_regular_number = $price_regular_number * $rate;
                            }
                        }

                        $price_html = $this->price_html(
                            [
                                'regular' => $price_regular_number,
                                'currency' => $args['currency']
                            ]
                        );
                        $price_number = $price_regular_number;

                    }

                }
                //calculate rate
                return [
                    'price_html' => $price_html,
                    'price_number' => $price_number,
                    'price_regular_number' => $price_regular_number,
                    'price_sale_number' => $price_sale_number,
                    'formatted_price_number' => $this->format_number( ['amount' => $price_number, 'round' => false ] ),
                    'formatted_regular_price_number' => $this->format_number( ['amount' => $price_regular_number, 'round' => false ] ),
                    'formatted_sale_price_number' => $this->format_number( ['amount' => $price_sale_number, 'round' => false ] )
                ];

            }

        } 
        /**
         * 
         * 
         * 
         * end of aelia curreny switcher
         * 
         * 
         * 
         */
        
        //just return what is passed
        return [
            'price_html' => $price_html,
            'price_number' => $price_number,
            'price_regular_number' => $price_regular_number,
            'price_sale_number' => $price_sale_number,
            'formatted_price_number' => $this->format_number( ['amount' => $price_number, 'round' => false ] ),
            'formatted_regular_price_number' => $this->format_number( ['amount' => $price_regular_number, 'round' => false ] ),
            'formatted_sale_price_number' => $this->format_number( ['amount' => $price_sale_number, 'round' => false ] )
        ];
    }



    /**
     * 
     * 
     * switch_currency
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */

    public function switch_post_currency( $currency = null )
    {

        if( !isset($currency) )
            return;

        /**
         * 
         * 
         * For aelia currency switcher
         * 
         * 
         */
        if ( $this->aelia_woo_currency_switcher ) {
            
            $_POST['aelia_cs_currency'] = $currency;

        }
        
    }

    /**
     * 
     * 
     * coupon_currency
     * @since 1.2.0
     * @param object
     * @return
     * 
     * 
     */
    public function coupon_currency( $id, $amount = 0 )
    {

        if( !isset($id) )
            return;

        /**
         * 
         * aelia currency switcher
         * 
         */
        if ( $this->aelia_woo_currency_switcher ) {

            //get endabled currencies
            $settings = $this->aelia_currency_switcher();
            $currencies = $settings['enabled_currencies'];

            if(!empty($currencies)){
                $amounts = [];
                foreach($currencies as $currencies){
                    $amounts[$currencies] = [
                        'coupon_amount' => $amount,
                        'minimum_amount' => null,
                        'maximum_amount' => null
                    ];
                }
                if(!empty($amounts))
                    update_post_meta( $id, '_coupon_currency_data', $amounts );
            }


        }
            
        

    }

    /**
     * 
     * 
     * generate_plan
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function generate_plan($post_id = 0, $data = null)
    {
        if(!$data || !$post_id)
            return;

        if(!isset($data['products']))
            return;

        if ( $this->woocommerce_subscription ) {

            if ( is_plugin_active( $this->pro_id ) ) {
                $pro = $this->pro_namespace;
                $pro_content = new $pro();
                return $pro_content->generate_plan( $post_id, $data );
            }

        }
        return;
    }


}



?>