<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'YITH_WooCommerce_Advanced_Reviews' ) ) {

	/**
	 * Implements features of FREE version of YWAR plugin
	 *
	 * @class   YITH_WooCommerce_Advanced_Reviews
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YITH_WooCommerce_Advanced_Reviews {
		/**
		 * @var YIT_Plugin_Panel_WooCommerce the panel object
		 */
		protected $_panel;

		/**
		 * @var string the Premium tab template file name
		 */
		protected $_premium = 'premium.php';

		/**
		 * @var string Premium version landing link
		 */
		protected $_premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-advanced-reviews/';

		/**
		 * @var string Plugin official documentation
		 */
		protected $_official_documentation = 'http://yithemes.com/docs-plugins/yith-woocommerce-advanced-reviews/';

		/**
		 * @var string Advanced Reviews panel page
		 */
		protected $_panel_page = 'yith_ywar_panel';

		/**
		 * @var $enable_title Let users to add a title when writing a review
		 */
		protected $enable_title = 0;

		/**
		 * @var $enable_attachments Let users to add attachments when submit a review
		 */
		protected $enable_attachments = 0;

		/**
		 * @var $attachments_limit Set the maximum number of attachments a users can add when submit a review
		 */
		protected $attachments_limit = 0;


		/**
		 * @var int number of review to show per single page
		 */
		public $items_for_page = 10;

		/**
		 * @var string limit the allowed file extension
		 */
		public $attachment_allowed_type = '';

		/**
		 * @var int Set the maximum allowed file size
		 */
		public $attachment_max_size = 0;

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		protected function __construct() {

			$this->initialize_settings();

			add_action( 'admin_menu', array( $this, 'add_menu_item' ) );


			//region    ******* YIT Plugin Framework    *********

			// Load Plugin Framework
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );

			//  Add row meta
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );

			//  Add stylesheets and scripts files
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

			//  register plugin pointer
			add_action( 'admin_init', array( $this, 'register_pointer' ) );

			//  verify import reviews action request
			add_action( "admin_init", array( $this, "check_import_actions" ) );


			//Add action links
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_YWAR_DIR . '/' . basename( YITH_YWAR_FILE ) ), array(
				$this,
				'action_links',
			) );

			add_action( 'yith_advanced_reviews_premium', array( $this, 'premium_tab' ) );

			//endregion

			//region    ***********  Add stylesheets and scripts files  ************

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_resource_frontend' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_resource_backend' ) );

			//endregion

			//region    ***********   Advanced reviews post type functionalities

			add_action( 'init', array( $this, 'register_post_type' ) );


			//endregion

			//region    ***********   Review table

			add_filter( 'yith_advanced_reviews_row_actions', array( $this, 'add_review_actions' ), 10, 2 );

			add_filter( 'post_class', array( $this, 'add_review_table_class' ), 10, 3 );

			/**
			 * intercept approve and unapprove actions
			 */
			add_action( "admin_action_" . YITH_YWAR_ACTION_APPROVE_REVIEW, array( $this, 'update_review_attributes' ) );
			add_action( "admin_action_" . YITH_YWAR_ACTION_UNAPPROVE_REVIEW, array(
				$this,
				'update_review_attributes'
			) );
			add_action( "admin_action_" . YITH_YWAR_ACTION_UNTRASH_REVIEW, array( $this, 'update_review_attributes' ) );

			//endregion ******************************************

			//  Load reviews template
			add_filter( 'comments_template', array(
				$this,
				'show_advanced_reviews_template',
			), 99 );

			//  Save additional comment fields on comment submit
			add_action( 'comment_post', array( $this, 'submit_review_on_comment_post' ), 10, 2 );

			//  redirect to product page on comment submitted
			add_filter( 'comment_post_redirect', array( $this, 'redirect_after_submit_review' ), 10, 2 );

			add_filter( 'woocommerce_product_review_comment_form_args', array(
				$this,
				'add_fields_to_comment_form',
			) );

			//  Add custom fields "Title" on top of comment form
