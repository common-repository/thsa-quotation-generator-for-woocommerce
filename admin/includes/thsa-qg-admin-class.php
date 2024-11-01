<?php 
/**
 * 
 * 
 * thsa_qg_admin
 * @since 1.2.0
 * @author thsa
 * 
 * 
 */
namespace thsa\qg\admin;
use thsa\qg\common\thsa_qg_common_class;
use thsa\qg\admin\settings as qgsettings;
use thsa\qg\admin\support\plugins  as support_plugins;

defined( 'ABSPATH' ) or die( 'No access area' );

class thsa_qg_admin_class extends thsa_qg_common_class{

    private $product_post_type = 'product';


    private $product_category = 'product_cat';


    public $setting_class = null;


    public $quotation_ids = [];

    
    public $support_plugin = null;

    /**
     * 
     * 
     * qg admin flag
     * @since 1.2.0
     * 
     */
    private $admin_page = '';

    /**
     * 
     * 
     * __construct
     * @since 1.2.0
     * 
     * 
     */

    public function __construct()
    {

        $this->setting_class = new qgsettings\thsa_qg_admin_settings_class();
        //support other plugins
        $this->support_plugin = new support_plugins\thsa_qg_admin_support_plugins();

        //load common js
        add_action('admin_enqueue_scripts', [$this, 'load_admin_assets']);

        //register post type
        add_action('init', [$this, 'register_post_type']);

        //register meta
        add_action('add_meta_boxes', [$this, 'meta_box']);

        $this->load_ajax();

        //save data
        add_action('save_post_thsa-quote-generator', [$this, 'save_quote']);

        add_shortcode( 'thsa-quotation-email' , [$this, 'email_quotation'] );

        add_filter('manage_thsa-quote-generator_posts_columns' , [$this, 'table_tabs'] );
		add_action('manage_thsa-quote-generator_posts_custom_column', [$this,'table_tabs_content'], 10, 2 );

        add_action ( 'admin_enqueue_scripts', function () {
            if (is_admin ())
                wp_enqueue_media ();
        } );

        //generate quotation tag
        add_action('admin_init', [$this, 'generate_tag'] );

        //remove quotation posts from products
        //add_action( 'pre_get_posts' , [ $this,'exclude_quotation'] );

        add_action('woocommerce_init', [$this, 'admin_currency'], 0);

        add_filter( 'views_edit-product', [$this, 'update_count'], 10, 1);    
        
        if( !is_plugin_active( $this->pro_id ) ){
			add_action('admin_menu',[$this, 'custom_menu']);
		}

    }

    /**
     * 
     * 
     * table_tabs
     * @since 1.2.1
     * @param $columns (array)
     * 
     * 
     */
    public function table_tabs( $columns )
    {
        array_splice( $columns, 2, 0, [__('Shortcodes','thsa-quote-generator')] );
		array_splice( $columns, 3, 0, [__('Author','thsa-quote-generator')] );
		return $columns;
    }

    /**
     * 
     * 
     * table_tabs_content
     * @since 1.2.1
     * @param $column (int)
     * @param $post_id (int)
     * 
     * 
     */
    public function table_tabs_content( $column, $post_id )
    {
        $post_id = sanitize_text_field( $post_id );
		switch($column){
			case 0:
				echo '[thsa-quotation id="' . $post_id . '"]<br/>[thsa-quotation-checkout-button id="' . $post_id . '"]';
				break;
			case 1:
				$post_author_id = get_post_field( 'post_author', $post_id );
				$user = get_userdata($post_author_id);
				echo '<a href="user-edit.php?user_id='.esc_attr( $post_author_id ).'" target="_blank">'.esc_html( $user->user_login ).'</a>';
				break;
		}
    }

    /**
     * 
     * 
     * custom_menu
     * render page for full version information
     * @since 1.2.0
     * 
     * 
     */
    public function custom_menu()
    {
        add_submenu_page(
			'edit.php?post_type=thsa-quote-generator',
			__( 'Full Version', 'thsa-quote-generator' ),
			'<span style="color:#1E7B72; font-weight: bold;">'.esc_html__( 'Pro Version', 'thsa-quote-generator' ).'</span>',
			'manage_options',
			'thsa-qg-pro-custom-menu',
			[$this, 'pro_page']
		);
    }

    /**
     * 
     * pro_page
     * @since 1.2.0
     * 
     * 
     */
    public function pro_page()
    {
       $this->set_template( 'pro-version', [ 'path' => 'admin' ]);
    }



