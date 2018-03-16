<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Ivole_Email_Coupon' ) ) :

if ( is_file( plugin_dir_path( __DIR__ ) . '/woocommerce/includes/libraries/class-emogrifier.php' ) ) {
	include_once( plugin_dir_path( __DIR__ ) . '/woocommerce/includes/libraries/class-emogrifier.php' );
} else {

}

require_once('class-ivole-email.php');

/**
 * Reminder email for product reviews
 */
class Ivole_Email_Coupon {

	public $id;
	public $to;
	public $heading;
	public $subject;
	public $template_html;
	public $template_items_html;
	public $from;
	public $from_name;
	public $bcc;
	public $replyto;
	public $language;
	public $footer;
	public $find = array();
	public $replace = array();
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id               = 'ivole_review_coupon';
		$this->heading          = strval( get_option( 'ivole_email_heading_coupon', __( 'Thank You for Leaving a Review', 'ivole' ) ) );
		$this->subject          = strval( get_option( 'ivole_email_subject_coupon', '[{site_title}] ' . __( 'Discount Coupon for You', 'ivole' ) ) );
		$this->template_html    = Ivole_Email::plugin_path() . '/templates/email_coupon.php';
		$this->language					= get_option( 'ivole_language', 'EN' );
		$this->from_name				= get_option( 'ivole_email_from_name', Ivole_Email_Footer::get_from_name() );
		$this->footer						= get_option( 'ivole_email_footer', Ivole_Email_Footer::get_text() );

		$this->find['site-title'] = '{site_title}';
		$this->find['customer-first-name']  = '{customer_first_name}';
		$this->find['customer-name'] = '{customer_name}';
		$this->find['coupon-code'] = '{coupon_code}';
		$this->find['discount-amount'] = '{discount_amount}';
		$this->replace['site-title'] = Ivole_Email::get_blogname();

