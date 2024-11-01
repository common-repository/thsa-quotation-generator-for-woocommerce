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
namespace thsa\qg\front\shortcodes;
use thsa\qg\common\thsa_qg_common_class;

defined( 'ABSPATH' ) or die( 'No access area' );

class thsa_qg_public_shortcodes extends thsa_qg_common_class
{
    public function __construct()
    {
        add_shortcode('thsa-quotation-user-name', [$this, 'get_user_details'] );
        add_shortcode('thsa-quotation-checkout-button', [$this, 'render_button'] );
        add_shortcode('thsa-quotation', [$this, 'quotation']);
    }

    /**
     * 
     * customer_name
     * @since 1.2.0
     * @param array
     * @return string
     * 
     * 
     */
    public function customer_name($attr)
    {
        $content = null;

        if(empty($attr))
            $content = __('#Error: No customer ID were found','thsa_quote_generator');

        if(isset($attr['id'])){
            $details = get_userdata($attr['id']);
            $content = $details->first_name.' '.$details->last_name;
        }

        ob_start();
            echo esc_html( $content );
        return ob_get_clean();
    }

    /**
     * 
     * 
     * quotation
     * @since 1.2.0
     * @param array
     * @return html
     * 
     * 
     */
    public function quotation( $attr )
    {

        $content = null;
        if(empty($attr))
            $content = __('#Error: No quotation ID available','thsa_quote_generator');


        if(isset($_GET['q_id'])){
            $attr = ( !is_array( $attr ) )? [] : $attr;
            $attr['id'] = sanitize_text_field( $_GET['q_id'] );
        }

        if(isset($attr['id'])){

            $attr['id'] = sanitize_text_field( $attr['id'] );

            $res = $this->render_quotation( $attr['id'] );

            if( is_array($res) ){
                ob_start();
                    $this->set_template('shortcodes/quotation', $res );
                return ob_get_clean();
            }else{
                ob_start();
                    echo wp_kses_post( $res );
                return ob_get_clean();
            }

        }else{
            ob_start();
                echo esc_html( $content );
            return ob_get_clean();
        }
        
    }

    /**
     * 
     * 
     * get_user_details
     * @since 1.2.0
     * @param int
     * @return 
     * 
     * 
     */

    public function get_user_details( $attr )
    {

        $id = ( isset( $_GET['q_id'] ) )? sanitize_text_field( $_GET['q_id'] ) : null;
        $id = ( !empty( $attr['id'] ) )? sanitize_text_field( $attr['id'] ) : $id;

        if( empty( $id ) || !$id ){
            return;
        }
            

        $data = get_post_meta( $id, 'thsa_quotation_data', true );

        if( !isset( $data['customer'] ) )
            return;

       
        $user = '';
        if( is_array( $data['customer'] ) ){
            $user = $data['customer']['firstname'];
        }else{
            $userdetails = get_userdata( $data['customer'] );
            $user = $userdetails->first_name;
        }

        /**
         * 
         * thsa_quote_user_name
         * @since 1.2.0
         * @param string - original value
         * @param mixed - array if user is new ID if existing user
         * 
         */
        $user = apply_filters( 'thsa_quote_user_name', $user, $data['customer'] );

        return $user;
    }

    /**
     * 
     * 
     * render_button
     * @since 1.2.0
     * @param int
     * @return
     * 
     * 
     */
    public function render_button( $attr = null )
    {
        $id = ( isset( $_GET['q_id'] ) )? sanitize_text_field( $_GET['q_id'] ) : null;
        $id = ( !empty( $attr['id'] ) )? sanitize_text_field( $attr['id'] ) : $id;

        if( empty( $id ) || !$id ){
            return;
        }

        $params = ['qid' => $id, 'path' => 'public' ];
        if( isset($attr['from_email']) ){
            $params['from_email'] = true;
            $settings = get_option('thsa_quotation_settings');
            $params['checkout_url'] = ( isset($settings['checkout']) )? get_permalink($settings['checkout']).'?quotation='.$id : get_site_url().'/checkout?quotation='.$id;
        }

        ob_start();
            $this->set_template( 'shortcodes/button', $params );
        return ob_get_clean();
    }
}
?>