    /**
     * 
     * admin currency
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function admin_currency()
    {
        if( isset( $_GET['post'] ) ){
            $pid = sanitize_text_field( $_GET['post'] );
            if( get_post_type($pid) == 'thsa-quote-generator'){
                $this->currency($pid);
            }
        }
    }


    /**
     * 
     * 
     * exclude_quotation
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function exclude_quotation( $query )
    {
        if( !is_admin() )
            return $query;

        global $pagenow;
     
        if( 'edit.php' == $pagenow && ( get_query_var('post_type') && 'product' == get_query_var('post_type') ) ){
            if(!empty($this->quotation_ids)){
                $query->set( 'post__not_in', $this->quotation_ids );
                $query->set( 'posts_per_page', 19 );
            }
        }
        return $query;
    }

    /**
     * 
     * update counts
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function update_count( $count )
    {

        //update all
        $qg_count = count( $this->quotation_ids );
        $label_count = [ 'all', 'publish' ];

        foreach( $label_count  as $type ){
            $clean = strip_tags( $count[ $type ] );
            $all_count = (int) filter_var( $clean, FILTER_SANITIZE_NUMBER_INT);
            $new_count = $all_count - $qg_count;
            $count[ $type ] = str_replace( $all_count, $new_count, $count[ $type ] );
        }

        return $count;
    }



    /**
     * 
     * 
     * load_admin_assets
     * @since 1.2.0
     * 
     */
    public function load_admin_assets()
    {

        $this->load_js();
        wp_register_script( THSA_QG_PREFIX.'-admin-js', THSA_QG_PLUGIN_URL.'admin/assets/js/thsa-qg-admin.js', array('jquery') );
        wp_enqueue_script( THSA_QG_PREFIX.'-admin-js' );
        wp_enqueue_style( THSA_QG_PREFIX.'-admin-css', THSA_QG_PLUGIN_URL.'admin/assets/css/thsa-qg-admin.css');

        
        $general_settings = $this->setting_class->get_settings('general');

        $default_round = ['round' => 'off', 'decimal' => 0];
        if(isset($general_settings)){
            $default_round = [
                'round' => 'none',
                'decimal' => $this->setting_class->get_settings('woocommerce_price_num_decimals')
            ];
        }
        

        //load thsa js global variables
        wp_localize_script( THSA_QG_PREFIX.'-admin-js', 'thsaqgvars', 
            [
                'ajaxurl'           =>  admin_url( 'admin-ajax.php' ),
                'nonce'             =>  wp_create_nonce( 'thsa-quotation-generator' ),
                'customer_action'   =>  'thsa_qg_customer_list',
                'customer_details'  =>  'thsa_qg_customer_details',
                'product_options'   =>  'thsa_qg_product_select_options',
                'product_from_cat'  =>  'thsa_qg_product_from_cat',
                'product_currency'  =>  'thsa_qg_product_currency_prices',
                'labels'            =>  $this->labels(),
                'save_settings'     => 'thsa_qg_save_settings',
                'round_settings'    => json_encode($default_round),
                'send_email'        => 'thsa_qg_send_email',
                'has_subscriptions'  => $this->support_plugin->has_subscriptions,
                'has_currencies'    => $this->support_plugin->has_currencies,
                'manage_email_content' => 'thsa_qg_manage_email_content',
                'is_admin_edit'     => (isset($_GET['post']))? true : false,
                'save_plate'        => 'thsa_qg_save_plate',
                'default_currency'  => get_woocommerce_currency(),
                'upgraded_features' => $this->pro_features(null, 'upgraded')
            ]
        );

        //load woo's select2
        wp_enqueue_script('selectWoo');
        //load woos admin styling
        wp_enqueue_style('woocommerce_admin_styles');
        
    }
    

    /**
     * 
     * 
     * load_ajax
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function load_ajax()
    {
        add_action('wp_ajax_thsa_qg_customer_list', [$this, 'thsa_qg_customer_list']);
        add_action('wp_ajax_thsa_qg_customer_details', [$this, 'thsa_qg_customer_details']);
        add_action('wp_ajax_thsa_qg_product_select_options', [$this, 'thsa_qg_product_select_options']);
        add_action('wp_ajax_thsa_qg_product_from_cat', [$this, 'thsa_qg_product_from_cat']);
        add_action('wp_ajax_thsa_qg_send_email', [$this, 'thsa_qg_send_email']);

    }


    /**
     * 
     * 
     * register_post_type
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    function register_post_type() {
		$labels = array(
			'name'               => _x( 'Quotation Generator', 'Quotation Generator', 'thsa-quote-generator' ),
			'singular_name'      => _x( 'Quotation Generator', 'Quotation Generator', 'thsa-quote-generator' ),
			'menu_name'          => _x( 'Quotation Generator', 'thsa-quote-generator', 'thsa-quote-generator' ),
			'name_admin_bar'     => _x( 'Quotation Generator', 'Quotation Generator', 'thsa-quote-generator' ),
			'add_new'            => _x( 'Add New', 'Add New Quotation Generator', 'thsa-quote-generator' ),
			'add_new_item'       => __( 'Add New Quotation Generator', 'thsa-quote-generator' ),
			'new_item'           => __( 'New Quotation Generator', 'thsa-quote-generator' ),
			'edit_item'          => __( 'Edit Quotation Generator', 'thsa-quote-generator' ),
			'view_item'          => __( 'View Quotation Generator', 'thsa-quote-generator' ),
			'all_items'          => __( 'All Quotation Generator', 'thsa-quote-generator' ),
			'search_items'       => __( 'Search Quotation Generator', 'thsa-quote-generator' ),
			'parent_item_colon'  => __( 'Parent Quotation Generator', 'thsa-quote-generator' ),
			'not_found'          => __( 'No Quotation Generator found.', 'thsa-quote-generator' ),
			'not_found_in_trash' => __( 'No Quotation Generator found in Trash.', 'thsa-quote-generator' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', 'thsa-quote-generator' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'thsa-quote-generator' ),
			'menu_icon'			 => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNS4xLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iNTAwcHgiIGhlaWdodD0iNTAwcHgiIHZpZXdCb3g9IjAgMCA1MDAgNTAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MDAgNTAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxwYXRoIGZpbGw9IiNBN0FBQUQiIGQ9Ik0yNTAsMi44NzNDMTEzLjUxNiwyLjg3MywyLjg3MywxMTMuNTE2LDIuODczLDI0OS45OTljMCwxMzYuNDg0LDExMC42NDMsMjQ3LjEyNywyNDcuMTI3LDI0Ny4xMjcNCglzMjQ3LjEyNi0xMTAuNjQzLDI0Ny4xMjYtMjQ3LjEyN0M0OTcuMTI2LDExMy41MTYsMzg2LjQ4NCwyLjg3MywyNTAsMi44NzN6IE0yMDksMTcyLjU5OEMxOTAsMTgwLjg3LDE3OS43ODEsMjAwLDE3OS43ODEsMjI4SDIwOQ0KCXYxMjFoLTgxdi04Ni43MzljMC04Ni40ODEsMjctMTMyLjExLDgxLTEzNi44OFYxNzIuNTk4eiBNMzU3LDE3Mi41OThDMzM4LDE4MC44NywzMjguMjA1LDIwMCwzMjguMjA1LDIyOEgzNTd2MTIxaC04MXYtODYuNzM5DQoJYzAtODYuNDgxLDI3LTEzMi4xMSw4MS0xMzYuODhWMTcyLjU5OHoiLz4NCjwvc3ZnPg0K',
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 25,
			'supports'           => array( 'title', 'thumbnail' )
		);
		register_post_type( 'thsa-quote-generator', $args );
	}


    /**
     * 
     * 
     * client_meta
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */

