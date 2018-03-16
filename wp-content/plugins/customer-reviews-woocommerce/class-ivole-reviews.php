<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

if ( ! class_exists( 'Ivole_Reviews' ) ) :

	require_once('class-ivole-email.php');

	class Ivole_Reviews {

		private $limit_file_size = 5000000;
		private $limit_file_count = 3;
		private $ivrating = 'ivrating';

	  public function __construct() {
			$this->limit_file_count = get_option( 'ivole_attach_image_quantity', 3 );
			$this->limit_file_size = 1024 * 1024 * get_option( 'ivole_attach_image_size', 5 );

			if( 'yes' == get_option( 'ivole_attach_image', 'no' ) ) {
				add_action( 'woocommerce_product_review_comment_form_args', array( $this, 'custom_fields_attachment' ) );
				add_filter( 'wp_insert_comment', array( $this, 'save_review_image' ) );
				add_filter( 'comments_array', array( $this, 'display_review_image' ), 12 );
				add_action( 'wp_enqueue_scripts', array( $this, 'ivole_style_1' ) );
			}
			if( 'yes' == get_option( 'ivole_enable_captcha', 'no' ) ) {
				if( is_user_logged_in() ) {
					add_action( 'woocommerce_product_review_comment_form_args', array( $this, 'custom_fields_captcha' ) );
				} else {
					add_action( 'comment_form_after_fields', array( $this, 'custom_fields_captcha2' ) );
				}
				add_filter( 'preprocess_comment', array( $this, 'validate_captcha' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'ivole_style_1' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'ivole_style_2' ) );
			}
			if( 'yes' == get_option( 'ivole_reviews_histogram', 'no' ) ) {
				add_filter( 'comments_template', array( $this, 'load_custom_comments_template' ), 100 );
				add_action( 'ivole_reviews_summary', array( $this, 'show_summary_table' ) );
				add_action( 'init', array( $this, 'add_query_var' ), 20 );
				add_filter( 'comments_template_query_args', array( $this, 'filter_comments2' ), 20);
				add_action( 'wp_enqueue_scripts', array( $this, 'ivole_style_1' ) );
				add_filter( 'comments_array', array( $this, 'include_review_replies' ), 11, 2 );
			}
			if( 'yes' == get_option( 'ivole_reviews_voting', 'no' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'ivole_style_1' ) );
				add_action( 'wp_ajax_ivole_vote_review', array( $this, 'vote_review_registered' ) );
				add_action( 'wp_ajax_nopriv_ivole_vote_review', array( $this, 'vote_review_unregistered' ) );

				if ( ( version_compare( WC()->version, "2.2.11", ">=" ) ) && ( version_compare( WC()->version, "2.5", "<" ) )  ) {
					add_filter( 'wc_get_template', array( $this, 'compatibility_reviews'), 10, 5 );
					add_action( 'ivole_woocommerce_review_after_comment_text', array( $this, 'display_voting_buttons' ), 10 );
				} elseif ( ( version_compare( WC()->version, "2.5", ">=" ) ) ) {
					add_action( 'woocommerce_review_after_comment_text', array( $this, 'display_voting_buttons' ), 10 );
				}
			}
			if( 'yes' == get_option( 'ivole_reviews_verified', 'no' ) ) {
				if ( ( version_compare( WC()->version, "2.2.11", ">=" ) ) && ( version_compare( WC()->version, "2.5", "<" ) )  ) {
					add_filter( 'wc_get_template', array( $this, 'compatibility_reviews'), 10, 5 );
					add_action( 'ivole_woocommerce_review_before_comment_text', array( $this, 'display_verified_badge' ), 10 );
				} elseif ( ( version_compare( WC()->version, "2.5", ">=" ) ) ) {
					add_action( 'woocommerce_review_before_comment_text', array( $this, 'display_verified_badge' ), 10 );
				}
			}
	  }
		public function custom_fields_attachment( $comment_form ) {
			$post_id = get_the_ID();
			$comment_form['comment_field'] .= '<p><label for="comment_image_' . $post_id . '">';
			$comment_form['comment_field'] .= sprintf( __( 'Upload up to %d images for your review (GIF, PNG, JPG, JPEG):', IVOLE_TEXT_DOMAIN ), $this->limit_file_count );
			$comment_form['comment_field'] .= '</label><input type="file" multiple="multiple" name="review_image_' . $post_id . '[]" id="review_image" />';
			$comment_form['comment_field'] .= '</p>';
			return $comment_form;
		}
		public function custom_fields_captcha( $comment_form ) {
			$site_key = get_option( 'ivole_captcha_site_key', '' );
			$comment_form['comment_field'] .= '<div class="g-recaptcha ivole-recaptcha" data-sitekey="' . $site_key . '"></div>';
			return $comment_form;
		}
		public function custom_fields_captcha2() {
			$site_key = get_option( 'ivole_captcha_site_key', '' );
			echo '<div class="g-recaptcha ivole-recaptcha" data-sitekey="' . $site_key . '"></div>';
		}
		public function save_review_image( $comment_id ) {
			//error_log("comment_id: " . print_r($comment_id, true));
			if( isset( $_POST['comment_post_ID'] ) ) {
				$post_id = $_POST['comment_post_ID'];
				//error_log("post_id: " . print_r($_POST['comment_post_ID'], true));
				$comment_image_id = 'review_image_' . $post_id;
				$nFiles = 0;
				if( is_array( $_FILES[$comment_image_id]['name'] ) ) {
					$nFiles = count( $_FILES[$comment_image_id]['name'] );
				}
				if( $nFiles > 0 ) {
					if( $nFiles > $this->limit_file_count ) {
						echo __( "Error: You tried to upload too many files. The maximum number of files that you can upload is " .
							$this->limit_file_count . ".<br/> Go back to: ", IVOLE_TEXT_DOMAIN );
						echo '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>';
						die;
					}
					for( $i = 0; $i < $nFiles; $i++ ) {
						//check file size
						if ( $this->limit_file_size < $_FILES[ $comment_image_id ]['size'][$i] ) {
							echo __( "Error: Uploaded file is too large. <br/> Go back to: ", IVOLE_TEXT_DOMAIN );
							echo '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>';
							die;
						}
						// Get file extension
						$file_name_parts = explode( '.', $_FILES[ $comment_image_id ]['name'][$i] );
						$file_ext = $file_name_parts[ count( $file_name_parts ) - 1 ];

						if( $this->is_valid_file_type( $file_ext ) ) {
							$comment_image_file = wp_upload_bits( $comment_id . '.' . $file_ext, null, file_get_contents( $_FILES[ $comment_image_id ]['tmp_name'][$i] ) );
							//$img_url = media_sideload_image( $comment_image_file['url'], $post_id );
							$attachmentId = media_sideload_image( $comment_image_file['url'], $post_id, null, 'id' );
							//preg_match_all( "#[^<img src='](.*)[^'alt='' />]#", $img_url, $matches );
							//$comment_image_file['url'] = $matches[0][0];
							if( !is_wp_error( $attachmentId ) ) {
								add_comment_meta( $comment_id, 'ivole_review_image2', $attachmentId );
							}
						}
					}
				}
			}
		}
		private function is_valid_file_type( $type ) {
			$type = strtolower( trim ( $type ) );
			return  $type == 'png' || $type == 'gif' || $type == 'jpg' || $type == 'jpeg';
		}
		public function display_review_image( $comments ) {
			if( count( $comments ) > 0 ) {
				//check WooCommerce version because PhotoSwipe lightbox is only supported in version 3.0+
				$class_a = 'ivole-comment-a-old';
				if ( ( version_compare( WC()->version, "3.0", ">=" ) ) ) {
					$class_a = 'ivole-comment-a';
				}
				foreach( $comments as $comment ) {
					$pics = get_comment_meta( $comment->comment_ID, 'ivole_review_image' );
					$pics_n = count( $pics );
					if( $pics_n > 0 ) {
						$comment->comment_content .= '<p class="iv-comment-image-text">' . __( 'Uploaded image(s):', IVOLE_TEXT_DOMAIN ) . '</p>';
						$comment->comment_content .= '<div class="iv-comment-images">';
						for( $i = 0; $i < $pics_n; $i ++) {
							$comment->comment_content .= '<div class="iv-comment-image">';
							$comment->comment_content .= '<a href="' . $pics[$i]['url'] . '" class="' . $class_a . '"><img src="' .
								$pics[$i]['url'] . '" alt="' . sprintf( __( 'Image #%1$d from ', IVOLE_TEXT_DOMAIN ), $i + 1 ) .
								$comment->comment_author . '" /></a>';
							$comment->comment_content .= '</div>';
						}
						$comment->comment_content .= '<div style="clear:both;"></div></div';
					} else {
						//new implementation of storing pictures in comments meta
						$pics = get_comment_meta( $comment->comment_ID, 'ivole_review_image2' );
						$pics_n = count( $pics );
						if( $pics_n > 0 ) {
							$temp_comment_content_flag = false;
							$temp_comment_content = '<p class="iv-comment-image-text">' . __( 'Uploaded image(s):', IVOLE_TEXT_DOMAIN ) . '</p>';
							$temp_comment_content .= '<div class="iv-comment-images">';
							for( $i = 0; $i < $pics_n; $i ++) {
								$attachmentUrl = wp_get_attachment_url( $pics[$i] );
								if( $attachmentUrl ) {
									$temp_comment_content_flag = true;
									$temp_comment_content .= '<div class="iv-comment-image">';
									$temp_comment_content .= '<a href="' . $attachmentUrl . '" class="' . $class_a . '"><img src="' .
										$attachmentUrl . '" alt="' . sprintf( __( 'Image #%1$d from ', IVOLE_TEXT_DOMAIN ), $i + 1 ) .
										$comment->comment_author . '" /></a>';
									$temp_comment_content .= '</div>';
								}
							}
							$temp_comment_content .= '<div style="clear:both;"></div></div';
							if( $temp_comment_content_flag ) {
								$comment->comment_content .= $temp_comment_content;
							}
						}
					}
				}
			}
			return $comments;
		}
		// include replies to reviews when filtering by number of stars
		public function include_review_replies( $comments, $post_id ){
			//error_log( print_r( $comments, true ) );
			$comments_flat = array();
			foreach ( $comments as $comment ) {
				$comments_flat[]  = $comment;
				$args = array(
					'parent' => $comment->comment_ID,
					'format' => 'flat',
					'status' => 'approve',
					'orderby' => 'comment_date'
				);
				$comment_children = get_comments( $args );
				foreach ( $comment_children as $comment_child ) {
					$reply_already_exist = false;
					foreach( $comments as $comment_flat ) {
						if( $comment_flat->comment_ID === $comment_child->comment_ID ) {
							$reply_already_exist = true;
						}
					}
					if( !$reply_already_exist ) {
						$comments_flat[] = $comment_child;
					}
				}
			}
			return $comments_flat;
		}
		public function display_voting_buttons( $comment ) {
			//error_log( print_r( $comment, true ) );
			if( 0 === intval( $comment->comment_parent ) ) {
				$votes = $this->get_votes( $comment->comment_ID );
				$prompt = __( 'Was this review helpful to you?', IVOLE_TEXT_DOMAIN );
				if( $votes['total'] > 0 ) {
					$prompt = sprintf( __( '%d out of %d people found this helpful. Was this review helpful to you?', IVOLE_TEXT_DOMAIN ), $votes['upvotes'], $votes['total'] );
				}
				if( is_array( $votes ) ) {
					echo '<span class="ivole-voting-cont"><span id="ivole-reviewvoting-' . $comment->comment_ID . '">';
					echo $prompt . '</span>';

					echo '<span class="ivobe-letter-space"></span>';

					echo '<span class="ivole-declarative">';
					echo '<div class="ivole-vote-button-margin">';
					echo '<span class="ivole-a-button">';
					echo '<span class="ivole-a-button-inner">';
					echo '<a id="ivole-reviewyes-' . $comment->comment_ID . '" class="ivole-a-button-text" href="#">';
					echo '<div class="ivole-vote-button">';
					echo __( 'Yes', IVOLE_TEXT_DOMAIN );
					echo '</div></a></span></span></div></span>';

					echo '<span class="ivobe-letter-space"></span>';

					echo '<span class="ivole-declarative">';
					echo '<div class="ivole-vote-button-margin">';
					echo '<span class="ivole-a-button">';
					echo '<span class="ivole-a-button-inner">';
					echo '<a id="ivole-reviewno-' . $comment->comment_ID . '" class="ivole-a-button-text" href="#">';
					echo '<div class="ivole-vote-button">';
					echo __( 'No', IVOLE_TEXT_DOMAIN );
					echo '</div></a></span></span></div></span>';

					echo '<span class="ivobe-letter-space"></span>';

					echo '</span>';
				}
			}
		}
		public function ivole_style_1() {
			if( is_product() ) {
				wp_register_style( 'ivole-frontend-css', plugins_url( '/css/frontend.css', __FILE__ ), array(), null, 'all' );
				wp_register_script( 'ivole-frontend-js', plugins_url( '/js/frontend.js', __FILE__ ), array(), null, true );
				wp_enqueue_style( 'ivole-frontend-css' );
				wp_localize_script( 'ivole-frontend-js', 'ajax_object',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
					 	'ajax_nonce' => wp_create_nonce('ivole-review-vote'),
					 	'text_processing' => __( 'Processing...', IVOLE_TEXT_DOMAIN ),
					 	'text_thankyou' => __( 'Thank you for your feedback!', IVOLE_TEXT_DOMAIN ),
					 	'text_error1' => __( 'An error occurred with submission of your feedback. Please refresh the page and try again.', IVOLE_TEXT_DOMAIN ),
					 	'text_error2' => __( 'An error occurred with submission of your feedback. Please report it to the website administrator.', IVOLE_TEXT_DOMAIN ) )
				);
				wp_enqueue_script( 'ivole-frontend-js' );
			}
		}
		public function ivole_style_2() {
			if( is_product() ) {
				wp_register_script( 'ivole-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true );
				wp_enqueue_script( 'ivole-recaptcha' );
			}
		}
		public function validate_captcha( $commentdata ) {
			if( is_admin() && current_user_can( 'edit_posts' ) ) {
				return $commentdata;
			}
			if( get_post_type( $commentdata['comment_post_ID'] ) === 'product' ) {
				if( !$this->ping_captcha() ) {
					wp_die( __( 'reCAPTCHA vertification failed and your review cannot be saved.', 'ivole' ), __( 'Add Review Error', 'ivole' ), array( 'back_link' => true ) );
				}
			}
			return $commentdata;
		}
		private function ping_captcha() {
			if( isset( $_POST['g-recaptcha-response'] ) ) {
				$secret_key = get_option( 'ivole_captcha_secret_key', '' );
				$response = json_decode(wp_remote_retrieve_body( wp_remote_get( "https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=" .$_POST['g-recaptcha-response'] ) ), true );
				if( $response["success"] )
				{
						return true;
				}
			}
			return false;
		}
		public function load_custom_comments_template( $template ) {
			if ( get_post_type() !== 'product' ) {
				return $template;
			}
			$plugin_folder = 'customer-reviews-woocommerce';
			$check_dirs = array(
				trailingslashit( get_stylesheet_directory() ) . $plugin_folder,
				trailingslashit( get_template_directory() ) . $plugin_folder
			);
			foreach ( $check_dirs as $dir ) {
				if ( file_exists( trailingslashit( $dir ) . 'ivole-single-product-reviews.php' ) ) {
					return trailingslashit( $dir ) . 'ivole-single-product-reviews.php';
				}
			}
			return wc_locate_template( 'ivole-single-product-reviews.php', '', plugin_dir_path ( __FILE__ ) . '/templates/' );
		}
		public function show_summary_table( $product_id ) {
			$all = $this->count_ratings( $product_id, 0 );
			if( $all > 0 ) {
				$five = (float)$this->count_ratings( $product_id, 5 );
				$five_percent = floor( $five / $all * 100 );
				$five_rounding = $five / $all * 100 - $five_percent;
				$four = (float)$this->count_ratings( $product_id, 4 );
				$four_percent = floor( $four / $all * 100 );
				$four_rounding = $four / $all * 100 - $four_percent;
				$three = (float)$this->count_ratings( $product_id, 3 );
				$three_percent = floor( $three / $all * 100 );
				$three_rounding = $three / $all * 100 - $three_percent;
				$two = (float)$this->count_ratings( $product_id, 2 );
				$two_percent = floor( $two / $all * 100 );
				$two_rounding = $two / $all * 100 - $two_percent;
				$one = (float)$this->count_ratings( $product_id, 1 );
				$one_percent = floor( $one / $all * 100 );
				$one_rounding = $one / $all * 100 - $one_percent;
				$hundred = $five_percent + $four_percent + $three_percent + $two_percent + $one_percent;
				// if( $hundred < 100 ) {
				// 	$to_distribute = 100 - $hundred;
				// 	$roundings = array( '5' => $five_rounding, '4' => $four_rounding, '3' => $three_rounding, '2' => $two_rounding, '1' => $one_rounding );
				// 	arsort($roundings);
				// 	error_log( print_r( $roundings, true ) );
				// }
				$output = '';
				$output .= '<div class="ivole-summaryBox col-sm-4 averageratingsection">';
				$output .= '<table id="ivole-histogramTable">';
				$output .= '<tbody>';
				$output .= '<tr class="ivole-histogramRow">';
				if( $five > 0 ) {
					$output .= '<td class="ivole-histogramCell1"><a href="' . esc_url( add_query_arg( $this->ivrating, 5, get_permalink( $product_id ) ) ) . '#tab-reviews" title="' . __( '5 star', IVOLE_TEXT_DOMAIN ) . '">' . __( '5 star', IVOLE_TEXT_DOMAIN ) . '</a></td>';
					$output .= '<td class="ivole-histogramCell2"><a href="' . esc_url( add_query_arg( $this->ivrating, 5, get_permalink( $product_id ) ) ) . '#tab-reviews"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $five_percent . '%"></div></div></a></td>';
					$output .= '<td class="ivole-histogramCell3"><a href="' . esc_url( add_query_arg( $this->ivrating, 5, get_permalink( $product_id ) ) ) . '#tab-reviews">' . (string)$five_percent . '%</a></td>';
				} else {
					$output .= '<td class="ivole-histogramCell1">' . __( '5 star', IVOLE_TEXT_DOMAIN ) . '</td>';
					$output .= '<td class="ivole-histogramCell2"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $five_percent . '%"></div></div></td>';
					$output .= '<td class="ivole-histogramCell3">' . (string)$five_percent . '%</td>';
				}
				$output .= '</tr>';
				$output .= '<tr class="ivole-histogramRow">';
				if( $four > 0 ) {
					$output .= '<td class="ivole-histogramCell1"><a href="' . esc_url( add_query_arg( $this->ivrating, 4, get_permalink( $product_id ) ) ) . '#tab-reviews" title="' . __( '4 star', IVOLE_TEXT_DOMAIN ) . '">' . __( '4 star', IVOLE_TEXT_DOMAIN ) . '</a></td>';
					$output .= '<td class="ivole-histogramCell2"><a href="' . esc_url( add_query_arg( $this->ivrating, 4, get_permalink( $product_id ) ) ) . '#tab-reviews"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $four_percent . '%"></div></div></a></td>';
					$output .= '<td class="ivole-histogramCell3"><a href="' . esc_url( add_query_arg( $this->ivrating, 4, get_permalink( $product_id ) ) ) . '#tab-reviews">' . (string)$four_percent . '%</a></td>';
				} else {
					$output .= '<td class="ivole-histogramCell1">' . __( '4 star', IVOLE_TEXT_DOMAIN ) . '</td>';
					$output .= '<td class="ivole-histogramCell2"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $four_percent . '%"></div></div></td>';
					$output .= '<td class="ivole-histogramCell3">' . (string)$four_percent . '%</td>';
				}
				$output .= '</tr>';
				$output .= '<tr class="ivole-histogramRow">';
				if( $three > 0 ) {
					$output .= '<td class="ivole-histogramCell1"><a href="' . esc_url( add_query_arg( $this->ivrating, 3, get_permalink( $product_id ) ) ) . '#tab-reviews" title="' . __( '3 star', IVOLE_TEXT_DOMAIN ) . '">' . __( '3 star', IVOLE_TEXT_DOMAIN ) . '</a></td>';
					$output .= '<td class="ivole-histogramCell2"><a href="' . esc_url( add_query_arg( $this->ivrating, 3, get_permalink( $product_id ) ) ) . '#tab-reviews"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $three_percent . '%"></div></div></a></td>';
					$output .= '<td class="ivole-histogramCell3"><a href="' . esc_url( add_query_arg( $this->ivrating, 3, get_permalink( $product_id ) ) ) . '#tab-reviews">' . (string)$three_percent . '%</a></td>';
				} else {
					$output .= '<td class="ivole-histogramCell1">' . __( '3 star', IVOLE_TEXT_DOMAIN ) . '</td>';
					$output .= '<td class="ivole-histogramCell2"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $three_percent . '%"></div></div></td>';
					$output .= '<td class="ivole-histogramCell3">' . (string)$three_percent . '%</td>';
				}
				$output .= '</tr>';
				$output .= '<tr class="ivole-histogramRow">';
				if( $two > 0 ) {
					$output .= '<td class="ivole-histogramCell1"><a href="' . esc_url( add_query_arg( $this->ivrating, 2, get_permalink( $product_id ) ) ) . '#tab-reviews" title="' . __( '2 star', IVOLE_TEXT_DOMAIN ) . '">' . __( '2 star', IVOLE_TEXT_DOMAIN ) . '</a></td>';
					$output .= '<td class="ivole-histogramCell2"><a href="' . esc_url( add_query_arg( $this->ivrating, 2, get_permalink( $product_id ) ) ) . '#tab-reviews"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $two_percent . '%"></div></div></a></td>';
					$output .= '<td class="ivole-histogramCell3"><a href="' . esc_url( add_query_arg( $this->ivrating, 2, get_permalink( $product_id ) ) ) . '#tab-reviews">' . (string)$two_percent . '%</a></td>';
				} else {
					$output .= '<td class="ivole-histogramCell1">' . __( '2 star', IVOLE_TEXT_DOMAIN ) . '</td>';
					$output .= '<td class="ivole-histogramCell2"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $two_percent . '%"></div></div></td>';
					$output .= '<td class="ivole-histogramCell3">' . (string)$two_percent . '%</td>';
				}
				$output .= '</tr>';
				$output .= '<tr class="ivole-histogramRow">';
				if( $one > 0 ) {
					$output .= '<td class="ivole-histogramCell1"><a href="' . esc_url( add_query_arg( $this->ivrating, 1, get_permalink( $product_id ) ) ) . '#tab-reviews" title="' . __( '1 star', IVOLE_TEXT_DOMAIN ) . '">' . __( '1 star', IVOLE_TEXT_DOMAIN ) . '</a></td>';
					$output .= '<td class="ivole-histogramCell2"><a href="' . esc_url( add_query_arg( $this->ivrating, 1, get_permalink( $product_id ) ) ) . '#tab-reviews"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $one_percent . '%"></div></div></a></td>';
					$output .= '<td class="ivole-histogramCell3"><a href="' . esc_url( add_query_arg( $this->ivrating, 1, get_permalink( $product_id ) ) ) . '#tab-reviews">' . (string)$one_percent . '%</a></td>';
				} else {
					$output .= '<td class="ivole-histogramCell1">' . __( '1 star', IVOLE_TEXT_DOMAIN ) . '</td>';
					$output .= '<td class="ivole-histogramCell2"><div class="ivole-meter"><div class="ivole-meter-bar" style="width: ' . $one_percent . '%"></div></div></td>';
					$output .= '<td class="ivole-histogramCell3">' . (string)$one_percent . '%</td>';
				}
				$output .= '</tr>';
				if( 'yes' !== get_option( 'ivole_reviews_nobranding', 'no' ) ) {
					$output .= '<tr class="ivole-histogramRow">';
					$output .= '<td colspan="3" class="ivole-credits">';
					$output .= 'Powered by <a href="https://wordpress.org/plugins/customer-reviews-woocommerce/" target="_blank">Customer Reviews Plugin</a>';
					$output .= '</td>';
					$output .= '</tr>';
				}
				$output .= '</tbody>';
				$output .= '</table>';
				if( get_query_var( $this->ivrating ) ) {
					$rating = intval( get_query_var( $this->ivrating ) );
					if( $rating > 0 && $rating <= 5 ) {
						$filtered_comments = sprintf( esc_html( _n( 'Showing %1$d of %2$d review (%3$d star). ', 'Showing %1$d of %2$d reviews (%3$d star). ', $all, 'ivole'  ) ), $this->count_ratings( $product_id, $rating ), $all, $rating );
						$all_comments = sprintf( esc_html( _n( 'See all %d review', 'See all %d reviews', $all, 'ivole'  ) ), $all );
						$output .= '<span>' . $filtered_comments . '</span><a class="ivole-seeAllReviews" href="' . esc_url( get_permalink( $product_id ) ) . '#tab-reviews">' . $all_comments . '</a>';
					}
				}
				$output .= '</div>';
				$output .= '<div class="col-sm-8">';
						$output .= '<div class="write-review">';
							$output .= '<p>Share your thoughts with other customers</p>';
							$output .= '<button type="button" class="write-review-btn">Write a Customer review</button>';


						$output .= '</div>';
					$output .= '</div>';
				echo $output;
			}
		}
		private function count_ratings( $product_id, $rating ) {
			$args = array(
				'post_id' => $product_id,
				'status' => 'approve',
				'parent' => 0,
				'count' => true
			);
			if( $rating > 0 ){
				$args['meta_query'][] = array(
					'key' => 'rating',
					'value'   => $rating,
					'compare' => '=',
					'type'    => 'numeric'
				);
			}
			return get_comments( $args );
		}
		public function add_query_var() {
			global $wp;
    	$wp->add_query_var( $this->ivrating );
		}
		public function filter_comments2( $comment_args ) {
			if( get_post_type() === 'product' ) {
				if( get_query_var( $this->ivrating ) ) {
					$rating = intval( get_query_var( $this->ivrating ) );
					if( $rating > 0 && $rating <= 5 ) {
						$comment_args['meta_query'][] = array(
							'key' => 'rating',
							'value'   => $rating,
							'compare' => '=',
							'type'    => 'numeric'
						);
					}
				}
			}
			//error_log( print_r( $comment_args, true ) );
			return $comment_args;
		}
		public function vote_review_registered() {
			if( !check_ajax_referer( 'ivole-review-vote', 'security', false ) ) {
				wp_send_json( array( 'code' => 3 ) );
			}
			//error_log('vote_review_registered');
			$comment_id = intval( $_POST['reviewID'] );
			$upvote = intval( $_POST['upvote'] );
			$registered_upvoters = get_comment_meta( $comment_id, 'ivole_review_reg_upvoters', true );
			$registered_downvoters = get_comment_meta( $comment_id, 'ivole_review_reg_downvoters', true );
			$current_user = get_current_user_id();
			// check if this registered user has already upvoted this review
			if( !empty( $registered_upvoters ) ) {
				$registered_upvoters = maybe_unserialize( $registered_upvoters );
				if( is_array( $registered_upvoters ) ) {
					$registered_upvoters_count = count( $registered_upvoters );
					$index_upvoters = -1;
					for($i = 0; $i < $registered_upvoters_count; $i++ ) {
						if( $current_user === $registered_upvoters[$i] ) {
							if( 0 < $upvote ) {
								// upvote request, exit because this user has already upvoted this review earlier
								wp_send_json( array( 'code' => 1 ) );
							} else {
								// downvote request, remove the upvote
								$index_upvoters = $i;
								break;
							}
						}
					}
					if( 0 <= $index_upvoters ) {
						array_splice( $registered_upvoters, $index_upvoters, 1 );
					}
				} else {
					$registered_upvoters = array();
				}
			} else {
				$registered_upvoters = array();
			}
			// check if this registered user has already downvoted this review
			if( !empty( $registered_downvoters ) ) {
				$registered_downvoters = maybe_unserialize( $registered_downvoters );
				if( is_array( $registered_downvoters ) ) {
					$registered_downvoters_count = count( $registered_downvoters );
					$index_downvoters = -1;
					for($i = 0; $i < $registered_downvoters_count; $i++ ) {
						if( $current_user === $registered_downvoters[$i] ) {
							if( 0 < $upvote ) {
								// upvote request, remove the downvote
								$index_downvoters = $i;
								break;
							} else {
								// downvote request, exit because this user has already downvoted this review earlier
								wp_send_json( array( 'code' => 2 ) );
							}
						}
					}
					if( 0 <= $index_downvoters ) {
						array_splice( $registered_downvoters, $index_downvoters, 1 );
					}
				} else {
					$registered_downvoters = array();
				}
			} else {
				$registered_downvoters = array();
			}
			//error_log( print_r( $registered_upvoters, true ) );
			//update arrays of registered upvoters and downvoters
			if( 0 < $upvote ) {
				$registered_upvoters[] = $current_user;
			} else {
				$registered_downvoters[] = $current_user;
			}
			//error_log( print_r( $registered_upvoters, true ) );
			update_comment_meta( $comment_id, 'ivole_review_reg_upvoters', $registered_upvoters );
			update_comment_meta( $comment_id, 'ivole_review_reg_downvoters', $registered_downvoters );
			$this->send_votes( $comment_id );
			// compatibility with W3 Total Cache plugin
			// clear DB cache to make sure that count of upvotes is immediately updated
			if( function_exists( 'w3tc_dbcache_flush' ) ) {
				w3tc_dbcache_flush();
			}
			wp_send_json( array( 'code' => 0 ) );
		}

		public function vote_review_unregistered() {
			if( !check_ajax_referer( 'ivole-review-vote', 'security', false ) ) {
				wp_send_json( array( 'code' => 3 ) );
			}
			//error_log('vote_review_unregistered, ip:' . $_SERVER['REMOTE_ADDR'] );
			$ip = $_SERVER['REMOTE_ADDR'];
			$comment_id = intval( $_POST['reviewID'] );
			$upvote = intval( $_POST['upvote'] );

			// check (via cookie) if this unregistered user has already upvoted this review
			if( isset( $_COOKIE['ivole_review_upvote'] ) ) {
				$upcomment_ids = json_decode( $_COOKIE['ivole_review_upvote'], true );
				if( is_array( $upcomment_ids ) ) {
					$upcomment_ids_count = count( $upcomment_ids );
					$index_upvoters = -1;
					for( $i = 0; $i < $upcomment_ids_count; $i++ ) {
						if( $comment_id === $upcomment_ids[$i] ) {
							if( 0 < $upvote ) {
								// upvote request, exit because this user has already upvoted this review earlier
								wp_send_json( array( 'code' => 1 ) );
							} else {
								// downvote request, remove the upvote
								$index_upvoters = $i;
								break;
							}
						}
					}
					if( 0 <= $index_upvoters ) {
						array_splice( $upcomment_ids, $index_upvoters, 1 );
					}
				} else {
					$upcomment_ids = array();
				}
			} else {
				$upcomment_ids = array();
			}

			// check (via cookie) if this unregistered user has already downvoted this review
			if( isset( $_COOKIE['ivole_review_downvote'] ) ) {
				$downcomment_ids = json_decode( $_COOKIE['ivole_review_downvote'], true );
				if( is_array( $downcomment_ids ) ) {
					$downcomment_ids_count = count( $downcomment_ids );
					$index_downvoters = -1;
					for( $i = 0; $i < $downcomment_ids_count; $i++ ) {
						if( $comment_id === $downcomment_ids[$i] ) {
							if( 0 < $upvote ) {
								// upvote request, remove the downvote
								$index_downvoters = $i;
								break;
							} else {
								// downvote request, exit because this user has already downvoted this review earlier
								wp_send_json( array( 'code' => 2 ) );
							}
						}
					}
					if( 0 <= $index_downvoters ) {
						array_splice( $downcomment_ids, $index_downvoters, 1 );
					}
				} else {
					$downcomment_ids = array();
				}
			} else {
				$downcomment_ids = array();
			}

			$unregistered_upvoters = get_comment_meta( $comment_id, 'ivole_review_unreg_upvoters', true );
			$unregistered_downvoters = get_comment_meta( $comment_id, 'ivole_review_unreg_downvoters', true );

			// check if this unregistered user has already upvoted this review
			if( !empty( $unregistered_upvoters ) ) {
				$unregistered_upvoters = maybe_unserialize( $unregistered_upvoters );
				if( is_array( $unregistered_upvoters ) ) {
					$unregistered_upvoters_count = count( $unregistered_upvoters );
					$index_upvoters = -1;
					for($i = 0; $i < $unregistered_upvoters_count; $i++ ) {
						if( $ip === $unregistered_upvoters[$i] ) {
							if( 0 < $upvote ) {
								// upvote request, exit because this user has already upvoted this review earlier
								wp_send_json( array( 'code' => 1 ) );
							} else {
								// downvote request, remove the upvote
								$index_upvoters = $i;
								break;
							}
						}
					}
					if( 0 <= $index_upvoters ) {
						array_splice( $unregistered_upvoters, $index_upvoters, 1 );
					}
				} else {
					$unregistered_upvoters = array();
				}
			} else {
				$unregistered_upvoters = array();
			}

			// check if this unregistered user has already downvoted this review
			if( !empty( $unregistered_downvoters ) ) {
				$unregistered_downvoters = maybe_unserialize( $unregistered_downvoters );
				if( is_array( $unregistered_downvoters ) ) {
					$unregistered_downvoters_count = count( $unregistered_downvoters );
					$index_downvoters = -1;
					for($i = 0; $i < $unregistered_downvoters_count; $i++ ) {
						if( $ip === $unregistered_downvoters[$i] ) {
							if( 0 < $upvote ) {
								// upvote request, remove the downvote
								$index_downvoters = $i;
								break;
							} else {
								// downvote request, exit because this user has already downvoted this review earlier
								wp_send_json( array( 'code' => 2 ) );
							}
						}
					}
					if( 0 <= $index_downvoters ) {
						array_splice( $unregistered_downvoters, $index_downvoters, 1 );
					}
				} else {
					$unregistered_downvoters = array();
				}
			} else {
				$unregistered_downvoters = array();
			}

			//update cookie arrays of unregistered upvoters and downvoters
			if( 0 < $upvote ) {
				$upcomment_ids[] = $comment_id;
				$unregistered_upvoters[] = $ip;
			} else {
				$downcomment_ids[] = $comment_id;
				$unregistered_downvoters[] = $ip;
			}
			setcookie( 'ivole_review_upvote', json_encode( $upcomment_ids ), time() + 365*24*60*60, COOKIEPATH, COOKIE_DOMAIN );
			setcookie( 'ivole_review_downvote', json_encode( $downcomment_ids ), time() + 365*24*60*60, COOKIEPATH, COOKIE_DOMAIN );
			update_comment_meta( $comment_id, 'ivole_review_unreg_upvoters', $unregistered_upvoters );
			update_comment_meta( $comment_id, 'ivole_review_unreg_downvoters', $unregistered_downvoters );
			$this->send_votes( $comment_id );
			// compatibility with W3 Total Cache plugin
			// clear DB cache to make sure that count of upvotes is immediately updated
			if( function_exists( 'w3tc_dbcache_flush' ) ) {
				w3tc_dbcache_flush();
			}
			wp_send_json( array( 'code' => 0 ) );
		}
		public function get_votes( $comment_id ) {
			$r_upvotes = 0;
			$r_downvotes = 0;
			$u_upvotes = 0;
			$u_downvotes = 0;
			$registered_upvoters = get_comment_meta( $comment_id, 'ivole_review_reg_upvoters', true );
			$registered_downvoters = get_comment_meta( $comment_id, 'ivole_review_reg_downvoters', true );
			$unregistered_upvoters = get_comment_meta( $comment_id, 'ivole_review_unreg_upvoters', true );
			$unregistered_downvoters = get_comment_meta( $comment_id, 'ivole_review_unreg_downvoters', true );

			if( !empty( $registered_upvoters ) ) {
				$registered_upvoters = maybe_unserialize( $registered_upvoters );
				if( is_array( $registered_upvoters ) ) {
					$r_upvotes = count( $registered_upvoters );
				}
			}

			if( !empty( $registered_downvoters ) ) {
				$registered_downvoters = maybe_unserialize( $registered_downvoters );
				if( is_array( $registered_downvoters ) ) {
					$r_downvotes = count( $registered_downvoters );
				}
			}

			if( !empty( $unregistered_upvoters ) ) {
				$unregistered_upvoters = maybe_unserialize( $unregistered_upvoters );
				if( is_array( $unregistered_upvoters ) ) {
					$u_upvotes = count( $unregistered_upvoters );
				}
			}

			if( !empty( $unregistered_downvoters ) ) {
				$unregistered_downvoters = maybe_unserialize( $unregistered_downvoters );
				if( is_array( $unregistered_downvoters ) ) {
					$u_downvotes = count( $unregistered_downvoters );
				}
			}

			$votes = array(
				'upvotes' => $r_upvotes + $u_upvotes,
				'total' => $r_upvotes + $r_downvotes + $u_upvotes + $u_downvotes
			);
			return $votes;
		}
		public function send_votes( $comment_id ) {
			$comment = get_comment( $comment_id );
			if( $comment ) {
				$votes = $this->get_votes( $comment_id );
				$product_id = $comment->comment_post_ID;
				//clear WP Super Cache after voting
				if( function_exists( 'wpsc_delete_post_cache' ) ) {
					wpsc_delete_post_cache( $product_id );
				}
				//clear W3TC after voting
				if( function_exists( 'w3tc_flush_post' ) ) {
					w3tc_flush_post( $product_id );
				}
				$order_id = get_comment_meta( $comment_id, 'ivole_order', true );
				//error_log( 'order_id = ' . $order_id );
				if( '' !== $order_id ) {
					$secret_key = get_post_meta( $order_id, 'ivole_secret_key', true );
					//error_log( 'secret_key = ' . $secret_key );
					if( '' !== $secret_key ) {
						$data = array(
							'token' => '164592f60fbf658711d47b2f55a1bbba',
							'secretKey' => $secret_key,
							'shop' => array( 'domain' => Ivole_Email::get_blogurl(),
						 		'orderId' => $order_id,
								'productId' => $product_id ),
							'upvotes' => $votes['upvotes'],
							'downvotes' => $votes['total'] - $votes['upvotes']
						);
						$api_url = 'https://z4jhozi8lc.execute-api.us-east-1.amazonaws.com/v1/review-vote';
						$data_string = json_encode( $data );
						$ch = curl_init();
						curl_setopt( $ch, CURLOPT_URL, $api_url );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
						curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
						curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
							'Content-Type: application/json',
							'Content-Length: ' . strlen( $data_string ) )
						);
						$result = curl_exec( $ch );
						//error_log( print_r( $result, true ) );
					}
				}
			}
		}
		public function compatibility_reviews( $located, $template_name, $args, $template_path, $default_path ) {
			if( 'single-product/review.php' === $template_name ) {
				$replacement_path = plugin_dir_path( __FILE__ ) . 'templates/review-compat.php';
				if( is_file( $replacement_path ) ) {
					$located = $replacement_path;
					//error_log( print_r( $replacement_path, true ) );
				}
			}
			return $located;
		}
		public function display_verified_badge( $comment ) {
			//error_log( print_r( $comment, true ) );
			if( 0 === intval( $comment->comment_parent ) ) {
				$product_id = $comment->comment_post_ID;
				$order_id = get_comment_meta( $comment->comment_ID, 'ivole_order', true );
				if( '' !== $order_id ) {
					$output = '<p class="ivole-verified-badge"><img src="' . untrailingslashit( plugin_dir_url( __FILE__ ) );
					$output .= '/img/shield-20.png" class="ivole-verified-badge-icon">';
					$output .= '<span class="ivole-verified-badge-text">';
					$output .= __( 'Verified review', IVOLE_TEXT_DOMAIN );
					$output .= ' - <a href="https://www.cusrev.com/reviews/' . get_option( 'ivole_reviews_verified_page', Ivole_Email::get_blogdomain() ) . '/p/p-' . $product_id . '/r-' . $order_id . '/';
					$output .= '" title="" target="_blank" rel="nofollow noopener">';
					$output .= __( 'view original', IVOLE_TEXT_DOMAIN ) . '</a>';
					$output .= '<img src="' . untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/img/external-link.png" class="ivole-verified-badge-ext-icon"></span></p>';
					echo $output;
				}
			}
		}
	}

endif;

?>
