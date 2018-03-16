<?php
namespace App;
use Sober\Controller\Controller;
use WP_Query;

class SingleProduct extends Controller
{
	//function to show related products
	public function relatedProducts()
	{
		/**
		 * Related Products
		*/
		$data = [];
		if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

		global $wpdb;
		global $post;
		global $woocommerce;
		global $product;
		global $woocommerce_loop;

		$product_obj = get_page_by_path( $product, OBJECT, 'product' );

		$related = wc_get_related_products( $product_obj->ID, '11', $product_obj->ID );

		 $args = apply_filters( 'woocommerce_related_products_args', array(
		    'post_type'            => 'product',
		    'ignore_sticky_posts'  => 1,
		    'posts_per_page'       => 11,
		    'post__in'             => $related,
		    'post__not_in'         => array( $product_obj->ID )
		 ) );

		$products = new WP_Query( $args );

		foreach ($products->posts as $r_product) {
			//echo $r_product->ID;
			$currency = get_woocommerce_currency_symbol();
			$price = get_post_meta( $r_product->ID, '_regular_price', true);
			$sale = get_post_meta( $r_product->ID, '_sale_price', true);
			$pimg = wp_get_attachment_image_src( get_post_thumbnail_id( $r_product->ID ), 'single-post-thumbnail' );
			$plink = get_the_permalink( $r_product->ID );
			
			if($sale) {
				$price = '<del>'.$currency.$price.'</del>'.' '.$currency.$sale;
			}else{
				$price = $currency.$price;
			}

	        $all_r_p_data = (object) array(
	            'p_id' => $r_product->ID,
	            'p_title' => $r_product->post_title,
	            'p_price' => $price,
	            'p_img' => $pimg[0],
	            'p_link' => $plink,
	        );
	        array_push($data, $all_r_p_data);
		}
		return $data;

	}
	//function to show single product data
	public function singleproductdata(){
		$data = [];
		if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

		global $wpdb;
		global $post;
		global $woocommerce;
		global $product;
		global $woocommerce_loop;

		$product_obj = get_page_by_path( $product, OBJECT, 'product' );

		$currency = get_woocommerce_currency_symbol();
		$price = get_post_meta( $product_obj->ID, '_regular_price', true);
		$sale = get_post_meta( $product_obj->ID, '_sale_price', true);
		$totaldiscount = $price - $sale;
		$totaldiscount_per = ($totaldiscount * 100) / $price;

		$all_s_p_data = (object) array(
            'p_id' => $product_obj->ID,
            'p_sale' => $sale,
            'p_price' => $price,
            'p_discount' => $totaldiscount,
            'p_discount_per' => round($totaldiscount_per,2),
            'p_currency' => $currency,
        );
        array_push($data, $all_s_p_data);
        return $data;
	}
	//function to show add to cart form
	public function addtocartform(){
		$data = [];
		if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
		global $wpdb;
		global $post;
		global $woocommerce;
		global $product;
		global $woocommerce_loop;

		$product_obj = get_page_by_path( $product, OBJECT, 'product' );
		$stockStatus = get_post_meta( $product_obj->ID, '_stock_status', true );

		if($stockStatus == 'instock'){
			$stockStatusname = 'In stock';
		}else if($stockStatus == 'outofstock'){
			$stockStatusname = 'Out of stock';
		}

		$stockStatus = get_post_meta( $product_obj->ID, '_stock_status', true );

		$totalQuantity = get_post_meta( $product_obj->ID, '_stock', true );

		if( $stockStatus == 'instock' && $totalQuantity !='' ){
			$totalQuantity = $totalQuantity;
		}else if( $stockStatus == 'instock' && $totalQuantity =='' ){
			$totalQuantity = '20';
		}else{
			$totalQuantity = '';
		}

		$cart_form = (object) array(
            'p_quantity' => $totalQuantity,
            'p_stockstatus' => $stockStatusname,
        );
        array_push($data, $cart_form);

		return $data;


	}
	//function to add product star rating section
	public function productstarrating(){
		$data = [];
		if ( ! defined( 'ABSPATH' ) ){
			exit;
		}
		global $wpdb;
		global $post;
		global $woocommerce;
		global $product;
		global $woocommerce_loop;

		if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ){
			return;
		}
		$product_obj = get_page_by_path( $product, OBJECT, 'product' );
		$product = wc_get_product( $product_obj->ID );

		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();

		$starhtml = wc_get_rating_html($average, $rating_count);
		if ( $rating_count >= 0 ){
			//if ( comments_open() ){
				$starcount = $review_count;
			//}
		}
		$p_rating = (object) array(
            'p_star' => $starhtml,
            'p_count' => $starcount,
            'p_average' => $average,
        );
        array_push($data, $p_rating);

		return $data;

	}





}