    public function meta_box()
    {

        add_meta_box(
            'thsa_qg_client_meta',
            __('Quotation Builder', 'thsa_quote_generator'),
            [$this, 'quote_meta_section'],
            'thsa-quote-generator',
            'normal',
            'high'
        );

        add_meta_box(
            'thsa_qg_action',
            __('Action', 'thsa_quote_generator'),
            [$this, 'customer_action'],
            'thsa-quote-generator',
            'side',
            'high'
        );

        add_meta_box(
            'thsa_qg_expiry',
            __('Expiry', 'thsa_quote_generator'),
            [$this, 'customer_expiry'],
            'thsa-quote-generator',
            'side',
            'high'
        );

        add_meta_box(
            'thsa_qg_shortcode',
            __('Shortcode', 'thsa_quote_generator'),
            [$this, 'customer_shortcode'],
            'thsa-quote-generator',
            'side',
            'high'
        );

    }

    /**
     * 
     * 
     * generate_tag
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function generate_tag()
    {
        $qg_term = get_term_by( 'slug', $this->quote_slug_tag, $this->product_tag );
        if(!$qg_term){
            wp_insert_term(
                __('Quotation', 'thsa-quote-generator'), // the term 
                $this->product_tag,
                array(
                  'description'=> __('THSA quotation generated tag', 'thsa-quote-generator'),
                  'slug' => $this->quote_slug_tag
                )
            );
        }

        $args = [
            'posts_per_page' => -1,
            'post_type' => 'product',
            'tax_query' => [
                [
                    'taxonomy' => $this->product_tag,
                    'field' => 'slug',
                    'terms' => $this->quote_slug_tag
                ]
            ],
            'post_status' => 'publish',
            'fields' => 'ids'
        ];
        $quotes = get_posts( $args );
        $this->quotation_ids = $quotes;
        
    }

    /**
     * 
     * quote_meta_section
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function quote_meta_section($post)
    {
        $data = get_post_meta($post->ID,'thsa_quotation_data',true);
        
        //get student details
        $customer = [];
        $tab = 'new';
        $customer_ = (isset($data['customer']))? $data['customer'] : null;

        
        
        if(!is_array($customer_) && $customer_){
            $user_id = $data['customer'];
            $customer_details = get_userdata($user_id);
            $billing =  get_user_meta( $user_id, 'billing_address_1', true ); 
            $billing_city = get_user_meta( $user_id, 'billing_city', true ); 
            $billing_postcode = get_user_meta( $user_id, 'billing_postcode', true ); 
            $billing_country = get_user_meta( $user_id, 'billing_country', true ); 
            $billing_state = get_user_meta( $user_id, 'billing_state', true ); 
            $billing = $billing.'<br/>'.$billing_postcode.', '.$billing_city.'<br/>'.$billing_state.', '.$billing_country;
            $customer = [ 
                'fullname' => $customer_details->first_name.' '.$customer_details->last_name,
                'email_address' => $customer_details->user_email,
                'billing_address' => $billing
            ];
            $tab = 'exist';
        
        }elseif(isset($data['customer'])){
            $customer = [
                'firstname' => $data['customer']['firstname'],
                'lastname' => $data['customer']['lastname'],
                'email' => $data['customer']['email']
            ];
        }

        //get products
        $products = [];
        if(!empty($data['products'])){
            foreach($data['products'] as $product){
                $product_details = wc_get_product($product[0]);


                $price_html = $product_details->get_price_html();
                $price_number = $product_details->get_price();
                $price_regular_number = $product_details->get_regular_price();
                $price_sale_number = $product_details->get_sale_price();


                //get prices based on what plugin is installed
                $prices = $this->support_plugin->currency_values(
                    [
                        'product_id' => $product_details->get_id(),
                        'currency' => $data['currency'],
                        'product_prices' => [
                            'price_html' => $price_html,
                            'price_number' => $price_number,
                            'price_regular_number' => $price_regular_number,
                            'price_sale_number' => $price_sale_number
                        ]
                    ]
                );


                $products[$product[0]] = [
                    'id' => $product_details->get_id(),
                    'text' => $product_details->get_id().' - '.$product_details->get_title(),
                    'price_html' => $prices['price_html'],
                    'price_number' => $prices['price_number'],
                    'price_regular_number' => $prices['price_regular_number'],
                    'price_sale_number' => $prices['price_sale_number'],
                    'qty' => $product[1],
                    'amount' => $this->format_number( ['amount' => $prices['price_number'] * $product[1], 'round' => false ] ),
                    'amount_num' => $prices['price_number'] * $product[1]
                ];
                
            }
        }

  
        if(!empty($data['currency'])){
            //do nothing
            $current = $data['currency'];
        }else{
            $current = get_woocommerce_currency();
        }
        $currencies = get_woocommerce_currencies();
        
        $this->pro_features(['currency' => $currencies, 'current' => $current ], 'currency');
        $this->set_template('customer-details',['path' => 'admin', 'post' => $post, 'data' => $customer, 'tab' =>  $tab]);
        $this->set_template('products',['path' => 'admin', 'products' => $products]);
        $this->set_template('discounts',['path' => 'admin','data' => $data, 'taxes' => $this->get_taxes()]);
        $this->set_template('fees',['path' => 'admin', 'data' => $data]);
        $this->set_template('summary',['path' => 'admin', 'data' => $data]);
    }

    /**
     * 
     * get_taxes
     * @since 1.2.0
     * @param 
     * @return array
     * 
     * 
     */
    public function get_taxes()
    {
        $tax_classes           = \WC_Tax::get_tax_classes();
        $tax_class_options     = array();
        $tax_class_options[''] = __( 'Standard', 'woocommerce' );

        if ( ! empty( $tax_classes ) ) {
            foreach ( $tax_classes as $class ) {
                $tax_class_options[ sanitize_title( $class ) ] = $class;
            }
        }
        return $tax_class_options;
    }