//			add_action( 'comment_form_logged_in_after', array( $this, 'add_custom_fields_on_comment_form' ) );
//			add_action( 'comment_form_after_fields', array( $this, 'add_custom_fields_on_comment_form' ) );
			add_filter( 'yith_ywar_add_content_before_review_text', array( $this, 'test' ) );

			add_filter( 'yith_advanced_reviews_review_content', array( $this, 'show_expanded_review_content' ) );

			add_filter( 'woocommerce_product_tabs', array( $this, 'update_tab_reviews_count' ), 100 );

			/**
			 * Add summary bars for product rating
			 */
			add_action( 'yith_advanced_reviews_before_reviews', array( $this, 'load_reviews_summary' ) );

			//  Show details with average rating for the current product
			add_action( 'ywar_summary_prepend', array( $this, 'add_reviews_average_info' ) );

			add_filter( 'wc_get_template', array( $this, 'wc_get_template' ), 99, 5 );

			add_filter( 'woocommerce_product_get_rating_html', array( $this, 'get_product_rating_html' ), 99, 2 );

			//region    ***************** Show, edit and save back-end review metabox
			//  Add a new metabox for editing and saving title comment in meta_comment table
			add_action( 'add_meta_boxes', array( $this, 'add_plugin_metabox' ), 1 );

			// save the custom fields
			add_action( 'save_post', array( $this, 'save_plugin_metabox' ), 1, 2 );

			add_action( 'admin_menu', array( $this, 'remove_unwanted_custom_post_type_features' ), 5 );
			add_action( 'admin_head', array( $this, 'hide_unwanted_custom_post_type_features' ) );
			//endregion

			add_action( 'woocommerce_admin_field_ywar_import_previous_reviews', array(
				$this,
				'show_import_reviews_button',
			), 10, 1 );

			add_action( 'wp_ajax_convert_reviews', array( $this, 'convert_reviews_callback' ) );

			/**
			 * Manage the changes to product's reviews
			 */
			add_action( 'yith_ywar_product_reviews_updated', array( $this, 'manage_product_reviews_update' ) );

			/**
			 * Show the gravatar on review
			 */
			add_action( 'woocommerce_review_before', array( $this, 'show_review_gravatar' ) );
		}

		public function test( $content ) {
			if ( $this->enable_title ) {
				$content = '<p class="comment-form-title"><label for="title">' .
				           __( 'Review title', 'yith-woocommerce-advanced-reviews' ) .
				           '</label><input type="text" name="title" id="title"></p>' .
				           $content;
			}

			return $content;

		}

		/**
		 * Show the gravatar on review
		 */
		public function show_review_gravatar() {

		}

		/**
		 * When a product review change its status, delete the transient that store the values relative to the ratings
		 *
		 * @param int $product_id the product id
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function manage_product_reviews_update( $product_id = 0 ) {
			if ( ! $product_id ) {
				return;
			}

			$transient_version = WC_Cache_Helper::get_transient_version( 'product' );
			delete_transient( 'yith_average_rating_' . $product_id . $transient_version );
			delete_transient( 'yith_ratings_' . $product_id . $transient_version );
		}

		/**
		 * Add the Reviews menu item in dashboard menu
		 *
		 * @since  1.0
		 * @return void
		 * @see    wp-admin\includes\plugin.php -> add_menu_page()
		 */
		public function add_menu_item() {

			$args = array(
				'page_title' => __( 'Reviews', 'yith-woocommerce-advanced-reviews' ),
				'menu_title' => __( 'Reviews', 'yith-woocommerce-advanced-reviews' ),
				'capability' => apply_filters( 'yith_ywar_manage_reviews_capability', 'manage_woocommerce' ),
				'menu_slug'  => 'Reviews',
				'function'   => array( $this, 'show_reviews_table' ),
				'icon'       => 'dashicons-star-filled',
				'position'   => 8 /* After WC Products */
			);

			extract( $args );

			add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon, $position );
		}

		/**
		 * Show the reviews table
		 *
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 * @since  1.0
		 * @return void
		 * @fire   yith_vendors_commissions_template hooks
		 */
		public function show_reviews_table() {
			if ( ! class_exists( 'WP_Posts_List_Table' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' );
			}

			require_once( YITH_YWAR_DIR . 'includes/class.yith-advanced-reviews-list-table.php' );

			$product_reviews = new YITH_Advanced_Reviews_List_Table();
			$product_reviews->prepare_items();

			wc_get_template( 'ywar-product-reviews-table.php', array( 'product_reviews' => $product_reviews ), YITH_YWAR_TEMPLATES_DIR, YITH_YWAR_TEMPLATES_DIR );
		}

		/**
		 * Set the approved status for a review
		 *
		 * @param int        $review_id       the review id
		 * @param int|string $review_approved whether to set the reviews as approved or not
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function set_approved_status( $review_id, $review_approved ) {
			update_post_meta( $review_id, YITH_YWAR_META_APPROVED, $review_approved );

			//  Set the comment status of the comment related to the review
			$comment_id = get_post_meta( $review_id, YITH_YWAR_META_COMMENT_ID, true );
			wp_set_comment_status( $comment_id, $review_approved ? '1' : '0' );

			//  Notify the status change
			do_action( 'ywar_review_approve_status_changed', $review_id, $review_approved );

			// Notify that the review was updated
			yith_ywar_notify_review_update( $review_id );
		}

		/**
		 * Intercept review action url and do the requested job
		 */
		public function update_review_attributes() {

			if ( ! isset( $_GET["review_id"] ) ) {
				return;
			}

			$review_id = $_GET["review_id"];

			$current_filter = current_filter();

			switch ( $current_filter ) {
				case "admin_action_" . YITH_YWAR_ACTION_APPROVE_REVIEW :
					$this->set_approved_status( $review_id, true );

					break;

				case "admin_action_" . YITH_YWAR_ACTION_UNAPPROVE_REVIEW :
					$this->set_approved_status( $review_id, false );

					break;

				case "admin_action_" . YITH_YWAR_ACTION_UNTRASH_REVIEW :
					$this->untrash_review( $review_id );

					break;
			}

			wp_redirect( esc_url_raw( remove_query_arg( array( 'action', 'action2' ), $_SERVER['HTTP_REFERER'] ) ) );
		}

		/**
		 * Move the review fro the trash
		 *
		 * @param int $review_id
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function untrash_review( $review_id ) {

			$my_post = array(
				'ID'          => $review_id,
				'post_status' => 'publish',
			);

			// Update the post into the database
			if ( wp_update_post( $my_post ) ) {

				// Notify that the review was updated
				yith_ywar_notify_review_update( $review_id );
			}
		}

		public function add_review_table_class( $classes, $class, $post_id ) {

			if ( YITH_YWAR_POST_TYPE != get_post_type( $post_id ) ) {
				return $classes;
			}

			unset( $classes["review-unapproved"] );
			unset( $classes["review-approved"] );

			$review_approved = get_post_meta( $post_id, YITH_YWAR_META_APPROVED, true );

			if ( 1 == $review_approved ) {
				$classes[] = "review-approved";
			} elseif ( 0 == $review_approved ) {
				$classes[] = "review-unapproved";
			}

			return apply_filters( 'yith_advanced_reviews_table_class', $classes, $post_id );
		}

		/**
		 * Build a url to be using as action url in row actions
		 *
		 * @param string $action  action to be performed
		 * @param int    $post_id review id
		 *
		 * @return string|void the url used to send an "approve" action for a specific review
		 */
		public function review_action_url( $action, $post_id ) {
			return admin_url( "admin.php?action=$action&post_type=" . YITH_YWAR_POST_TYPE . "&review_id=$post_id" );
		}

		/**
		 * Build an "untrash" action url
		 *
		 * @param WP_Post $review the review on which the action is performed
		 *
		 * @return string|void action url
		 */
		public function untrash_review_url( $review ) {
			return $this->review_action_url( YITH_YWAR_ACTION_UNTRASH_REVIEW, $review->ID );
		}

		/**
		 * Build an "approve" action url
		 *
		 * @param WP_Post $review the review on which the action is performed
		 *
		 * @return string|void action url
		 */
		public function approve_review_url( $review ) {
			return $this->review_action_url( YITH_YWAR_ACTION_APPROVE_REVIEW, $review->ID );
		}

		/**
		 * Build an "unapprove" action url
		 *
		 * @param WP_Post $review the review on which the action is performed
		 *
		 * @return string|void action url
		 */
		public function unapprove_review_url( $review ) {
			return $this->review_action_url( YITH_YWAR_ACTION_UNAPPROVE_REVIEW, $review->ID );
		}

		public function add_review_actions( $actions, $post ) {

			if ( $post->post_type != YITH_YWAR_POST_TYPE ) {
				return $actions;
			}

			$review_approved = get_post_meta( $post->ID, YITH_YWAR_META_APPROVED, true );

			unset( $actions['view'] );
			unset( $actions['inline hide-if-no-js'] );

			if ( 0 == $review_approved ) {
				$actions[ YITH_YWAR_ACTION_APPROVE_REVIEW ] = '<a href="' . $this->approve_review_url( $post ) . '" title="' . esc_attr( __( 'Approve review', 'yith-woocommerce-advanced-reviews' ) ) . '" rel="permalink">' . __( 'Approve', 'yith-woocommerce-advanced-reviews' ) . '</a>';
			} elseif ( 1 == $review_approved ) {
				$actions['unapprove-review'] = '<a href="' . $this->unapprove_review_url( $post ) . '" title="' . esc_attr( __( 'Unapprove review', 'yith-woocommerce-advanced-reviews' ) ) . '" rel="permalink">' . __( 'Unapprove', 'yith-woocommerce-advanced-reviews' ) . '</a>';
			}

			return apply_filters( 'yith_advanced_reviews_review_actions', $actions, $post );
		}

		/**
		 * Retrieve the average value of approved reviews
		 *
		 * @param int $product_id
		 *
		 * @return string
		 */
		public function get_average_rating( $product_id ) {
			$transient_name = 'yith_average_rating_' . $product_id . WC_Cache_Helper::get_transient_version( 'product' );

			if ( false === ( $average_rating = get_transient( $transient_name ) ) ) {
				global $wpdb;

				$query = $wpdb->prepare( "
                    select avg(pm.meta_value)
                    from {$wpdb->prefix}posts po
                    left join {$wpdb->prefix}postmeta pm  on po.ID = pm.post_id
                    left join {$wpdb->prefix}postmeta pm2  on po.ID = pm2.post_id
                    left join {$wpdb->prefix}postmeta pm3  on po.ID = pm3.post_id
                    where pm.meta_key = %s AND 
                    pm2.meta_key = %s AND 
                    pm3.meta_key = %s AND 
                    pm2.meta_value = %d AND 
                    pm3.meta_value = 1 AND 
                    po.post_parent = '0' AND 
                    po.post_status = 'publish'",
					YITH_YWAR_META_KEY_RATING,
					YITH_YWAR_META_KEY_PRODUCT_ID,
					YITH_YWAR_META_APPROVED,
					$product_id );

				$count = $wpdb->get_var( $query );

				$average_rating = number_format( $count, 2 );

				set_transient( $transient_name, $average_rating, DAY_IN_SECONDS * 30 );
			}

			return $average_rating;
		}

		/**
		 * Show the reviews average information
		 *
		 * @param WC_Product $product
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function add_reviews_average_info( $product ) {

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				return;
			}

			global $product;

			$average = $this->get_average_rating( $product->id );

			$count      = count( $this->get_product_reviews_by_rating( $product->id ) );
			$rated_text = sprintf( __( 'Rated %s out of 5 stars', 'yith-woocommerce-advanced-reviews' ), esc_html( $average ) );

			if ( $count > 0 ) {
				?>
				<div class="woocommerce-product-rating">
					<div class="star-rating  trr2" title="<?php echo $rated_text; ?>">
                        <span style="width:<?php echo( ( $average / 5 ) * 100 ); ?>%">
	                        <span class="review-rating-value"><?php echo $rated_text; ?></span>
                        </span>
					</div>
					<span class="ywar_review_count"><?php printf( "%d %s", $count, _n( " review", " reviews", $count, 'yith-woocommerce-advanced-reviews' ) ); ?></span>

				</div>
				<?php
			}
		}

		//region ***********    YIT Plugin Framework   ****************


		/**
		 * Enqueue css file
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT' ) || ! defined( 'YIT_CORE_PLUGIN' ) ) {

				require_once( YITH_YWAR_DIR . 'plugin-fw/yit-plugin.php' );
			}
		}

		/**
		 * plugin_row_meta
		 *
		 * add the action links to plugin admin page
		 *
		 * @param array $plugin_meta
		 * @param       $plugin_file
		 * @param       $plugin_data
		 * @param       $status
		 *
		 * @return   array
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use      plugin_row_meta
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( ( defined( 'YITH_YWAR_INIT' ) && ( YITH_YWAR_INIT == $plugin_file ) ) ||
			     ( defined( 'YITH_YWAR_FREE_INIT' ) && ( YITH_YWAR_FREE_INIT == $plugin_file ) )
			) {
				$plugin_meta[] = '<a href="' . $this->_official_documentation . '" target="_blank">' . __( 'Plugin Documentation', 'yith-woocommerce-advanced-reviews' ) . '</a>';
			}

			return $plugin_meta;
		}

		/**
		 * Action Links
		 *
		 * add the action links to plugin admin page
		 *
		 * @param $links | links plugin array
		 *
		 * @return   mixed Array
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return mixed
		 * @use      plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {

			$links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'yith-woocommerce-advanced-reviews' ) . '</a>';

			if ( defined( 'YITH_YWAR_FREE_INIT' ) ) {
				$links[] = '<a href="' . $this->get_premium_landing_uri() . '" target="_blank">' . __( 'Premium Version', 'yith-woocommerce-advanced-reviews' ) . '</a>';
			}

			return $links;
		}

		public function register_pointer() {
			if ( ! class_exists( 'YIT_Pointers' ) ) {
				include_once( 'plugin-fw/lib/yit-pointers.php' );
			}

			$premium_message = defined( 'YITH_YWAR_PREMIUM' )
				? ''
				: __( 'YITH WooCommerce Advanced Reviews is available in an outstanding PREMIUM version with many new options, discover it now.', 'yith-woocommerce-advanced-reviews' ) .
				  ' <a href="' . $this->get_premium_landing_uri() . '">' . __( 'Premium version', 'yith-woocommerce-advanced-reviews' ) . '</a>';

			$args[] = array(
				'screen_id'  => 'plugins',
				'pointer_id' => 'yith_ywar_panel',
				'target'     => '#toplevel_page_yit_plugin_panel',
				'content'    => sprintf( '<h3> %s </h3> <p> %s </p>',
					__( 'YITH WooCommerce Advanced Reviews', 'yith-woocommerce-advanced-reviews' ),
					__( 'In YIT Plugins tab you can find YITH WooCommerce Advanced Reviews options. From this menu you can access all settings of YITH plugins activated.', 'yith-woocommerce-advanced-reviews' ) . '<br>' . $premium_message
				),
				'position'   => array( 'edge' => 'left', 'align' => 'center' ),
				'init'       => defined( 'YITH_YWAR_PREMIUM' ) ? YITH_YWAR_INIT : YITH_YWAR_FREE_INIT,
			);

			$args[] = array(
				'screen_id'  => 'update',
				'pointer_id' => 'yith_ywar_panel',
				'target'     => '#toplevel_page_yit_plugin_panel',
				'content'    => sprintf( '<h3> %s </h3> <p> %s </p>',
					__( 'YITH WooCommerce Advanced Reviews', 'yith-woocommerce-advanced-reviews' ),
					__( 'From now on, you can find all YITH WooCommerce Advanced Reviews options in YIT Plugin -> Advanced Reviews instead of WooCommerce -> Settings -> Advanced Reviews, as in the previous version. Any time one of our plugins is updated, a new entry will be added to this menu.', 'yith-woocommerce-advanced-reviews' ) . $premium_message
				),
				'position'   => array( 'edge' => 'left', 'align' => 'center' ),
				'init'       => defined( 'YITH_YWAR_PREMIUM' ) ? YITH_YWAR_INIT : YITH_YWAR_FREE_INIT,
			);

			YIT_Pointers()->register( $args );
		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return  string The premium landing link
		 */
		public function get_premium_landing_uri() {
			return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing . '?refer_id=1030585';
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use      /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$admin_tabs = array(
				'general' => __( 'General', 'yith-woocommerce-advanced-reviews' ),
				'layout'  => __( 'Layout', 'yith-woocommerce-advanced-reviews' ),
			);

			if ( defined( 'YITH_YWAR_PREMIUM' ) ) {
				$admin_tabs['premium'] = __( 'Voting/Review settings', 'yith-woocommerce-advanced-reviews' );
			} else {
				$admin_tabs['premium-landing'] = __( 'Premium Version', 'yith-woocommerce-advanced-reviews' );
			}

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => __( 'Advanced Reviews', 'yith-woocommerce-advanced-reviews' ),
				'menu_title'       => __( 'Advanced Reviews', 'yith-woocommerce-advanced-reviews' ),
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->_panel_page,
				'admin-tabs'       => $admin_tabs,
				'options-path'     => YITH_YWAR_DIR . '/plugin-options',
			);

			/* === Fixed: not updated theme  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once( 'plugin-fw/lib/yit-plugin-panel-wc.php' );
			}

			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Premium Tab Template
		 *
		 * Load the premium tab template on admin page
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return void
		 */
		public function premium_tab() {
			$premium_tab_template = YITH_YWAR_TEMPLATES_DIR . 'admin/' . $this->_premium;
			if ( file_exists( $premium_tab_template ) ) {
				include_once( $premium_tab_template );
			}
		}

		//endregion

		//region    ***********  Add stylesheets and scripts files  ************

		/**
		 * Add scripts
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function enqueue_resource_frontend() {

			if ( ! is_product() ) {
				return;
			}

			//  register and enqueue ajax calls related script file
			wp_register_script( "attachments-script", yith_maybe_script_minified_path( YITH_YWAR_URL . 'assets/js/ywar-attachments' ), array(
				'jquery',
				'comment-reply',
			), false, true );

			wp_localize_script( 'attachments-script', 'attach', array(
				'limit_multiple_upload'        => $this->attachments_limit,
				'too_many_attachment_selected' => __( 'Too many files selected!', 'yith-woocommerce-advanced-reviews' ),
				'no_attachment_selected'       => __( 'No Files Selected', 'yith-woocommerce-advanced-reviews' ),
			) );

			wp_enqueue_script( 'attachments-script' );

			wp_enqueue_style( 'ywar-frontend', YITH_YWAR_ASSETS_URL . '/css/ywar-frontend.css' );

		}

		/**
		 * Enqueue scripts on administration comment page
		 *
		 * @param $hook
		 */
		function enqueue_resource_backend( $hook ) {
			global $pagenow;

			if ( ( 'toplevel_page_Reviews' == $hook ) ||
			     ( ( 'post.php' == $hook ) && ( YITH_YWAR_POST_TYPE == get_current_screen()->post_type ) )
			) {

				/** Add Woocommerce.css file */
				$styles = (array) WC_Frontend_Scripts::get_styles();

				if ( array_key_exists( 'woocommerce-general', $styles ) ) {
					wp_enqueue_style( 'woocommerce-general', $styles['woocommerce-general']['src'] );
				}

				wp_enqueue_style( 'ywar-backend', YITH_YWAR_ASSETS_URL . '/css/ywar-backend.css' );

				wp_register_script( "ywar-backend", yith_maybe_script_minified_path( YITH_YWAR_URL . 'assets/js/ywar-back-end' ), array(
					'jquery',
					'jquery-blockui',
				) );

				$loader = apply_filters( 'yith_advanced_reviews_loader_gif', YITH_YWAR_ASSETS_URL . '/images/loading.gif' );

				wp_localize_script( 'ywar-backend', 'ywar', array(
					'loader'   => $loader,
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				) );

				wp_enqueue_script( "ywar-backend" );
			}
		}

		//endregion

		//region    ***********   Advanced reviews post type functionalities

		/**
		 * Register advanced reviews post type
		 */
		public function register_post_type() {
			// Set UI labels for Custom Post Type
			$labels = array(
				'name'               => _x( 'Reviews', 'Post Type General Name', 'yith-woocommerce-advanced-reviews' ),
				'singular_name'      => _x( 'Review', 'Post Type Singular Name', 'yith-woocommerce-advanced-reviews' ),
				'menu_name'          => __( 'Reviews', 'yith-woocommerce-advanced-reviews' ),
				'parent_item_colon'  => __( 'Parent Review', 'yith-woocommerce-advanced-reviews' ),
				'all_items'          => __( 'All reviews', 'yith-woocommerce-advanced-reviews' ),
				'view_item'          => __( 'View review', 'yith-woocommerce-advanced-reviews' ),
				'add_new_item'       => __( 'Add New Review', 'yith-woocommerce-advanced-reviews' ),
				'add_new'            => __( 'Add New', 'yith-woocommerce-advanced-reviews' ),
				'edit_item'          => __( 'Edit Review', 'yith-woocommerce-advanced-reviews' ),
				'update_item'        => __( 'Update Review', 'yith-woocommerce-advanced-reviews' ),
				'search_items'       => __( 'Search Review', 'yith-woocommerce-advanced-reviews' ),
				'not_found'          => __( 'Not Found', 'yith-woocommerce-advanced-reviews' ),
				'not_found_in_trash' => __( 'Not found in bin', 'yith-woocommerce-advanced-reviews' ),
			);

			// Set other options for Custom Post Type

			$args = array(
				'label'               => __( 'YIT Product reviews', 'yith-woocommerce-advanced-reviews' ),
				'description'         => __( 'Advanced WooCommerce product reviews', 'yith-woocommerce-advanced-reviews' ),
				'labels'              => $labels,
				// Features this CPT supports in Post Editor
				'supports'            => array(
					'title',
					'editor',
				),
				/* A hierarchical CPT is like Pages and can have
				* Parent and child items. A non-hierarchical CPT
				* is like Posts.
				*/
				'hierarchical'        => true,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => 9,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-star-filled',
				'query_var'           => false,
			);

			// Registering your Custom Post Type
			register_post_type( YITH_YWAR_POST_TYPE, $args );
		}

		/**
		 * Default query arguments to be used where querying to review custom post type
		 *
		 * @param $product_id
		 *
		 * @return array
		 */
		function default_query_args( $product_id ) {
			return array(
				'numberposts' => - 1,    //By default retrieve all reviews
				'offset'      => 0,
				'orderby'     => 'post_date',
				'order'       => 'DESC',
				'post_type'   => YITH_YWAR_POST_TYPE,
				'post_parent' => '0',
				'post_status' => 'publish',
				'meta_query'  => array(
					'relation' => 'AND',
					array(
						'key'     => YITH_YWAR_META_KEY_PRODUCT_ID,
						'value'   => $product_id,
						'compare' => '=',
						'type'    => 'numeric',
					),
					array(
						'key'     => YITH_YWAR_META_APPROVED,
						'value'   => 1,
						'compare' => '=',
						'type'    => 'numeric',
					),
				),
			);
		}

		/**
		 * Retrieve reviews for a product
		 *
		 * @param int $product_id the product id for whose retrieve the reviews
		 *
		 * @return array
		 */
		public function get_product_reviews( $product_id = null, $args = null ) {
//            $transient_name = 'yith_ratings_' . $product_id . WC_Cache_Helper::get_transient_version( 'product' );
//
//            if ( false === ( $rating_ids = get_transient( $transient_name ) ) ) {

			if ( $args == null ) {
				$args = $this->default_query_args( $product_id );
			}

			//  if $product_id is null, retrieve all reviews without filters
			if ( is_null( $product_id ) ) {
				unset( $args['meta_query'] );
			}

			$args['fields'] = 'ids';
			$rating_ids     = get_posts( $args );
//                set_transient( $transient_name, $rating_ids, 30 * DAY_IN_SECONDS );
//            }

			return array_map( 'get_post', $rating_ids );
		}


		/**
		 * Retrieve the product reviews for a specific product
		 *
		 * @param int $product_id The product id
		 * @param int $rating     (Optional) The rating value to use as filter
		 *
		 * @return array
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_product_reviews_by_rating( $product_id, $rating = 0 ) {
			$args = $this->default_query_args( $product_id );
			if ( $rating > 0 ) {
				$args['meta_query'][] = array(
					'key'     => YITH_YWAR_META_KEY_RATING,
					'value'   => $rating,
					'compare' => '=',
					'type'    => 'numeric',
				);
			}

			return $this->get_product_reviews( $product_id, $args );
		}


		/**
		 * Show the reviews for a specific product
		 *
		 * @param int   $product_id The product id of the reviews to be shown
		 * @param array $args       The argument to be used as a filter for the reviews
		 */
		public function reviews_list( $product_id, $args = null ) {
			$reviews = $this->get_product_reviews( $product_id, $args );

			foreach ( $reviews as $review ) {
				$this->show_review( $review );
			}
		}

		/**
		 * Call the review template and show the review
		 *
		 * @param  WP_Post   $review   current review
		 * @param bool|false $featured is featured
		 * @param string     $classes  css class to be used
		 */
		public function show_review( $review, $featured = false, $show_childs = false ) {
			global $ywar_review;
			$ywar_review = $review;

			wc_get_template( 'ywar-review.php',
				array(
					'review'      => $review,
					'featured'    => $featured,
					'classes'     => $featured ? 'review-featured' : '',
					'rating'      => YITH_YWAR()->get_meta_value_rating( $review->ID ),
					'approved'    => YITH_YWAR()->get_meta_value_approved( $review->ID ),
					'product_id'  => YITH_YWAR()->get_meta_value_product_id( $review->ID ),
					'review_date' => version_compare( WC()->version, '2.5', '<' ) ?
						mysql2date( get_option( 'date_format' ), $review->post_date ) :
						mysql2date( wc_date_format(), $review->post_date ),
				),
				'',
				YITH_YWAR_TEMPLATES_DIR );

			if ($show_childs) {
				$review_childs = $this->get_childs_review( $review );
				if ( count( $review_childs ) > 0 ) {

					echo '<ul class="children">';
					foreach ( $review_childs as $review ) {
						$this->show_review( $review );
					}

					echo '</ul>';
				}
			}
		}

		//endregion

		/**
		 * Initialize plugin options
		 *
		 * @since  1.0
		 * @access public
		 * @access public
		 * @return void
		 * @author Lorenzo Giuffrida
		 */
		public function initialize_settings() {

			$this->enable_title            = get_option( 'ywar_enable_review_title' ) === 'yes';
			$this->enable_attachments      = get_option( 'ywar_enable_attachments' ) === 'yes';
			$this->attachments_limit       = get_option( 'ywar_max_attachments' );
			$this->attachment_allowed_type = get_option( 'ywar_attachment_type', '' );
			$this->attachment_max_size     = get_option( 'ywar_attachment_max_size', 0 );
		}

		/**
		 * Return the right path to the reviews template file
		 *
		 * @param string $template the template that is currently used
		 *
		 * @return mixed|void new template path, only for product comments page
		 */
		public function show_advanced_reviews_template( $template ) {

			if ( get_post_type() === 'product' ) {
				//  return apply_filters( 'ywar_show_advanced_reviews_template', YITH_YWAR_TEMPLATES_DIR . "ywar-product-reviews.php" );
				return wc_locate_template( "ywar-product-reviews.php", '', YITH_YWAR_TEMPLATES_DIR );
			}

			return $template;
		}

		/**
		 * Create new Advanced Review post type when a comment is saved to database
		 *
		 * @param int        $comment_id       The comment ID.
		 * @param int|string $comment_approved if the comment is approved, 0 if not, 'spam' if spam.
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function submit_review_on_comment_post( $comment_id, $comment_approved ) {
			if ( ! isset( $_POST["comment_post_ID"] ) ) {
				return;
			}

			$post_id = $_POST["comment_post_ID"];
			if ( 'product' != get_post_type( $post_id ) ) {
				return;
			}

			$review_title = $this->enable_title && isset( $_POST["title"] ) ? wp_strip_all_tags( $_POST["title"] ) : '';

			$post_parent = apply_filters( 'yith_advanced_reviews_post_parent', $_POST["comment_parent"] );

			$comment = get_comment( $comment_id );

			// Create post object
			$my_post = array(
				'post_title'          => $review_title,
				'post_content'        => wp_strip_all_tags( $comment->comment_content ),
				'post_status'         => 'publish',
				'post_author'         => get_current_user_id(),
				'post_type'           => YITH_YWAR_POST_TYPE,
				'post_parent'         => $post_parent,
				'review_user_id'      => $comment->user_id,
				'review_rating'       => ( isset( $_POST["rating"] ) ? $_POST["rating"] : 0 ),
				'review_product_id'   => $comment->comment_post_ID,
				'review_comment_id'   => $comment_id,
				'review_approved'     => apply_filters( 'yith_advanced_reviews_approve_new_review', $comment_approved ),
				'review_author'       => $comment->comment_author,
				'review_author_email' => $comment->comment_author_email,
				'review_author_IP'    => $comment->comment_author_IP,
				'review_author_url'   => $comment->comment_author_url,
			);

			$validation_passed = apply_filters( 'yith_ywar_comment_validation_passed', true, $comment_id );

			if ( ! $validation_passed ) {
				return;
			}

			// Insert the post into the database
			$review_id = $this->insert_review( $my_post );

			$this->submit_attachments( $review_id );
		}

		/**
		 * Create a new CPT object with the review's data
		 *
		 * @param array $args
		 *
		 * @return int|WP_Error
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function insert_review( $args ) {
			// Create post object
			$defaults = array(
				'post_title'                 => '',
				'post_content'               => '',
				'post_status'                => 'publish',
				'post_author'                => 0,
				'post_type'                  => YITH_YWAR_POST_TYPE,
				'post_parent'                => 0,
				'review_user_id'             => 0,
				'review_approved'            => 1,
				'review_rating'              => 0,
				'review_product_id'          => 0,
				'review_comment_id'          => 0,
				'review_upvotes'             => 0,
				'review_downvotes'           => 0,
				'review_votes'               => array(),
				'review_inappropriate_list'  => array(),
				'review_inappropriate_count' => 0,
				'review_is_featured'         => 0,
				'review_is_reply_blocked'    => 0,
				'review_thumbnails'          => array(),
				'review_author'              => '',
				'review_author_email'        => '',
				'review_author_url'          => '',
				'review_author_IP'           => '',
			);

			$args = wp_parse_args( $args, $defaults );

			// Insert the post into the database
			$review_id = wp_insert_post( $args );

			//  Set rating only for top level reviews, not for replies
			if ( 0 != $args["post_parent"] ) {
				update_post_meta( $review_id, YITH_YWAR_META_KEY_RATING, 0 );
			} else {
				update_post_meta( $review_id, YITH_YWAR_META_KEY_RATING, $args["review_rating"] );
			}

			update_post_meta( $review_id, YITH_YWAR_META_KEY_RATING, $args["review_rating"] );

			update_post_meta( $review_id, YITH_YWAR_META_KEY_PRODUCT_ID, $args["review_product_id"] );
			update_post_meta( $review_id, YITH_YWAR_META_COMMENT_ID, $args["review_comment_id"] );
			update_post_meta( $review_id, YITH_YWAR_META_THUMB_IDS, $args["review_thumbnails"] );

			update_post_meta( $review_id, YITH_YWAR_META_UPVOTES_COUNT, $args["review_upvotes"] );
			update_post_meta( $review_id, YITH_YWAR_META_DOWNVOTES_COUNT, $args["review_downvotes"] );
			update_post_meta( $review_id, YITH_YWAR_META_VOTES, $args["review_votes"] );

			update_post_meta( $review_id, YITH_YWAR_META_KEY_INAPPROPRIATE_LIST, $args["review_inappropriate_list"] );
			update_post_meta( $review_id, YITH_YWAR_META_KEY_INAPPROPRIATE_COUNT, $args["review_inappropriate_count"] );
			update_post_meta( $review_id, YITH_YWAR_META_KEY_FEATURED, $args["review_is_featured"] );
			update_post_meta( $review_id, YITH_YWAR_META_STOP_REPLY, $args["review_is_reply_blocked"] );

			update_post_meta( $review_id, YITH_YWAR_META_REVIEW_USER_ID, $args["review_user_id"] );
			update_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR, $args["review_author"] );
			update_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR_EMAIL, $args["review_author_email"] );
			update_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR_URL, $args["review_author_url"] );
			update_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR_IP, $args["review_author_IP"] );

			$this->set_approved_status( $review_id, $args["review_approved"] );

			// Notify that the review was updated
			yith_ywar_notify_product_reviews_update( $args["review_product_id"] );

			return $review_id;
		}

		/**
		 *    redirect to product page on comment submitted
		 */
		public function redirect_after_submit_review( $location, $comment ) {
			// Set the new comment as imported so it will not imported when clicking on "convert reviews", creating duplicated entries
			update_comment_meta( $comment->comment_ID, YITH_YWAR_META_IMPORTED, 1 );

			return get_permalink( $comment->comment_post_ID );
		}

		/**
		 * Add custom field "Title" on top of comment form
		 *
		 * Check if the "enable title" option is activated and add a title field on comment form
		 *
		 * @return void
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function add_custom_fields_on_comment_form() {

			if ( ! is_product() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				return;
			}

			if ( $this->enable_title ) {
				echo '<p class="comment-form-title"><label for="title">' . __( 'Review title', 'yith-woocommerce-advanced-reviews' ) . '</label><input type="text" name="title" id="title"></p>';
			}
		}

		/**
		 * Submit attachments from a comment form
		 *
		 * Check if attachment option is enabled and option value is satisfied, then upload attachment files.
		 *
		 * @param   int $review_id the review id the files are referred.
		 *
		 * @return  void
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function submit_attachments( $review_id ) {


			//  check if attachments are enabled
			if ( ! $this->enable_attachments ) {
				return;
			}

			if ( $_FILES ) {
				$files       = $_FILES["ywar-uploadFile"];
				$files_count = count( $files['name'] );

				//  check for attachments limits
				if ( ( $this->attachments_limit > 0 ) && ( $files_count > $this->attachments_limit ) ) {
					return;
				}

				$attacchments_array = array();

				foreach ( $files['name'] as $key => $value ) {
					if ( $files['name'][ $key ] ) {
						$file   = array(
							'name'     => $files['name'][ $key ],
							'type'     => $files['type'][ $key ],
							'tmp_name' => $files['tmp_name'][ $key ],
							'error'    => $files['error'][ $key ],
							'size'     => $files['size'][ $key ],
						);
						$_FILES = array( "ywar-uploadFile" => $file );

						foreach ( $_FILES as $file => $array ) {
							$attachId = $this->insert_attachment( $file, $review_id );

							//  enqueue attachments to current comment
							array_push( $attacchments_array, $attachId );
						}
					}
				}

				//  save review with attachments array
				update_post_meta( $review_id, YITH_YWAR_META_THUMB_IDS, $attacchments_array );
			}
		}

		/**
		 * Add attachment to media library
		 *
		 * @param   int    $postId
		 * @param   string $fileHandler
		 *
		 * @return false|int|WP_Error ID of the attachment or a WP_Error object on failure.
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function insert_attachment( $fileHandler, $postId ) {
			if ( $_FILES[ $fileHandler ]['error'] !== UPLOAD_ERR_OK ) {
				__return_false();
			}

			require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
			require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
			require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

			return media_handle_upload( $fileHandler, $postId );
		}

		/**
		 * Append attachment fields on comment form
		 *
		 * @param object $comment_form
		 *
		 * @return object $comment_form
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function add_fields_to_comment_form( $comment_form ) {

			$current_content = $comment_form['comment_field'];

			//  In case of a page refresh following a reply request, don't add additional fields
			$hide_rating = isset( $_REQUEST["replytocom"] ) ? "hide-rating" : '';
			$selected    = isset( $_REQUEST["replytocom"] ) ? "selected" : '';

			$content_before = '';
			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {

				$content_before = '<p class="' . $hide_rating . ' comment-form-rating">
				<label for="rating">' . __( 'Your Rating', 'yith-woocommerce-advanced-reviews' ) . '</label>
				<select name="rating" id="rating">
							<option value="">' . __( 'Rate&hellip;', 'yith-woocommerce-advanced-reviews' ) . '</option>
							<option value="5">' . __( 'Perfect', 'yith-woocommerce-advanced-reviews' ) . '</option>
							<option value="4">' . __( 'Good', 'yith-woocommerce-advanced-reviews' ) . '</option>
							<option value="3">' . __( 'Average', 'yith-woocommerce-advanced-reviews' ) . '</option>
							<option value="2">' . __( 'Not that bad', 'yith-woocommerce-advanced-reviews' ) . '</option>
							<option value="1" ' . $selected . '>' . __( 'Very Poor', 'yith-woocommerce-advanced-reviews' ) . '</option>
				</select>
				</p>';
			}

			$content_after = '';

			if ( $this->enable_attachments ) {
				$content_after = '<p class="upload_section ' . $hide_rating . '" >
					<label for="ywar-uploadFile" > ' . __( 'Attachments', 'yith-woocommerce-advanced-reviews' ) . ' </label >
					<input type = "button" value = "' . __( 'Choose file(s)', 'yith-woocommerce-advanced-reviews' ) . '" id = "do_uploadFile" />
					<input type = "file" name = "ywar-uploadFile[]" id = "ywar-uploadFile" multiple="multiple" />';

				$attachment_message = '';
				if ( trim( $this->attachment_allowed_type ) ) {
					$attachment_message .= sprintf( __( "Use one of the allowed file type: %s. ", 'yith-woocommerce-advanced-reviews' ), $this->attachment_allowed_type );
				}

				if ( $this->attachment_max_size > 0 ) {
					$attachment_message .= sprintf( __( "The max size allowed is : %s MB.", 'yith-woocommerce-advanced-reviews' ), $this->attachment_max_size );
				}

				if ( $attachment_message ) {
					$attachment_message = '<span class="ywar-upload-limitation">' . $attachment_message . '</span>';
				}

				$content_after .= $attachment_message . '</p><p><ul id = "uploadFileList" ></ul></p>';
			}

			$comment_form['comment_field'] = apply_filters( 'yith_ywar_add_content_before_review_text', $content_before ) .
			                                 $comment_form['comment_field'] .
			                                 apply_filters( 'yith_ywar_add_content_after_review_text', $content_after );

			return apply_filters( 'yith_ywar_customize_comment_form', $comment_form );
		}

		/**
		 * Display a customized comment content
		 *
		 * @param WP_Post $review
		 *
		 * @return  string  customized comment content
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function show_expanded_review_content( $review ) {

			if ( ! is_product() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				return $review->post_content;
			}

			$review_title  = '';
			$thumbnail_div = $this->get_thumbnails( $review );

			if ( $this->enable_title ) {
				//  Add review title before review content text
				if ( ! empty( $review->post_title ) ) {
					$review_title = '<span class="review_title"> ' . esc_attr( $review->post_title ) . '</span> ';
				}
			}

			return $review_title . nl2br( $review->post_content ) . $thumbnail_div;
		}

		/**
		 * Get an HTML formatted attachment section
		 *
		 * @param WP_Post $review the review for whose retrieve attachments
		 *
		 * @return  string
		 */
		public function get_thumbnails( $review ) {
			$is_toplevel   = ( 0 == $review->post_parent );
			$thumbnail_div = '';

			if ( $is_toplevel && $this->enable_attachments ) {

				if ( $thumbs = get_post_meta( $review->ID, YITH_YWAR_META_THUMB_IDS, true ) ) {

					$thumbnail_div = '<div class="ywar-review-thumbnails review_thumbnail horizontalRule"> ';

					foreach ( $thumbs as $thumb_id ) {
						$file_url    = wp_get_attachment_url( $thumb_id );
						$image_thumb = wp_get_attachment_image_src( $thumb_id, array( 100, 100 ), true );

						$thumbnail_div .= "<a href='$file_url' data-rel = \"prettyPhoto[review-gallery-{$review->ID}]\"><img class=\"ywar_thumbnail\" src='{$image_thumb[0]}' width='70px' height='70px'></a>";
					}
					$thumbnail_div .= ' </div> ';
				}
			}

			return $thumbnail_div;
		}

		/**
		 * Alter text on tab reviews, fixing wrong count of reviews(even replies to reviews were used
		 *
		 * @param array $tabs tabs with description for product reviews
		 *
		 * @return mixed
		 */
		public function update_tab_reviews_count( $tabs ) {
			global $product;

			if ( isset( $tabs['reviews'] ) ) {
				$tabs['reviews']['title'] = sprintf( __( 'Reviews (%d)', 'yith-woocommerce-advanced-reviews' ), count( $this->get_product_reviews_by_rating( $product->id ) ) );
			}

			return $tabs;
		}

		/**
		 * Collect data about reviews rating and show a summary grouped by stars
		 *
		 * @param   object $template a custom template to be shown
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function load_reviews_summary( $template ) {
			if ( ! is_product() ) {
				return $template;
			}

			global $product;
			global $review_stats;
			$review_stats = array(
				'1'     => count( $this->get_product_reviews_by_rating( $product->id, 1 ) ),
				'2'     => count( $this->get_product_reviews_by_rating( $product->id, 2 ) ),
				'3'     => count( $this->get_product_reviews_by_rating( $product->id, 3 ) ),
				'4'     => count( $this->get_product_reviews_by_rating( $product->id, 4 ) ),
				'5'     => count( $this->get_product_reviews_by_rating( $product->id, 5 ) ),
				'total' => count( $this->get_product_reviews_by_rating( $product->id ) ),
			);

			wc_get_template( 'ywar-single-product-reviews.php', null, '', YITH_YWAR_TEMPLATES_DIR );
		}

		/**
		 * Add a metabox on review page for review's title
		 *
		 * @return void
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function add_plugin_metabox() {
			add_meta_box( 'reviews-metabox', __( 'Review information', 'yith-woocommerce-advanced-reviews' ), array(
				$this,
				'display_plugin_metabox',
			), YITH_YWAR_POST_TYPE, 'normal', 'high' );
		}

		public function get_review_author( $review_id ) {

			$review_author_id = get_post_meta( $review_id, YITH_YWAR_META_REVIEW_USER_ID, true );
			$author_user      = get_user_by( 'id', $review_author_id );
			$is_modified_user = $author_user && ( '' != get_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR_CUSTOM, true ) );

			if ( $author_user && ! $is_modified_user ) {
				$author_name  = $author_user->display_name;
				$author_email = $author_user->user_email;
			} else {
				$author_name  = get_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR, true );
				$author_email = get_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR_EMAIL, true );
			}

			return array(
				'display_name'     => $author_name,
				'display_email'    => $author_email,
				'is_modified_user' => $is_modified_user,
			);
		}

		/**
		 * Display a meta box with additional review data, like title and thumbnails
		 *
		 * @return  void
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function display_plugin_metabox() {
			global $post;

			$thumbnail_div = '';

			$review_rating = get_post_meta( $post->ID, YITH_YWAR_META_KEY_RATING, true );

			$review_author_id = get_post_meta( $post->ID, YITH_YWAR_META_REVIEW_USER_ID, true );
			$author_user      = get_user_by( 'id', $review_author_id );
			$is_custom_user   = $author_user && ( '' != get_post_meta( $post->ID, YITH_YWAR_META_REVIEW_AUTHOR_CUSTOM, true ) );
			$is_edit_blocked  = get_post_meta( $post->ID, YITH_YWAR_META_REVIEW_BLOCK_EDIT, true );

			if ( $author_user && ! $is_custom_user ) {
				$review_author_name  = $author_user->display_name;
				$review_author_email = $author_user->user_email;
			} else {
				$review_author_name  = get_post_meta( $post->ID, YITH_YWAR_META_REVIEW_AUTHOR, true );
				$review_author_email = get_post_meta( $post->ID, YITH_YWAR_META_REVIEW_AUTHOR_EMAIL, true );
			}
			$disabled_text = $author_user && ! $is_custom_user ? 'disabled' : '';

			?>
			<div class="woocommerce_options_panel">

				<p class="form-field">
					<label
						for="<?php echo YITH_YWAR_META_KEY_RATING; ?>"><?php _e( "Rating", 'yith-woocommerce-advanced-reviews' ); ?></label>
					<select name="<?php echo YITH_YWAR_META_KEY_RATING; ?>"
					        id="<?php echo YITH_YWAR_META_KEY_RATING; ?>">
						<?php for ( $rating = 1; $rating <= 5; $rating ++ ) {
							echo sprintf( '<option value="%1$s"%2$s>%1$s</option>', $rating, selected( $review_rating, $rating, false ) );
						} ?>
					</select>
				</p>

				<?php // Generate a hidden nonce used for verifying if a request to update the following values came from here ?>
				<input type="hidden" name="review_metabox_nonce" id="review_metabox_nonce"
				       value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

				<div class="ywar-author-info">
					<input type="hidden" name="_ywar_author_is_user"
					       value="<?php echo $author_user ? 1 : 0; ?>" />

					<p class="form-field">
						<label
							for="<?php echo YITH_YWAR_META_REVIEW_BLOCK_EDIT; ?>"><?php _e( "Block review edit", 'yith-woocommerce-advanced-reviews' ); ?></label>
						<input type="checkbox" name="<?php echo YITH_YWAR_META_REVIEW_BLOCK_EDIT; ?>"
						       id="<?php echo YITH_YWAR_META_REVIEW_BLOCK_EDIT; ?>" value="yes" <?php echo checked( 'yes', $is_edit_blocked, false ); ?>>
					</p>

					<?php if ( $author_user && $author_user->ID ): ?>
						<span><?php _e( "The review author is a registered user.", 'yith-woocommerce-advanced-reviews' ) ?></span>
						<a href="<?php echo get_edit_user_link( $author_user->ID ); ?>"><?php _e( "User profile", 'yith-woocommerce-advanced-reviews' ) ?></a>
						<p class="form-field">
							<label
								for="<?php echo YITH_YWAR_META_REVIEW_AUTHOR; ?>"><?php _e( "Use user data", 'yith-woocommerce-advanced-reviews' ); ?></label>
							<input type="checkbox" name="ywar-user-data" <?php echo checked( '1', ! $is_custom_user, false ); ?>
							       id="ywar-user-data" value="1">
						</p>
					<?php endif; ?>

					<?php //  Show review author and email ?>
					<p class="form-field">
						<label
							for="<?php echo YITH_YWAR_META_REVIEW_AUTHOR; ?>"><?php _e( "Author", 'yith-woocommerce-advanced-reviews' ); ?></label>
						<input type="text" name="<?php echo YITH_YWAR_META_REVIEW_AUTHOR; ?>"
						       id="<?php echo YITH_YWAR_META_REVIEW_AUTHOR; ?>" <?php echo $disabled_text; ?>
						       value="<?php echo $review_author_name; ?>">
					</p>

					<p class="form-field">
						<label
							for="<?php echo YITH_YWAR_META_REVIEW_AUTHOR_EMAIL; ?>"><?php _e( "Author email", 'yith-woocommerce-advanced-reviews' ); ?></label>
						<input type="text"
						       name="<?php echo YITH_YWAR_META_REVIEW_AUTHOR_EMAIL; ?>"
						       id="<?php echo YITH_YWAR_META_REVIEW_AUTHOR_EMAIL; ?>" <?php echo $disabled_text; ?>
						       value="<?php echo $review_author_email; ?>">
					</p>
				</div>
			</div>
			<?php
			//  Show thumbnails
			$review_thumbnails = get_post_meta( $post->ID, YITH_YWAR_META_THUMB_IDS, true );

			if ( isset ( $review_thumbnails ) && is_array( $review_thumbnails ) ) {
				$thumbnail_div
					= '
			<div style="padding-top: 10px;padding-bottom: 10px;overflow:hidden"> ';
				foreach ( $review_thumbnails as $thumb_id ) {
					$file_url    = wp_get_attachment_url( $thumb_id );
					$image_thumb = wp_get_attachment_image_src( $thumb_id, array( 100, 100 ), true );

					$thumbnail_div
						.= "<a href='$file_url'><img src='{$image_thumb[0]}' width='{$image_thumb[1]}'
				                                            height='{$image_thumb[2]}'></a>";
				}
				$thumbnail_div
					.= '
			</div>';
			}

			echo $thumbnail_div;
		}

		/**
		 * Save the Metabox Data
		 *
		 * @param int     $post_id
		 * @param WP_Post $post
		 *
		 * @return mixed
		 */
		function save_plugin_metabox( $post_id, $post ) {

			if ( ! isset( $_POST['review_metabox_nonce'] ) ) {
				return $post->ID;
			}

			// verify the save request started from review edit page...
			if ( ! wp_verify_nonce( $_POST['review_metabox_nonce'], plugin_basename( __FILE__ ) ) ) {
				return $post->ID;
			}

			// Check for authorization
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return $post->ID;
			}

			// OK, we're authenticated: we need to find and save the data
			if ( isset( $_POST[ YITH_YWAR_META_KEY_RATING ] ) ) {
				$rating = $_POST[ YITH_YWAR_META_KEY_RATING ];

				if ( is_numeric( $rating ) && ( $rating > 0 ) && ( $rating <= 5 ) ) {
					update_post_meta( $post_id, YITH_YWAR_META_KEY_RATING, $rating );
					yith_ywar_notify_review_update( $post_id );
				}
			}

			$author_is_user     = $_POST["_ywar_author_is_user"];
			$use_author_data    = isset( $_POST['ywar-user-data'] ) && $_POST['ywar-user-data'];
			$save_custom_author = ! $author_is_user || ! $use_author_data ? 1 : '';
			$is_edit_blocked    = isset( $_POST[ YITH_YWAR_META_REVIEW_BLOCK_EDIT ] ) ? 'yes' : 'no';

			$author_name  = $save_custom_author ? $_POST[ YITH_YWAR_META_REVIEW_AUTHOR ] : '';
			$author_email = $save_custom_author ? $_POST[ YITH_YWAR_META_REVIEW_AUTHOR_EMAIL ] : '';

			update_post_meta( $post_id, YITH_YWAR_META_REVIEW_AUTHOR, $author_name );
			update_post_meta( $post_id, YITH_YWAR_META_REVIEW_AUTHOR_EMAIL, $author_email );
			update_post_meta( $post_id, YITH_YWAR_META_REVIEW_AUTHOR_CUSTOM, $save_custom_author );
			update_post_meta( $post_id, YITH_YWAR_META_REVIEW_BLOCK_EDIT, $is_edit_blocked );
		}

		/**
		 * Remove features for the review custom post type
		 */
		public function remove_unwanted_custom_post_type_features() {
			global $submenu;

			// Remove Add new for review custom post type
			unset( $submenu[ "edit.php?post_type=" . YITH_YWAR_POST_TYPE ][10] );
		}

		public function hide_unwanted_custom_post_type_features() {
			if ( YITH_YWAR_POST_TYPE == get_post_type() ) {
				echo '
			<style type="text/css">
				
				.add-new-h2 {
					display: none;
				}
			
			</style>';
			}
		}

		/**
		 * Retrieve value for the "rating" meta_key for a specific review
		 *
		 * @param int $review_id review id from which retrieve the meta_value
		 *
		 * @return mixed meta_value for "rating" meta_key
		 */
		function get_meta_value_rating( $review_id ) {
			return get_post_meta( $review_id, YITH_YWAR_META_KEY_RATING, true );
		}

		/**
		 * Retrieve value for the "approved" meta_key for a specific review
		 *
		 * @param int $review_id review id from which retrieve the meta_value
		 *
		 * @return mixed meta_value for "approved" meta_key
		 */
		function get_meta_value_approved( $review_id ) {
			return get_post_meta( $review_id, YITH_YWAR_META_APPROVED, true );
		}

		/**
		 * Retrieve value for the "product_id" meta_key for a specific review
		 *
		 * @param int $review_id review id from which retrieve the meta_value
		 *
		 * @return mixed meta_value for "product_id" meta_key
		 */
		function get_meta_value_product_id( $review_id ) {
			return get_post_meta( $review_id, YITH_YWAR_META_KEY_PRODUCT_ID, true );
		}

		/**
		 * Retrieve information about the review author
		 *
		 * @param int $review_id review id from which retrieve the meta_value
		 *
		 * @return array author's information
		 */
		function get_meta_value_author( $review_id ) {
			return array(
				'review_user_id'      => get_post_meta( $review_id, YITH_YWAR_META_REVIEW_USER_ID, true ),
				'review_author'       => get_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR, true ),
				'review_author_email' => get_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR_EMAIL, true ),
				'review_author_url'   => get_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR_URL, true ),
				'review_author_IP'    => get_post_meta( $review_id, YITH_YWAR_META_REVIEW_AUTHOR_IP, true ),
			);
		}

		/**
		 * Retrieve the plugin template that override the default WooCommerce ones
		 *
		 * @param $located
		 * @param $template_name
		 * @param $args
		 * @param $template_path
		 * @param $default_path
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {
			if ( "single-product/rating.php" != $template_name ) {
				return $located;
			}

			$located = wc_locate_template( "ywar-rating.php", $template_path, $default_path );

			if ( file_exists( $located ) ) {
				return $located;
			}

			return YITH_YWAR_TEMPLATES_DIR . 'ywar-rating.php';
		}

		/**
		 * Retrieve the HTML content for the product rating
		 *
		 * @param $rating_html
		 * @param $rating
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_product_rating_html( $rating_html, $rating ) {

			global $product;

			$rating_html = '';

			if ( ! $product ) {
				return $rating_html;
			}

			$rating = $this->get_average_rating( $product->id );

			if ( $rating > 0 ) {

				$rating_html =
					'<div class="star-rating trr1" title="' . sprintf( __( 'Rated %s out of 5', 'yith-woocommerce-advanced-reviews' ), $rating ) . '">
						<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%">
									<strong class="rating">' . $rating . '</strong> ' .
					__( 'out of 5', 'yith-woocommerce-advanced-reviews' ) .
					'</span></div>';
			}

			return $rating_html;
		}


		/**
		 * Show import button for starting convertion from standard system to YITH Advanced reviews
		 *
		 * @param array $args
		 */
		public function show_import_reviews_button( $args = array() ) {
			if ( ! empty( $args ) ) {
				$args['value'] = ( get_option( $args['id'] ) ) ? get_option( $args['id'] ) : '';
				extract( $args );
			}

			?>
			<tr valign="top">
				<th scope="row"></th>

				<td class="forminp forminp-color plugin-option">
					<div class="convert-reviews">
						<a href="<?php echo esc_url( add_query_arg( "convert-reviews", "start" ) ); ?>"
						   class="button convert-reviews"><?php _e( "Convert reviews", 'yith-woocommerce-advanced-reviews' ); ?></a>

						<div style="display: inline-block; width: 65%; margin-left: 15px;"><span
								class="description"><?php _e( "If this is the first time you install the YITH Advanced Reviews plugin, or if you are using an older version prior to the 1.1.0, first you have to convert the older reviews if you want to use them.", 'yith-woocommerce-advanced-reviews' ); ?></span>
						</div>
					</div>
				</td>
			</tr>
			<?php
		}

		/**
		 * Convert previous reviews into new YITH Advanced review type
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function convert_reviews_callback() {

			//  Check for standard comment matching from previous version...
			$review_converted = $this->import_previous_reviews();
			//  Add a fix for missing post for comments
			$review_converted += $this->import_previous_reviews( "upvotes_count" );

			$response = '';

			if ( $review_converted ) {
				$response = sprintf( __( 'Task completed. %d reviews have been processed and converted.', 'yith-woocommerce-advanced-reviews' ), $review_converted );
			} else {
				$response = __( 'Task completed. No review to convert has been found.', 'yith-woocommerce-advanced-reviews' );
			}

			wp_send_json( array( "value" => $response ) );
		}

		/**
		 * Set a maximum execution time
		 *
		 * @param int $seconds time in seconds
		 */
		private function set_time_limit( $seconds ) {
			$check_safe_mode = ini_get( 'safe_mode' );
			if ( ( ! $check_safe_mode ) || ( 'OFF' == strtoupper( $check_safe_mode ) ) ) {
				@set_time_limit( $seconds );
			}
		}

		/**
		 * Read and convert previous reviews into new advanced reviews using custom post type
		 *
		 * @param string $metakey
		 *
		 * @return int
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function import_previous_reviews( $metakey = "rating" ) {

			global $wpdb;

			$review_converted = 0;

			$query
				= "SELECT *
					FROM {$wpdb->prefix}comments as co left join {$wpdb->prefix}commentmeta as cm
					on co.comment_ID = cm.comment_id
					where ((co.comment_approved = '0') or (co.comment_approved = '1')) and (cm.meta_key = '{$metakey}') and (co.comment_type = '')";

			$results = $wpdb->get_results( $query );

			//  manage parent relationship and update all reviews when import ends
			$parent_review = array();

			foreach ( $results as $comment ) {

				// Check if comment_meta exists for previous reviews and is not still imported
				if ( "1" === get_comment_meta( $comment->comment_ID, YITH_YWAR_META_IMPORTED, true ) ) {
					//  comment still imported, update only author data (Fix for upgrade from 1.1.2 to 1.2.3 then skip it!

					$args    = array(
						'post_type'  => YITH_YWAR_POST_TYPE,
						'meta_query' => array(
							array(
								'key'     => YITH_YWAR_META_COMMENT_ID,
								'value'   => $comment->comment_ID,
								'compare' => '=',
								'type'    => 'numeric',
							),
						),
					);
					$reviews = get_posts( $args );

					if ( isset( $reviews ) && ( count( $reviews ) == 1 ) ) {
						$review = $reviews[0];

						// Update review meta
						update_post_meta( $review->ID, YITH_YWAR_META_REVIEW_USER_ID, $comment->user_id );
						update_post_meta( $review->ID, YITH_YWAR_META_REVIEW_AUTHOR, $comment->comment_author );
						update_post_meta( $review->ID, YITH_YWAR_META_REVIEW_AUTHOR_EMAIL, $comment->comment_author_email );
						update_post_meta( $review->ID, YITH_YWAR_META_REVIEW_AUTHOR_URL, $comment->comment_author_url );
						update_post_meta( $review->ID, YITH_YWAR_META_REVIEW_AUTHOR_IP, $comment->comment_author_IP );
					}

					continue;
				}

				//  Set execution time
				$this->set_time_limit( 30 );

				$val   = get_comment_meta( $comment->comment_ID, "title", true );
				$title = $val ? $val : '';

				$val       = get_comment_meta( $comment->comment_ID, "thumb_ids", true );
				$thumb_ids = $val ? $val : array();

				$val    = get_comment_meta( $comment->comment_ID, "rating", true );
				$rating = $val ? $val : 0;

				//  Import previous settings for "stop reply" from the comment
				$val      = get_comment_meta( $comment->comment_ID, "no_reply", true );
				$no_reply = $val ? $val : 0;

				//  Import previous settings for "votes" from the comment
				$val   = get_comment_meta( $comment->comment_ID, "votes", true );
				$votes = $val ? $val : array();

				//  Extract upvotes and downvotes count
				$votes_grouped = array_count_values( array_values( $votes ) );
				$yes_votes     = isset( $votes_grouped['1'] ) && is_numeric( $votes_grouped['1'] ) ? $votes_grouped['1'] : 0;
				$no_votes      = isset( $votes_grouped['-1'] ) && is_numeric( $votes_grouped['-1'] ) ? $votes_grouped['-1'] : 0;

				// Create post object
				$args = array(
					'post_author'             => $comment->user_id,
					'post_date'               => $comment->comment_date,
					'post_date_gmt'           => $comment->comment_date_gmt,
					'post_content'            => $comment->comment_content,
					'post_title'              => $title,
					'review_user_id'          => $comment->user_id,
					'review_approved'         => $comment->comment_approved,
					'review_product_id'       => $comment->comment_post_ID,
					'review_thumbnails'       => $thumb_ids,
					'review_comment_id'       => $comment->comment_ID,
					'review_rating'           => $rating,
					'review_is_reply_blocked' => $no_reply,
					'review_votes'            => $votes,
					'review_upvotes'          => $yes_votes,
					'review_downvotes'        => $no_votes,
					'review_author'           => $comment->comment_author,
					'review_author_email'     => $comment->comment_author_email,
					'review_author_url'       => $comment->comment_author_url,
					'review_author_IP'        => $comment->comment_author_IP,
				);


				// Insert the post into the database
				$review_id = $this->insert_review( $args );

				//  If current comment have parent, store it and update all relationship when the import ends
				if ( $comment->comment_parent > 0 ) {
					$parent_review[ $review_id ] = $comment->comment_parent;
				}

				//  set current comment as imported
				update_comment_meta( $comment->comment_ID, YITH_YWAR_META_IMPORTED, 1 );
				$review_converted ++;
			}

			//  if some hierarchical comment was found, update the review created
			foreach ( $parent_review as $key => $comment_parent_id ) {

				$args    = array(
					'post_type'  => YITH_YWAR_POST_TYPE,
					'meta_query' => array(
						array(
							'key'     => YITH_YWAR_META_COMMENT_ID,
							'value'   => $comment_parent_id,
							'compare' => '=',
							'type'    => 'numeric',
						),
					),
				);
				$reviews = get_posts( $args );

				if ( isset( $reviews ) && ( count( $reviews ) == 1 ) ) {
					$review = $reviews[0];
					//update the post which id is in $key, setting parent to $review_ids[$value]
					$args = array(
						'ID'          => $key,
						'post_parent' => $review->ID,
					);

					// Update the post into the database
					wp_update_post( $args );
				}
			}


			return $review_converted;
		}

		/**
		 * On plugin init check query vars for commands to convert previous reviews
		 */
		function check_import_actions() {
			if ( isset( $_GET["convert-reviews"] ) ) {

				//  Check for standard comment matching from previous version...
				$converted = $this->import_previous_reviews();
				//  Add a fix for missing post for comments
				$converted += $this->import_previous_reviews( "upvotes_count" );

				wp_redirect( esc_url( remove_query_arg( "convert-reviews" ) ) );
			}
		}
	}
}