		//qTranslate integration
		if( function_exists( 'qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage' ) ) {
			$this->heading = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->heading );
			$this->subject = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->subject );
			$this->from_name = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->from_name );
			$this->footer = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->footer );
			if( 'QQ' === $this->language ) {
				global $q_config;
				$this->language = strtoupper( $q_config['language'] );
			}
		}
	}

	/**
	 * Trigger 2.
	 */
	public function trigger2( $order_id, $to = null, $coupon_code = "", $discount_amount = "" ) {
		$api_url = '';
		$this->from = get_option( 'ivole_email_from', 'feedback@cusrev.com' );

		// check if Reply-To address needs to be added to email
		$this->replyto = get_option( 'ivole_coupon_email_replyto', get_option( 'admin_email' ) );
		if( filter_var( $this->replyto, FILTER_VALIDATE_EMAIL ) ) {
			$this->replyto = $this->replyto;
		} else {
			$this->replyto = get_option( 'admin_email' );
		}

		if ( $order_id ) {
			$coupon_type = get_option( 'ivole_coupon_type', 'static' );

			if( $coupon_type === 'static' ) {
				$coupon_id = get_option( 'ivole_existing_coupon', 0 );
			} else {
				$coupon_id = $this->generate_coupon( $to, $review_id );
			}
			if( $coupon_id > 0 && get_post_type( $coupon_id ) === 'shop_coupon' && get_post_status( $coupon_id ) === 'publish' && $order_id > 0 ) {
				$this->to = $to;
				$order = new WC_Order( $order_id );
				$customer_first_name = '';
				$customer_last_name = '';

				if( method_exists( $order, 'get_billing_email' ) ) {
					// Woocommerce version 3.0 or later
					$this->replace['customer-first-name'] = $order->get_billing_first_name();
					$customer_first_name = $order->get_billing_first_name();
					$customer_last_name = $order->get_billing_last_name();
					$this->replace['customer-name'] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
					$order_date = date_i18n( 'd.m.Y', strtotime( $order->get_date_created() ) );
					$order_currency = $order->get_currency();
				} else {
					// Woocommerce before version 3.0
					$this->replace['customer-first-name'] = get_post_meta( $order_id, '_billing_first_name', true );
					$customer_first_name = get_post_meta( $order_id, '_billing_first_name', true );
					$customer_last_name = get_post_meta( $order_id, '_billing_last_name', true );
					$this->replace['customer-name'] = get_post_meta( $order_id, '_billing_first_name', true ) . ' ' . get_post_meta( $order_id, '_billing_last_name', true );
					$order_date = date_i18n( 'd.m.Y', strtotime( $order->order_date ) );
					$order_currency = $order->get_order_currency();
				}

				$coupon_code = get_post_field( 'post_title', $coupon_id );
				$discount_type = get_post_meta( $coupon_id, 'discount_type', true );
				$discount_amount = get_post_meta( $coupon_id, 'coupon_amount', true );
				$discount_string = "";
				if( $discount_type == "percent" && $discount_amount > 0 ){
					$discount_string = $discount_amount . "%";
				} elseif( $discount_amount > 0 ) {
					$discount_string = trim( strip_tags( wc_price( $discount_amount, array( 'currency' => get_option( 'woocommerce_currency' ) ) ) ) );
				}

				$this->replace['coupon-code'] = $coupon_code;
				$this->replace['product-name'] = $product_name;
				$this->replace['discount-amount'] = $discount_string;

				$bcc_address = get_option( 'ivole_coupon_email_bcc', '' );
				if( filter_var( $bcc_address, FILTER_VALIDATE_EMAIL ) ) {
					$this->bcc = $bcc_address;
				}

				$message = $this->get_content();
				$message = $this->replace_variables( $message );

				$data = array(
					'token' => '164592f60fbf658711d47b2f55a1bbba',
					'shop' => array( "name" => Ivole_Email::get_blogname(),
				 		'domain' => Ivole_Email::get_blogurl(),
					 	'country' => apply_filters( 'woocommerce_get_base_location', get_option( 'woocommerce_default_country' ) ) ),
					'email' => array( 'to' => $this->to,
						'from' => $this->from,
						'fromText' => $this->from_name,
						'bcc' => $this->bcc,
						'replyTo' => $this->replyto,
				 		'subject' => $this->replace_variables( $this->subject ),
						'header' => $this->replace_variables( $this->heading ),
						'body' => $message,
					 	'footer' => $this->footer ),
					'customer' => array( 'firstname' => $customer_first_name,
						'lastname' => $customer_last_name ),
					'order' => array( 'id' => strval( $order_id ),
				 		'date' => $order_date,
						'currency' => $order_currency,
					 	'items' => Ivole_Email::get_order_items2( $order ) ),
					'discount' => array('type' => $discount_type,
						'amount' => $discount_amount,
						'code' => $coupon_code ),
					'colors' => array(
						'email' => array(
							"bg" => get_option( 'ivole_email_coupon_color_bg', '#0f9d58' ),
							'text' => get_option( 'ivole_email_coupon_color_text', '#ffffff' )
						)
					),
					'language' => $this->language
				);
				$api_url = 'https://z4jhozi8lc.execute-api.us-east-1.amazonaws.com/v1/review-discount';
			} else {
				return false;
			}
		} else {
			// no review_id means this is a test and we should provide some dummy information
			$this->to = $to;
			$this->replace['customer-first-name'] = __( 'Jane', 'ivole' );
			$this->replace['customer-name'] = __( 'Jane Doe', 'ivole' );
			$this->replace['coupon-code'] = $coupon_code;
			$this->replace['discount-amount'] = ( $discount_amount == "" ) ? '10%' : $discount_amount;

			$message = $this->get_content();
			$message = $this->replace_variables( $message );

			$data = array(
				'token' => '164592f60fbf658711d47b2f55a1bbba',
				'shop' => array( "name" => Ivole_Email::get_blogname(),
			 		'domain' => Ivole_Email::get_blogurl() ),
				'email' => array( 'to' => $to,
					'from' => $this->from,
					'fromText' => $this->from_name,
					'replyTo' => $this->replyto,
			 		'subject' => $this->replace_variables( $this->subject ),
					'header' => $this->replace_variables( $this->heading ),
					'body' => $message,
				 	'footer' => $this->footer ),
				'colors' => array(
					'email' => array(
						"bg" => get_option( 'ivole_email_coupon_color_bg', '#0f9d58' ),
						'text' => get_option( 'ivole_email_coupon_color_text', '#ffffff' )
					)
				),
				'language' => $this->language
			);
			$api_url = 'https://z4jhozi8lc.execute-api.us-east-1.amazonaws.com/v1/test-email';
		}
		$license = get_option( 'ivole_license_key', '' );
		if( strlen( $license ) > 0 ) {
			$data['licenseKey'] = $license;
		}
		$data_string = json_encode( $data );
		//error_log( $data_string );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $api_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen( $data_string ) )
		);
		$result = curl_exec( $ch );
		if( false === $result ) {
			return array( 2, curl_error( $ch ) );
		}
		//error_log( $result );
		$result = json_decode( $result );
		if( $result->status === 'OK' ) {
			return 0;
		} else {
			return 1;
		}
	}

	/**
	 * Generate a coupon for the given email
	 *
	 * @access public
	 * @return id of generated coupon | false
	 */
	public function generate_coupon($to,$review_id=0) {
		$unique_code = (!empty( $to)) ? strtoupper( uniqid( substr( preg_replace('/[^a-z0-9]/i', '', sanitize_title( $to ) ), 0, 5 ) ) ) : strtoupper( uniqid() );
		$coupon_args = array(
			'post_title' 	=> $unique_code,
			'post_content' 	=> '',
			'post_status' 	=> 'publish',
			'post_author' 	=> 1,
			'post_type'     => 'shop_coupon'
		);
		$coupon_id = wp_insert_post( $coupon_args );
		if($coupon_id>0){
			$type=get_option('ivole_coupon__discount_type','percent');
			update_post_meta( $coupon_id, 'discount_type', $type );
			$amount=floatval(get_option('ivole_coupon__coupon_amount',0));
			update_post_meta( $coupon_id, 'coupon_amount', $amount  );
			$individual_use=get_option('ivole_coupon__individual_use','no');
			update_post_meta( $coupon_id, 'individual_use', $individual_use );
			$product_ids=get_option('ivole_coupon__product_ids',array());
			$product_ids=implode(",",$product_ids);
			update_post_meta( $coupon_id, 'product_ids', $product_ids );
			$exclude_product_ids=get_option('ivole_coupon__exclude_product_ids',array());
			$exclude_product_ids=implode(",",$exclude_product_ids);
			update_post_meta( $coupon_id, 'exclude_product_ids', $exclude_product_ids );
			$usage_limit=get_option('ivole_coupon__usage_limit',0);
			update_post_meta( $coupon_id, 'usage_limit', $usage_limit );
			update_post_meta( $coupon_id, 'usage_limit_per_user', $usage_limit );
			$days=intval(get_option('ivole_coupon__expires_days',0));
			if($days>0) {
				$today = time();
				$expiry_date = date('Y-m-d', $today + 24 * 60 * 60 * $days);
				update_post_meta($coupon_id, 'expiry_date', $expiry_date);
				$date_expires = strtotime($expiry_date);
				update_post_meta($coupon_id, 'date_expires', $date_expires);
			}else{
				update_post_meta($coupon_id, 'expiry_date', NULL);
				update_post_meta($coupon_id, 'date_expires', '');
			}
			update_post_meta( $coupon_id, 'customer_email', array( $to ) );
			$free_shipping=get_option('ivole_coupon__free_shipping','no');
			update_post_meta( $coupon_id, 'free_shipping', $free_shipping );

			$exclude_sale_items=get_option('ivole_coupon__exclude_sale_items','no');
			update_post_meta( $coupon_id, 'exclude_sale_items', $exclude_sale_items );

			$product_categories=get_option('ivole_coupon__product_categories',array());
			update_post_meta( $coupon_id, 'product_categories', $product_categories  );

			$exclude_product_categories=get_option('ivole_coupon__excluded_product_categories',array());
			update_post_meta( $coupon_id, 'exclude_product_categories', $exclude_product_categories );

			$minimum_amount=floatval(get_option('ivole_coupon__minimum_amount',0));
			if($minimum_amount>0){
				update_post_meta( $coupon_id, 'minimum_amount', $minimum_amount );
			}else{
				update_post_meta( $coupon_id, 'minimum_amount', '' );
			}

			$maximum_amount=floatval(get_option('ivole_coupon__maximum_amount',0));
			if($maximum_amount>0){
				update_post_meta( $coupon_id, 'maximum_amount', $maximum_amount );
			}else{
				update_post_meta( $coupon_id, 'maximum_amount', '' );
			}

			update_post_meta( $coupon_id, 'generated_from_review_id', $review_id );
			update_post_meta( $coupon_id, '_ivole_auto_generated', 1 );
		}
		return $coupon_id;
	}
	/**
	 * Get content
	 *
	 * @access public
	 * @return string
	 */
	public function get_content() {
		ob_start();
		//$email_heading = $this->heading;
		$def_body = Ivole_Email::$default_body_coupon;
		include( $this->template_html );
		return ob_get_clean();
	}

	public static function plugin_path() {
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
  }

	public function send() {

		add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		$subject = $this->replace_variables( $this->subject );
		$message = $this->get_content();
		$message = $this->replace_variables( $message );
		$message = $this->style_inline( $message );
		$headers = array();
		if($this->bcc) {
			$headers[] = 'Bcc: ' . $this->bcc;
		}
		$return  = wp_mail( $this->to, $subject, $message, $headers, array() );

		remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		return $return;
	}

	public function get_from_address() {
		$from_address = apply_filters( 'woocommerce_email_from_address', get_option( 'woocommerce_email_from_address' ), $this );
		return sanitize_email( $from_address );
	}

	public function get_from_name() {
		$from_name = apply_filters( 'woocommerce_email_from_name', get_option( 'woocommerce_email_from_name' ), $this );
		return wp_specialchars_decode( esc_html( $from_name ), ENT_QUOTES );
	}

	public function get_content_type() {
		return 'text/html';
	}

	public function style_inline( $content ) {
		// make sure we only inline CSS for html emails
		if ( in_array( $this->get_content_type(), array( 'text/html', 'multipart/alternative' ) ) && class_exists( 'DOMDocument' ) ) {
			ob_start();
			wc_get_template( 'emails/email-styles.php' );
			$css = apply_filters( 'woocommerce_email_styles', ob_get_clean() );

			// apply CSS styles inline for picky email clients
			try {
				$emogrifier = new Emogrifier( $content, $css );
				$content    = $emogrifier->emogrify();
			} catch ( Exception $e ) {
				$logger = new WC_Logger();
				$logger->add( 'emogrifier', $e->getMessage() );
			}
		}
		return $content;
	}

	public function replace_variables( $input ) {
		return str_replace( $this->find, $this->replace, __( $input ) );
	}

	public function get_last_order_with_product( $product_id, $email ) {
		global $wpdb;
		$statuses = array( 'processing', 'completed' );
		$result = $wpdb->get_col( "
        SELECT p.ID FROM {$wpdb->posts} AS p
        INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
        WHERE p.post_status IN ( 'wc-" . implode( "','wc-", $statuses ) . "' )
        AND pm.meta_key IN ( '_billing_email' )
        AND im.meta_key IN ( '_product_id', '_variation_id' )
        AND im.meta_value IN ('" . $product_id . "')
        AND pm.meta_value IN ( '" . $email . "' )
    " );
		$result = array_map( 'absint', $result );
		if( count( $result ) > 0 ) {
			error_log( 'ORDER ID: ' . print_r( max( $result ), true ) );
			return max( $result );
		} else {
			return -1;
		}
	}

}

endif;