    /**
     * 
     * customer_action
     * @since 1.2,0
     * @param
     * @return
     * 
     */
    public function customer_action($post)
    {
        $data = get_post_meta($post->ID,'thsa_quotation_data',true);
        $data = isset($data['customer'])? true : null;

        //get save content from meta
        $saved_email = get_post_meta($post->ID, 'thsa_qg_email_content', true);

        if( !empty($saved_email) ){
            $email_con = $saved_email;
        }else{
            $settings = $this->setting_class->get_settings('email');
            $email_con = null;
            if( !empty($settings['content']) ){
                $email_con = $settings['content'];
            }else{
                $email_con = $this->setting_class->default_email_text;
            }
        }
        $this->set_template('action',['path' => 'admin', 'has' => $data, 'id' => $post->ID, 'email_content' => html_entity_decode($email_con) ]);
    }

    /**
     * 
     * 
     * customer_expiry
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function customer_expiry($post)
    {
        $data = get_post_meta($post->ID,'thsa_quotation_data',true);
        $date = (isset($data['expiry']))? $data['expiry'] : null;
        $this->set_template('expiry',['path' => 'admin', 'date' => $date]);
    }


    /**
     * 
     * 
     * customer_shortcode
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function customer_shortcode($post)
    {
        $data = get_post_meta($post->ID,'thsa_quotation_data',true);
        $shortcodes = null;
        if( isset( $data['customer'] ) ){
            $shortcodes = [
                htmlentities('[thsa-quotation id="'.$post->ID.'"]'),
                htmlentities('[thsa-quotation-checkout-button id="'.$post->ID.'"]')
            ];
        }
        $this->set_template('shortcode',['path' => 'admin','shortcode' => $shortcodes, 'id' => $post->ID]);
    }


    /**
     * 
     * 
     * thsa_qg_customer_list
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function thsa_qg_customer_list()
    {
        if ( ! wp_verify_nonce( $_GET['nonce'], 'thsa-quotation-generator' ) ) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Error 105: Invalid Nonce'
            ]);
            exit();
        }

        $user_s = sanitize_text_field($_GET['search']);

        $users = get_users( [ 'search' => $user_s ] );
        $data = [];

        if($users){
            foreach($users as $user){
                $data[] = [
                    'id' => $user->ID,
                    'text' => $user->display_name
                ];
            }
        }

        echo json_encode($data);
        exit();

    }


    /**
     * 
     * 
     * thsa_qg_customer_details
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     * 
     */
    public function thsa_qg_customer_details()
    {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'thsa-quotation-generator' ) ) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Error 105: Invalid Nonce'
            ]);
            exit();
        }

        $id = sanitize_text_field($_POST['id']);
        $details = get_userdata($id);
        if($details){
            $billing = get_user_meta( $id, 'billing_address_1', true ); 
            $billing_city = get_user_meta( $id, 'billing_city', true ); 
            $billing_postcode = get_user_meta( $id, 'billing_postcode', true ); 
            $billing_country = get_user_meta( $id, 'billing_country', true ); 
            $billing_state = get_user_meta( $id, 'billing_state', true ); 
            $billing = $billing.'<br/>'.$billing_postcode.', '.$billing_city.'<br/>'.$billing_state.', '.$billing_country;
            echo json_encode(
                [
                    'ID' => $details->ID,
                    'fullname' => $details->first_name.' '.$details->last_name,
                    'email_address' => $details->user_email,
                    'billing_address' => $billing
                ]
            );
        }
        exit();
    }

    /**
     * 
     * 
     * thsa_qg_product_select_options
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function thsa_qg_product_select_options()
    {
        if ( ! wp_verify_nonce( $_GET['nonce'], 'thsa-quotation-generator' ) ) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Error 105: Invalid Nonce'
            ]);
            exit();
        }
        
        $s = sanitize_text_field($_GET['search']);
        $type = sanitize_text_field($_GET['filter']);
        $currency = sanitize_text_field($_GET['currency']);

        switch($type){
            case 'product':
                echo $this->product_filter($s, $currency);
                break;
            case 'category':
                echo $this->category_filter($s, $currency);
                break;
            case 'tag':
                echo $this->tag_filter($s, $currency);
                break;
        }

        exit();
       
    }

    /**
     * 
     * 
     * product_filter
     * @since 1.2.0
     * @param string
     * @return json
     * 
     * 
     * 
     */
    public function product_filter($s, $currency)
    {
        $args = [
            'posts_per_page' => -1,
            'post_type' => $this->product_post_type,
            's' => $s,
            'order' => 'ASC',
            'orderby' => 'post_title',
            'post_status' => 'publish'
        ];

        $args = apply_filters('thsa_product_select_arg', $args);
        $products = get_posts($args);

        $data = [];
        if($products){
            foreach($products as $product){

                //extract details
                $product_ = wc_get_product($product->ID);

                //set for no currency plugin is installed
                $price_html = $product_->get_price_html();
                $price_number = $product_->get_price();
                $price_regular_number = $product_->get_regular_price();
                $price_sale_number = $product_->get_sale_price();


                //get prices based on what plugin is installed
                $prices = $this->support_plugin->currency_values(
                    [
                        'product_id' => $product->ID,
                        'currency' => $currency,
                        'product_prices' => [
                            'price_html' => $price_html,
                            'price_number' => $price_number,
                            'price_regular_number' => $price_regular_number,
                            'price_sale_number' => $price_sale_number
                        ]
                    ]
                );
                

                $data[] = [
                    'id' => $product->ID,
                    'text' => $product->ID.' - '.$product->post_title,
                    'price_html' => $prices['price_html'],
                    'price_number' => $prices['price_number'],
                    'price_regular_number' => $prices['price_regular_number'],
                    'price_sale_number' => $prices['price_sale_number']
                ];

            }

            return json_encode($data);
        }

    }

    /**
     * 
     * 
     * thsa_qg_category_select_options
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */

    public function category_filter($s)
    {

        $args = [
            'taxonomy' => $this->product_category,
            'hide_empty' => false,
            'search' => $s,
            'order' => 'ASC',
            'orderby' => 'name'
        ];

        $cats_ = [];
        $cats = get_terms($args);
        if($cats){
            foreach($cats as $cat){
                $cats_[] = [
                    'id' => $cat->term_id,
                    'text' => $cat->name
                ];
            }

            echo json_encode($cats_);
        }
        exit();
    }

     /**
     * 
     * 
     * thsa_qg_category_select_options
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */

    public function tag_filter($s)
    {

        $args = [
            'taxonomy' => $this->product_tag,
            'hide_empty' => false,
            'search' => $s,
            'order' => 'ASC',
            'orderby' => 'name'
        ];

        $cats_ = [];
        $cats = get_terms($args);
        if($cats){
            foreach($cats as $cat){
                $cats_[] = [
                    'id' => $cat->term_id,
                    'text' => $cat->name
                ];
            }

            echo json_encode($cats_);
        }
        exit();
    }


    /**
     * 
     * 
     * thsa_qg_product_from_cat
     * @since 1.2.0
     * @param
     * @return json
     * 
     */
    public function thsa_qg_product_from_cat()
    {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'thsa-quotation-generator' ) ) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Error 105: Invalid Nonce'
            ]);
            exit();
        }

        $cat = sanitize_text_field($_POST['term']);

        $taxon = ($_POST['type'] == 'category')? $this->product_category : $this->product_tag;
        $currency = sanitize_text_field( $_POST['currency'] );

        if($cat){
            $args = [
                'posts_per_page' => -1,
                'post_type' => 'product',
                'tax_query' => [
                    [
                        'taxonomy' => $taxon,
                        'field' => 'term_id',
                        'terms' => $cat 
                    ]
                    ],
                'order' => 'ASC',
                'orderby' => 'post_title',
                'post_status' => 'publish'
            ];

            $products = get_posts($args);
            $data = [];
            if($products){
                foreach($products as $product){
                    $product_ = wc_get_product($product->ID);

                    //set for no currency plugin is installed
                    $price_html = $product_->get_price_html();
                    $price_number = $product_->get_price();
                    $price_regular_number = $product_->get_regular_price();
                    $price_sale_number = $product_->get_sale_price();

                    $prices = $this->support_plugin->currency_values(
                        [
                            'product_id' => $product->ID,
                            'currency' => $currency,
                            'product_prices' => [
                                'price_html' => $price_html,
                                'price_number' => $price_number,
                                'price_regular_number' => $price_regular_number,
                                'price_sale_number' => $price_sale_number
                            ]
                        ]
                    );
                    
                    $data[] = [
                        'id' => $product->ID,
                        'text' => $product->ID.' - '.$product->post_title,
                        'price_html' => $prices['price_html'],
                        'price_number' => $prices['price_number'],
                        'price_regular_number' => $prices['price_regular_number'],
                        'price_sale_number' => $prices['price_sale_number']
                    ];

                }

                echo json_encode($data);
            }
        }
        exit();
    }


    /**
     * 
     * 
     * 
     * save_quote
     * @since 1.2.0
     * @param int
     * @return
     * 
     * 
     * 
     */
    public function save_quote($post_id){

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return;
        }
        
        $quote_data = [];

        //customer details
        $customer_type = ( isset( $_POST['thsa_qg_customer_type'] ) )? sanitize_text_field( $_POST['thsa_qg_customer_type'] ) : null;
        $customer_details = null;
        if($customer_type == 1){
            $customer_details = [
                'firstname' => sanitize_text_field($_POST['thsa_qg_customer_name']),
                'lastname'  => sanitize_text_field($_POST['thsa_qg_customer_lastname']),
                'email'     => sanitize_text_field($_POST['thsa_qg_customer_email'])  
            ];
        }else{
            $customer_id = ( isset( $_POST['thsa_qg_customer_select'] ) )? sanitize_text_field( $_POST['thsa_qg_customer_select'] ) : null;
            $customer_details = sanitize_text_field($customer_id);
        }

        if($customer_details){
            $quote_data['customer'] = $customer_details;
        }

        //currency
        $currency_ = ( isset( $_POST['thsa_qg_currency'] ) )? sanitize_text_field( $_POST['thsa_qg_currency'] ) : get_woocommerce_currency();
        $quote_data['currency'] = sanitize_text_field($currency_);

        //products
        //sanitize product id
        $saved_products = [];
        if(!empty($_POST['thsa_qg_added_product'])){
            foreach($_POST['thsa_qg_added_product'] as $index => $product_id){
                $qty = sanitize_text_field($_POST['thsa_qg_added_product_qty'][$index]);
                $saved_products[] = [sanitize_text_field($product_id), $qty];
            }
            $quote_data['products'] = $saved_products;
        }

        $expiry = (!empty($_POST['thsa_qg_expiry']))? sanitize_text_field($_POST['thsa_qg_expiry']) : null;
        if($expiry){
            $quote_data['expiry'] = $expiry;
        }

        //discounts
        $payment_type = (!empty($_POST['thsa_qg_payment_type']))? sanitize_text_field($_POST['thsa_qg_payment_type']) : null;
        if($payment_type){
            $quote_data['payment_type'] = $payment_type;
        }

        $payment_type_edit = (!empty($_POST['allow_payment_type_edit']))? sanitize_text_field($_POST['allow_payment_type_edit']) : null;
        if($payment_type_edit){
            $quote_data['allow_payment_type_edit'] = $payment_type_edit;
        }

        $discount_amount = (!empty($_POST['thsa_qg_fix_amount']))? sanitize_text_field($_POST['thsa_qg_fix_amount']) : null; 
        if($discount_amount){
            $quote_data['fixed_amount_discount'] = $discount_amount;
        }

        $percent_discount = (!empty($_POST['thsa_qg_percent_amount']))? sanitize_text_field($_POST['thsa_qg_percent_amount']) : null;
        if($percent_discount){
            $quote_data['percent_amount_discount'] = $percent_discount;
        }

        if(isset($quote_data['payment_type'])){
            if($quote_data['payment_type'] == 'plan'){

                //resanitize $_POST
                $post__ = array_map( function( $data ){
                    if( is_array( $data ) ){
                        return array_map( function( $file ){
                            return sanitize_text_field( $file );
                         }, $data );
                    }
                    return $data;
                }, $_POST);

                $quote_data = $this->pro_features( [ 'post' => $post__, 'quote_data' => $quote_data ], 'payment_plan_settings_data_save' );
                $plan_id = $this->support_plugin->generate_plan($post_id, $quote_data);
            }
        }

        
        if(isset($plan_id)){
            $quote_data['plan_product_id'] = $plan_id;
        }
       

        //fees
        $fee_data = [];
        if(!empty($_POST['thsa_qg_added_fee'])){
            foreach($_POST['thsa_qg_added_fee'] as $fee){
                $fee_ = $this->sanitize_json($fee);
                $fee_data[] = $fee_;
            }
        }
        $quote_data['fees'] = (!empty($fee_data))? $fee_data : null;


        if(!empty($quote_data)){
            update_post_meta($post_id, 'thsa_quotation_data', $quote_data);
        }else{
            delete_post_meta($post_id, 'thsa_quotation_data');
        }

        //create coupon for quotation
        if( isset($quote_data['payment_type']) ){
            if($quote_data['payment_type'] == 'upfront'){
                $code = 'quotation-'.$post_id;
                $this->generate_coupon($code, $discount_amount);
            }
        }

    }

    



    /**
     * 
     * 
     * generate_coupon
     * @since 1.2.0
     * @param $code string - name of coupon
     * @param $amount string - amount of coupon
     * @param $type string - type of coupon
     * @return
     * 
     * 
     */
    public function generate_coupon($code = null, $amount = null, $type = 'fixed_cart')
    {
        if(!$code || !$code)
            return;


        if(!$amount)
            return;

        //check of code exist
        $code_id = wc_get_coupon_id_by_code($code);
        if($code_id > 0){
            //update instead
            $this->update_coupon_meta($code_id, $type, $amount);
            return;
        }
            

        $coupon_code = $code; // Code - perhaps generate this from the user ID + the order ID
        $coupon = array(
            'post_title' => $coupon_code,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type'     => 'shop_coupon'
        );    

        $new_coupon_id = wp_insert_post( $coupon );
        $this->update_coupon_meta($new_coupon_id, $type, $amount);
        return $new_coupon_id;
    }

    /**
     * 
     * 
     * update_coupon_meta
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function update_coupon_meta($new_coupon_id, $discount_type, $amount)
    {
        //get settings
        $get_settings = get_option('thsa_quotation_settings');
        $individual_use = 'yes';
        $product_ids = '';
        $exclude_product_ids = '';
        $usage_limit = 1;
        $usage_limit_per_user = 1;
        $expiry_date = '';
        $apply_before_tax = 'yes';
        $free_shipping = '';
        if(isset($get_settings['coupon'])){
            if(isset($get_settings['coupon']['individual_usage'])){
                $individual_use = $get_settings['coupon']['individual_usage'];
            }
            if(isset($get_settings['coupon']['product_ids'])){
                $product_ids = $get_settings['coupon']['product_ids'];
            }
            if(isset($get_settings['coupon']['exclude_ids'])){
                $exclude_product_ids = $get_settings['coupon']['exclude_ids'];
            }
            if(isset($get_settings['coupon']['usage_limit'])){
                $usage_limit = $get_settings['coupon']['usage_limit'];
            }
            if(isset($get_settings['coupon']['expiry_date'])){
                $expiry_date = $get_settings['coupon']['expiry_date'];
            }
            if(isset($get_settings['coupon']['before_tax'])){
                $apply_before_tax = $get_settings['coupon']['before_tax'];
            }
            if(isset($get_settings['coupon']['free_shipping'])){
                $free_shipping = $get_settings['coupon']['free_shipping'];
            }
        }

        // Add meta
        update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
        update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
        update_post_meta( $new_coupon_id, 'individual_use', $individual_use );
        update_post_meta( $new_coupon_id, 'product_ids', $product_ids );
        update_post_meta( $new_coupon_id, 'exclude_product_ids', $exclude_product_ids );
        update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
        update_post_meta( $new_coupon_id, 'usage_limit_per_user', $usage_limit_per_user );
        update_post_meta( $new_coupon_id, 'expiry_date', $expiry_date );
        update_post_meta( $new_coupon_id, 'apply_before_tax', $apply_before_tax );
        update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping );

        //update currency
        $this->support_plugin->coupon_currency($new_coupon_id, $amount);

    }


    /**
     * 
     * email_quotation
     * @since 1.2.0
     * @param
     * @return
     * 
     */
    public function email_quotation($attr)
    {

        $id = sanitize_text_field( $attr['q_id'] );
        $res = $this->render_quotation( $id );

        if( is_array($res) ){
            ob_start();
                $this->set_template('shortcodes/quotation', $res );
            return ob_get_clean();
        }else{
            ob_start();
                echo wp_kses_post( $res );
            return ob_get_clean();
        }

    }

    
    /**
     * 
     * 
     * thsa_qg_send_email
     * @since 1.2.0
     * @param
     * @return
     * 
     * 
     */
    public function thsa_qg_send_email()
    {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'thsa-quotation-generator' ) ) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Error 105: Invalid Nonce'
            ]);
            exit();
        }

        

        $id = sanitize_text_field($_POST['id']);
        $type = sanitize_text_field($_POST['type']);
        $get_settings = $this->setting_class->get_settings('email');
        //$email = null;

        $post_email = get_post_meta($id, 'thsa_qg_email_content', true);
        if(!empty($post_email)){
            $get_settings['content'] = $post_email;
        }

        $quote_user = get_post_meta($id, 'thsa_quotation_data', true);
        $customer_details = [];
        if(isset($quote_user['customer'])){

            if(is_array($quote_user['customer'])){
                $customer_details = [
                    'fullname' => $quote_user['customer']['firstname'],
                    'email' => $quote_user['customer']['email']
                ];

                $customer_details = apply_filters('thsa_quotation_email_customer_details', $customer_details, $quote_user );
            }else{
                if(is_numeric($quote_user['customer'])){
                    $user = get_userdata($quote_user['customer']);
                    $customer_details = [
                        'fullname' => $user->first_name.' '.$user->last_name,
                        'email' => $user->user_email
                    ];

                    $customer_details = apply_filters('thsa_quotation_email_customer_details', $customer_details, $user );
                }else{
                    echo json_encode([
                        'status' => 'failed',
                        'message' => __('Error 108: No customer is found','thsa-quote-generator')
                    ]);
                    exit();
                }
            }                
        }

        if(isset($get_settings)){
            //process shortcodes
            if(!empty($customer_details)){
                $get_settings['content'] = str_replace('[thsa-quotation-user-name]', $customer_details['fullname'], $get_settings['content']);
            }

            //render the quotation
            if(strpos($get_settings['content'],'[thsa-quotation]') !== false && $type != 'send'){
                $quotation__ = do_shortcode('[thsa-quotation-email q_id='.$id.']');
                $get_settings['content'] = str_replace('[thsa-quotation]', htmlentities($quotation__), $get_settings['content']);
            }else{
                $quotation__ = do_shortcode('[thsa-quotation-email q_id='.$id.']');
                $get_settings['content'] = html_entity_decode(stripslashes($get_settings['content']));
                $get_settings['content'] = str_replace('[thsa-quotation]', $quotation__, $get_settings['content']);
            }

            if(strpos($get_settings['content'],'[thsa-quotation-checkout-button]') !== false && $type != 'send'){
                $button__ = do_shortcode('[thsa-quotation-checkout-button id='.$id.' from_email=1]');
                $get_settings['content'] = str_replace('[thsa-quotation-checkout-button]', htmlentities($button__), $get_settings['content']);
            }else{
                $button__ = do_shortcode('[thsa-quotation-checkout-button id='.$id.' from_email=1]');
                $get_settings['content'] = html_entity_decode(stripslashes($get_settings['content']));
                $get_settings['content'] = str_replace('[thsa-quotation-checkout-button]', $button__, $get_settings['content']);
            }

            if($type == 'send'){
                echo $this->send_wp_email(
                    [
                       'email' => $customer_details['email'],
                       'title' => $get_settings['title'],
                       'content' => $get_settings['content'],
                       'from_email' => $get_settings['from_email'],
                       'fullname' => $customer_details['fullname']
                    ]
                );
                exit();
                
            }else{
                echo json_encode([
                    'status' => 'success',
                    'message' => html_entity_decode($get_settings['content'])
                ]);
                exit();
            }

            
        }else{

            $email_default_content = $this->setting_class->default_email_text;
            $email_default_title = $this->setting_class->default_email_title;
            $email_default_from_email = $this->setting_class->default_admin_email;

            if(!empty($customer_details)){
                $email_default_content = str_replace('[thsa-quotation-user-name]', $customer_details['fullname'], $email_default_content);
            }
            
            if( $type == 'send' ){
                $quotation__ = do_shortcode('[thsa-quotation-email q_id='.$id.']');
                $get_text = html_entity_decode( stripslashes ($email_default_content) );
                $get_text = str_replace('[thsa-quotation]', $quotation__, $get_text);
            }else{
                $quotation__ = do_shortcode('[thsa-quotation-email q_id='.$id.']');
                $get_text = str_replace('[thsa-quotation]', htmlentities($quotation__), $email_default_content);
            }   

            if( $type == 'send' ){                
                $button__ = do_shortcode('[thsa-quotation-checkout-button id='.$id.' from_email=1]');
                $button__ = html_entity_decode( stripslashes ($button__ ) );
                $get_text = str_replace('[thsa-quotation-checkout-button]', $button__, $get_text);

            }else{
                $button__ = do_shortcode('[thsa-quotation-checkout-button id='.$id.' from_email=1]');
                $get_text = str_replace('[thsa-quotation-checkout-button]', htmlentities($button__), $get_text);
            }
           
            
            if($type == 'send'){

                
                
                echo $this->send_wp_email(
                    [
                       'email' => $customer_details['email'],
                       'title' => $email_default_title,
                       'content' => $get_text,
                       'from_email' => $email_default_from_email,
                       'fullname' => $customer_details['fullname']
                    ]
                );
                exit();

            }else{
                echo json_encode([
                    'status' => 'success',
                    'message' => html_entity_decode($get_text)
                ]);
                exit();
            }
        }

    }

    /**
     * 
     * send_wp_email
     * @since 1.2.0
     * @param mixed
     * @return json
     * 
     * 
     */
    public function send_wp_email( $data = [] )
    {
        if( empty( $data ) )
            return;

        //send email
        if(isset($data['email'])){

            $to = $data['email'];
            $subject = $data['title'];
            $body = $data['content'];
            $headers = array( 'Content-Type: text/html; charset=UTF-8','From: '.$data['from_email'] );

            $res = wp_mail( $to, $subject, $body, $headers );

            $status = ( $res )? 'success' : 'failed';
            $message = ( $res )? '' : __('Failed to send email please check your quotation settings or your SMTP' ,'thsa-quote-generator');
            return json_encode([
                'status' => $status,
                'message' => $message
            ]);

        }else{
            return json_encode([
                'status' => 'failed',
                'message' => __( 'Error 013: No email has been sent','thsa-quote-generator' )
            ]);
        }

    }

}

?>