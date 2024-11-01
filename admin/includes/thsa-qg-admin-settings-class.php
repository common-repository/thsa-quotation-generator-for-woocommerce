<?php 
/**
 * 
 * 
 * thsa_qg_settings
 * @since 1.2.0
 * @author thsa
 * 
 * 
 */
namespace thsa\qg\admin\settings;
use thsa\qg\common\thsa_qg_common_class;

defined( 'ABSPATH' ) or die( 'No access area' );

class thsa_qg_admin_settings_class extends thsa_qg_common_class
{ 

    /**
     * 
     * default email message
     * 
     */
    public $default_email_text = null;

    /**
     * 
     * default admin email
     * 
     */
    public $default_admin_email = null;

    /**
     * 
     * default email title
     * 
     */
    public $default_email_title = null;


    /**
     * 
     * shortcodes
     * old shortcode [thsa_qg_quotation_holder]
     * 
     */
    public $shortcodes = [
        '[thsa-quotation-user-name]',
        '[thsa-quotation]',
        '[thsa-quotation-checkout-button]'
    ];

    public function __construct()
    {
        //add custom menu
        add_action('admin_menu',[$this, 'submenu']);

        add_action('admin_enqueue_scripts', [$this, 'load_settings_assets']);

        $this->default_email_text = "<p>Hi ".$this->shortcodes[0].",</p><p>Kindly find below the quotation you requested </p>".$this->shortcodes[1]."<p><p>".$this->shortcodes[2]."</p> Any questions or concerns please contact us through this email ".get_bloginfo('admin_email')."</p>Regards,<p>".get_bloginfo('site_name')."</p>";

        $this->default_admin_email = get_bloginfo('admin_email');

        $this->default_email_title = get_bloginfo('site_title').__(' - Quotation','thsa_quote_generator');

        add_action('wp_ajax_thsa_qg_save_settings', [$this, 'thsa_qg_save_settings']);
        
    }

