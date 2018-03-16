<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Ivole_Endpoint' ) ) :

	require_once('class-ivole-email.php');
	require_once('class-ivole-email-coupon.php');

	class Ivole_Endpoint {
	  public function __construct() {
			add_action( 'rest_api_init', array( $this, 'init_endpoint' ) );
	  }

		public function init_endpoint( ) {
			$this->register_routes();
		}

		public function register_routes() {
	    $version = '1';
	    $namespace = 'ivole/v' . $version;
	    register_rest_route( $namespace, '/review', array(
	      array(
	        'methods'         => WP_REST_Server::CREATABLE,
	        'callback'        => array( $this, 'create_review' ),
	        'permission_callback' => array( $this, 'create_review_permissions_check' ),
	        'args'            => array(),
	      ),
	    ) );
	  }

		public function create_review( $request ) {
			$body = $request->get_body();
			$body2 = json_decode( $body );
			if( json_last_error() === JSON_ERROR_NONE ) {
				if( isset( $body2->key ) && isset( $body2->order ) ) {
					if( isset( $body2->order->id ) && isset( $body2->order->items ) ) {
						//error_log( print_r( $body2, true ) );
						$order_id = intval( $body2->order->id );
						$order = new WC_Order( $order_id );
						$customer_name = '';
						$customer_first_name = '';
						$customer_last_name = '';
						$customer_email = '';

						if( method_exists( $order, 'get_billing_email' ) ) {
							// Woocommerce version 3.0 or later
							$customer_email = $order->get_billing_email();
							$customer_first_name = $order->get_billing_first_name();
							$customer_last_name = $order->get_billing_last_name();
							$customer_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
							$order_date = date_i18n( 'd.m.Y', strtotime( $order->get_date_created() ) );
							$order_currency = $order->get_currency();
						} else {
							// Woocommerce before version 3.0
							$customer_email = get_post_meta( $order_id, '_billing_email', true );
							$customer_first_name = get_post_meta( $order_id, '_billing_first_name', true );
							$customer_last_name = get_post_meta( $order_id, '_billing_last_name', true );
							$customer_name = get_post_meta( $order_id, '_billing_first_name', true ) . ' ' . get_post_meta( $order_id, '_billing_last_name', true );
							$order_date = date_i18n( 'd.m.Y', strtotime( $order->order_date ) );
							$order_currency = $order->get_order_currency();
						}

						//if customer specified preference for display name, take into account their preference
						if( !empty( $body2->order->display_name ) ) {
							$customer_name = strval( $body2->order->display_name );
						}

						if( is_array( $body2->order->items ) ) {
							$num_items = count( $body2->order->items );
							$comment_approved = 1;
							$moderation_enabled = get_option( 'ivole_enable_moderation', 'no' );
							if( $moderation_enabled === 'yes' ) {
								$comment_approved = 0;
							}
							$result = true;
							$previous_comments_exist = false;
							for( $i = 0; $i < $num_items; $i++ ) {
								//error_log( print_r( $body2->order->items[$i], true) );
								if( isset( $body2->order->items[$i]->rating ) && isset( $body2->order->items[$i]->id ) ) {
									// check if review text was provided, if not then we will be adding an empty comment
									$comment_text = '';
									if( isset( $body2->order->items[$i]->comment ) ) {
										$comment_text = strval( $body2->order->items[$i]->comment );
									}

									// check if a review has already been submitted for this product and for this order by this customer
									$args = array(
										'post_id' => intval( $body2->order->items[$i]->id ),
										'author_email' => $customer_email,
										'meta_key' => 'ivole_order',
										'meta_value' => $order_id
									);
									$existing_comments = get_comments( $args );
									$num_existing_comments = count( $existing_comments );

									if( $num_existing_comments > 0 ) {
										// there are previous comment(s) submitted via external form
										$previous_comments_exist = true;
										$review_id = $existing_comments[$num_existing_comments-1]->comment_ID;
										$commentdata = array(
											'comment_ID' => $review_id,
										 	'comment_content' => $comment_text,
										 	'comment_approved' => $comment_approved,
										 	'comment_author' => $customer_name );
										wp_update_comment( $commentdata );
										update_comment_meta( $review_id, 'rating', intval( $body2->order->items[$i]->rating ) );
										do_action( 'wp_update_comment_count', intval( $body2->order->items[$i]->id ) );
									} else {
										// there are no previous comment(s) submitted via external form for this order and product
										$commentdata = array(
											'comment_author' => $customer_name,
									 		'comment_author_email' => $customer_email,
											'comment_author_url' => '',
										 	'comment_content' => $comment_text,
										 	'comment_post_ID' =>  intval( $body2->order->items[$i]->id ),
										 	'comment_type' => '',
										 	'comment_approved' => $comment_approved );
										$review_id = wp_insert_comment( $commentdata );
										if( $review_id ) {
											add_comment_meta( $review_id, 'rating', intval( $body2->order->items[$i]->rating ), true );
											add_comment_meta( $review_id, 'ivole_order', $order_id, true );
											do_action( 'wp_update_comment_count', intval( $body2->order->items[$i]->id ) );
										} else {
											$result = false;
										}
									}
								}
							}
							// if there are previous comments, it means that the customer has already received a coupon
							// and we don't need to send another one, so return early
							if( $previous_comments_exist ) {
								// send result the endpoint
								if( $result ) {
									return new WP_REST_Response( '', 200 );
								} else {
									return new WP_REST_Response( 'Review creation error 1', 500 );
								}
							}
							// send a coupon to the customer
							$coupon_enabled = get_option( 'ivole_coupon_enable', 'no' );
							if( $coupon_enabled === 'yes' ) {
								//qTranslate integration
								$lang = get_post_meta( $order_id, '_user_language', true );
								$old_lang = '';
								if( $lang ) {
									global $q_config;
									$old_lang = $q_config['language'];
									$q_config['language'] = $lang;
								}

								$ec = new Ivole_Email_Coupon();

								$coupon_type = get_option( 'ivole_coupon_type', 'static' );
								if( $coupon_type === 'static' ) {
									$coupon_id = get_option( 'ivole_existing_coupon', 0 );
								} else {
									$coupon_id = $ec->generate_coupon( $customer_email, $order_id );
									// compatibility with W3 Total Cache plugin
									// clear DB cache to read properties of the coupon
									if( function_exists( 'w3tc_dbcache_flush' ) ) {
										w3tc_dbcache_flush();
									}
								}
								if( $coupon_id > 0 && get_post_type( $coupon_id ) === 'shop_coupon' && get_post_status( $coupon_id ) === 'publish' ) {

									$coupon_code = get_post_field( 'post_title', $coupon_id );
									$discount_type = get_post_meta( $coupon_id, 'discount_type', true );
									$discount_amount = get_post_meta( $coupon_id, 'coupon_amount', true );
									$discount_string = "";
									if( $discount_type == "percent" && $discount_amount > 0 ) {
										$discount_string = $discount_amount . "%";
									} elseif( $discount_amount > 0 ) {
										$discount_string = trim( strip_tags( wc_price( $discount_amount, array( 'currency' => get_option( 'woocommerce_currency' ) ) ) ) );
									}

									$ec->replace['customer-first-name'] = $customer_first_name;
									$ec->replace['customer-name'] = $customer_name;
									$ec->replace['coupon-code'] = $coupon_code;
									$ec->replace['discount-amount'] = $discount_string;

									$from_address = get_option( 'ivole_email_from', 'feedback@cusrev.com' );
									$from_name = get_option( 'ivole_email_from_name', Ivole_Email::get_blogname() . ' via Customer Reviews' );
									$footer = get_option( 'ivole_email_footer', Ivole_Email_Footer::get_text() );

									// check if Reply-To address needs to be added to email
									$replyto = get_option( 'ivole_coupon_email_replyto', get_option( 'admin_email' ) );
									if( filter_var( $replyto, FILTER_VALIDATE_EMAIL ) ) {
										$replyto = $replyto;
									} else {
										$replyto = get_option( 'admin_email' );
									}

									$bcc_address = get_option( 'ivole_coupon_email_bcc', '' );
									if( !filter_var( $bcc_address, FILTER_VALIDATE_EMAIL ) ) {
										$bcc_address = '';
									}

									$message = $ec->get_content();
									$message = $ec->replace_variables( $message );

									$data = array(
										'token' => '164592f60fbf658711d47b2f55a1bbba',
										'shop' => array( "name" => Ivole_Email::get_blogname(),
									 		'domain' => Ivole_Email::get_blogurl(),
										 	'country' => apply_filters( 'woocommerce_get_base_location', get_option( 'woocommerce_default_country' ) ) ),
										'email' => array( 'to' => $customer_email,
											'from' => $from_address,
											'fromText' => $from_name,
											'replyTo' => $replyto,
											'bcc' => $bcc_address,
									 		'subject' => $ec->replace_variables( $ec->subject ),
											'header' => $ec->replace_variables( $ec->heading ),
											'body' => $message,
										 	'footer' => $footer ),
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
										'language' => $ec->language,
									);
									$license = get_option( 'ivole_license_key', '' );
									if( strlen( $license ) > 0 ) {
										$data['licenseKey'] = $license;
									}
									$api_url = 'https://z4jhozi8lc.execute-api.us-east-1.amazonaws.com/v1/review-discount';
									$data_string = json_encode( $data );
									error_log( $data_string );
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
									//error_log( $result );
									$result = json_decode( $result );
								}
								//qTranslate integration
								if( $lang ) {
									$q_config['language'] = $old_lang;
								}
							}

							// send result the endpoint
							if( $result ) {
								return new WP_REST_Response( '', 200 );
							} else {
								return new WP_REST_Response( 'Review creation error 2', 500 );
							}
						}
					}
				}
			}
			return new WP_REST_Response( 'Generic error', 500 );
		}

		public function create_review_permissions_check( WP_REST_Request $request ) {
			$body = $request->get_body();
			//error_log( print_r( $body, true) );
			$body2 = json_decode( $body );
			if( json_last_error() === JSON_ERROR_NONE ) {
				if( isset( $body2->key ) && isset( $body2->order ) ) {
					if( isset( $body2->order->id ) ) {
						$saved_key = get_post_meta( $body2->order->id, 'ivole_secret_key', true );
						if( $body2->key === $saved_key ) {
							//error_log( 'keys match!' );
							return true;
						}
					}
				}
			}
			return false;
		}
	}

endif;

?>