    /**
     * 
     * 
     * load_settings_assets
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function load_settings_assets()
    {
        wp_register_script( THSA_QG_PREFIX.'-admin-settings-js', THSA_QG_PLUGIN_URL.'admin/assets/js/thsa-qg-settings.js', array('jquery') );
        wp_enqueue_script( THSA_QG_PREFIX.'-admin-settings-js' );
        wp_enqueue_style( THSA_QG_PREFIX.'-admin-settings-css', THSA_QG_PLUGIN_URL.'admin/assets/css/thsa-qg-settings.css');
        wp_enqueue_style( THSA_QG_PREFIX.'-admin-quotation-css', THSA_QG_PLUGIN_URL.'admin/assets/css/quotation.css');

        wp_localize_script( THSA_QG_PREFIX.'-admin-settings-js', 'thsaqg_plates', 
            [
                'blackcurrant' => [
                        'border_color' => '#000000',
                        'background_color' => '#f2f2f2',
                        'color' => '#000000',
                        'title' => [
                            'background_color' => '#000000',
                            'color' => '#FFFFFF'
                        ],
                        'total' => [
                            'color' => '#000000'
                        ]
                    ],
                'honeydew' => [
                        'border_color' => '#cfc87a',
                        'background_color' => '#fdf8e2',
                        'color' => '#000000',
                        'title' => [
                            'background_color' => '#e9e5b6',
                            'color' => '#000000'
                        ],
                        'total' => [
                            'color' => '#000000'
                        ]
                    ],
                'pear' => [
                        'border_color' => '#899100',
                        'background_color' => '#f8fadb',
                        'color' => '#000000',
                        'title' => [
                            'background_color' => '#d3d951',
                            'color' => '#000000'
                        ],
                        'total' => [
                            'color' => '#000000'
                        ]
                    ],
                'banana' => [
                        'border_color' => '#c79e05',
                        'background_color' => '#f9e7a9',
                        'color' => '#000000',
                        'title' => [
                            'background_color' => '#edc230',
                            'color' => '#000000'
                        ],
                        'total' => [
                            'color' => '#000000'
                        ]
                    ],
                'strawberry' => [
                        'border_color' => '#b50002',
                        'background_color' => '#ff5c68',
                        'color' => '#FFFFFF',
                        'title' => [
                            'background_color' => '#ee1d2b',
                            'color' => '#FFFFFF'
                        ],
                        'total' => [
                            'color' => '#FFFFFF'
                        ]
                    ],
                'blueberry' => [
                        'border_color' => '#084391',
                        'background_color' => '#68caf9',
                        'color' => '#000000',
                        'title' => [
                            'background_color' => '#2786e2',
                            'color' => '#000000'
                        ],
                        'total' => [
                            'color' => '#000000'
                        ]
                    ],
                'poncan' => [
                        'border_color' => '#ff6700',
                        'background_color' => '#ffd7b5',
                        'color' => '#000000',
                        'title' => [
                            'background_color' => '#ff9248',
                            'color' => '#000000'
                        ],
                        'total' => [
                            'color' => '#000000'
                        ]
                    ],
                'lychees' => [
                        'border_color' => '#aa0a1e',
                        'background_color' => '#d06776',
                        'color' => '#FFFFFF',
                        'title' => [
                            'background_color' => '#c83a54',
                            'color' => '#FFFFFF'
                        ],
                        'total' => [
                            'color' => '#FFFFFF'
                        ]
                    ],
                'grapes' => [
                        'border_color' => '#810066',
                        'background_color' => '#c877ba',
                        'color' => '#FFFFFF',
                        'title' => [
                            'background_color' => '#ad027e',
                            'color' => '#FFFFFF'
                        ],
                        'total' => [
                            'color' => '#FFFFFF'
                        ]
                    ],

            ]
        );

        //color picker
        wp_register_script( THSA_QG_PREFIX.'-admin-color-js', THSA_QG_PLUGIN_URL.'admin/assets/js/coloris.min.js', array('jquery') );
        wp_enqueue_script( THSA_QG_PREFIX.'-admin-color-js' );
        wp_enqueue_style( THSA_QG_PREFIX.'-admin-color-css', THSA_QG_PLUGIN_URL.'admin/assets/css/coloris.min.css');
    }

    /**
     * 
     * 
     * submenu
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function submenu()
    {

        add_submenu_page(
            'edit.php?post_type=thsa-quote-generator',
            __( 'Settings', 'thsa-quote-generator' ),
            __( 'Settings', 'thsa-quote-generator' ),
            'manage_options',
            'thsaqgettings',
            [$this, 'settings']
        );

        /*add_submenu_page(
            'edit.php?post_type=thsa-quote-generator',
            __( 'About', 'thsa-quote-generator' ),
            __( 'About', 'thsa-quote-generator' ),
            'manage_options',
            'thsaqgabout',
            [$this, 'about']
        );*/
    }

    /**
     * 
     * get_settings
     * @since 1.2.0
     * @param string - type of settings
     * @return - array
     * 
     */
    public function get_settings($type = null)
    {   
        if(!$type)
            return;

        $settings = get_option('thsa_quotation_settings');

        $settings['woocommerce_price_thousand_sep'] = get_option('woocommerce_price_thousand_sep');
        $settings['woocommerce_price_decimal_sep'] = get_option('woocommerce_price_decimal_sep');
        $settings['woocommerce_price_num_decimals'] = get_option('woocommerce_price_num_decimals');

        if(isset($settings[$type])){
            return $settings[$type];
        }else{
            return;
        }
    }

    /**
     * 
     * settings
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function settings()
    {
        $tabs = [
            [
                'text' => 'General',
                'target' => 'thsa_set_gen_con',
                'status' => 'active'
            ],
            [
                'text' => 'Coupon',
                'target' => 'thsa_set_coupon_con',
                'status' => ''
            ],
            [
                'text' => 'Email',
                'target' => 'thsa_set_email_con',
                'status' => ''
            ]
        ];

        
        $pro_data = $this->pro_features(
            [
                'text' => 'Quotation',
                'target' => 'thsa_set_quote_con',
                'status' => ''
            ]
        );

        if( isset($pro_data) ){
            $tabs[] = $pro_data;
        }


        $tab_targets = [
            [
                'class' => 'thsa_set_gen_con',
                'content' => [$this, 'general_settings'],
                'status' => 'active'
            ],
            [
                'class' => 'thsa_set_coupon_con',
                'content' => [$this, 'coupon_settings'],
                'status' => null
            ],
            [
                'class' => 'thsa_set_email_con',
                'content' => [$this, 'email_settings'],
                'status' => null
            ]
        ];

        $pro_data_targets = $this->pro_features(
            [
                'class' => 'thsa_set_quote_con',
                'content' => [$this, 'quote_settings'],
                'status' => null
            ]
        );

        if( isset($pro_data_targets) ){
            $tab_targets[] = $pro_data_targets;
        }

        $this->set_template('settings',['path' => 'admin', 'tabs' => $tabs, 'tab_targets' => $tab_targets]);
    }

    /**
     * 
     * about
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function about()
    {
        $this->set_template('about',['path' => 'admin']);
    }

    /**
     * 
     * general_settings
     * @since 1.2.0
     * @param
     * @return
     * 
     */
    public function general_settings()
    {
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'page',
            'post_status' => 'publish'
        ];
        $pages = get_posts($args);
        $settings = $this->get_settings('general');
        $this->set_template('part-settings/part-general',['path' => 'admin', 'pages' => $pages, 'settings' => $settings]);
    }

    /**
     * 
     * 
     * email_settings
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function email_settings()
    {
        $email = $this->get_settings('email');
        $this->set_template('part-settings/part-email',
            [
                'path' => 'admin', 
                'message' => (isset($email['content']))? $email['content'] : $this->default_email_text, 
                'email' => (isset($email['from_email']))? $email['from_email'] : $this->default_admin_email,
                'title' => (isset($email['title']))? $email['title'] : $this->default_email_title,
                'shortcodes' => $this->shortcodes
            ]
        );
    }

    /**
     * 
     * quote_settings
     * @since 1.2.0
     * @param
     * @return
     * 
     */
    public function quote_settings()
    {
        $plate = $this->get_settings('plates');
        $this->pro_features( $plate, 'settings-quotation' );
    }


    /**
     * 
     * 
     * subscription_settings
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function subscription_settings()
    {
        $this->set_template('part-settings/part-subscription',
        [
            'path' => 'admin'
        ]   
        );
    }

    /**
     * 
     * 
     * coupon_settings
     * @since 1.2.0
     * @param
     * @return
     * 
     */
    public function coupon_settings()
    {
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'product',
            'order' => 'ASC',
            'orderby' => 'post_title',
            'post_status' => 'publish'
        ];
        $products = get_posts($args);
        $products_ = [];
        if(!empty($products)){
            foreach($products as $prod){
                $products_[] = [
                    'id' => $prod->ID,
                    'title'=> $prod->post_title
                ];
            }
        }

        $settings = get_option('thsa_quotation_settings');
        $this->set_template('part-settings/part-coupon',
        [
            'path' => 'admin',
            'products' => $products_,
            'settings' => ( isset($settings['coupon']) )? $settings['coupon'] : null
        ]   
        );
    }

    /**
     * 
     * thsa_qg_save_settings
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function thsa_qg_save_settings()
    {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'thsa-quotation-generator' ) ) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Error 105: Invalid Nonce'
            ]);
            exit();
        }

        if( isset( $_POST['type'] ) ){

            $type__ = sanitize_text_field( $_POST['type'] );

            switch( $type__ ){
                case 'general':
                    $general = [];
                    if(isset($_POST['checkout'])){
                        $general['checkout'] = sanitize_text_field($_POST['checkout']);
                    }
                    
                    $settings = get_option('thsa_quotation_settings');
                    if(!empty($general)){
                        //get exists
                        if(isset($settings)){
                            $settings['general'] = $general;
                            update_option('thsa_quotation_settings', $settings);
                        }
                    }else{
                        $settings['general'] = null;
                        update_option('thsa_quotation_settings', $settings);
                    }        

                    break;
                case 'coupon':
                    $coupon = [];

                    if(isset($_POST['individual_usage'])){
                        $coupon['individual_usage'] = sanitize_text_field($_POST['individual_usage']);
                    }
                    if(isset($_POST['product_ids'])){
                        $coupon['product_ids'] = sanitize_text_field($_POST['product_ids']);
                    }
                    if(isset($_POST['exclude_ids'])){
                        $coupon['exclude_ids'] = sanitize_text_field($_POST['exclude_ids']);
                    }
                    if(isset($_POST['usage_limit'])){
                        $coupon['usage_limit'] = sanitize_text_field($_POST['usage_limit']);
                    }
                    if(isset($_POST['expiry_date'])){
                        $coupon['expiry_date'] = sanitize_text_field($_POST['expiry_date']);
                    }
                    if(isset($_POST['before_tax'])){
                        $coupon['before_tax'] = sanitize_text_field($_POST['before_tax']);
                    }
                    if(isset($_POST['free_shipping'])){
                        $coupon['free_shipping'] = sanitize_text_field($_POST['free_shipping']);
                    }

                    $settings = get_option('thsa_quotation_settings');
                    if(!empty($coupon)){
                        $settings['coupon'] = $coupon;
                    }else{
                        $settings['coupon'] = null;
                    }
                    update_option('thsa_quotation_settings', $settings);
                    

                    break;
                case 'email':

                    $mode = sanitize_text_field( $_POST['mode'] );

                    if( $mode == 'Save Changes' ){

                        $email_data = [];
                        if(isset($_POST['from_email'])){
                            $email_data['from_email'] =  sanitize_text_field($_POST['from_email']);
                        }

                        if(isset($_POST['title'])){
                            $email_data['title'] =  sanitize_text_field($_POST['title']);
                        }

                        if(isset($_POST['content'])){
                            $_POST['content'] = stripslashes($_POST['content']);
                            $email_data['content'] =  sanitize_text_field(htmlentities($_POST['content']));
                        }


                        $settings = get_option('thsa_quotation_settings');
                        if(!empty($email_data)){
                            //get exists
                            if(isset($settings)){
                                $settings['email'] = $email_data;
                                update_option('thsa_quotation_settings', $settings);
                            }
                        }else{
                            $settings['email'] = null;
                            update_option('thsa_quotation_settings', $settings);
                        } 

                    }else{
                        
                        $settings = get_option('thsa_quotation_settings');
                        $settings['email'] = null;
                        update_option('thsa_quotation_settings', $settings);

                    }

                    break;
                case 'plates':

                    $plates = ['thsa_qg_plate_name', 'thsa_qg_background_color', 'thsa_qg_text_color', 'thsa_qg_header_text_color', 'thsa_qg_header_color', 'thsa_qg_border_color', 'thsa_qg_total_font_color','thsa_qg_total_row_color','thsa_qg_total_font_size', 'thsa_qg_padding'];

                    $qplates = [];
                    foreach($plates as $plate){
                        if( isset( $_POST[$plate] ) ){
                            $qplates[$plate] = sanitize_text_field( $_POST[$plate] );
                        }
                    }

                    $settings = get_option('thsa_quotation_settings');
                    if( !empty($qplates) ){
                        $settings['plates'] = $qplates;
                    }else{
                        $settings['plates'] = null;
                    }
                    update_option('thsa_quotation_settings', $settings);

                    break;
                case 'restore_plate':
                    $settings = get_option('thsa_quotation_settings');
                    unset($settings['plates']);
                    update_option('thsa_quotation_settings', $settings);
                    break;
                default:
                    break;
            }

            echo json_encode([
                'status' => 'success',
                'message' => ''
            ]);
            exit();

        }else{
            echo json_encode([
                'status' => 'failed',
                'message' => 'Error 106: No type found'
            ]);
            exit();
        }
        exit();
    }

   
}

?>