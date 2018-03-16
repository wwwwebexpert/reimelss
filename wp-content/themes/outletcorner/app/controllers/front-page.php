<?php

namespace App;

use Sober\Controller\Controller;

class FrontPage extends Controller
{
	//function to show slider
	public function slider()
	{
	    $data = [];
		$frontpageslider = get_field('field_5aa194a489908','options');
	    foreach ($frontpageslider as $s_img) {
	        $all_slides = (object) array(
	            'slides' => $s_img['upload_slider_image'],
	        );
	        array_push($data, $all_slides);
	    }
	    return $data;
	}
	//function to show featured product
	public function featuredproduct()
	{
		if ( ! defined( 'ABSPATH' ) ){
			exit;
		}
		global $wpdb;
		global $post;
		global $woocommerce;
		global $product;
		global $woocommerce_loop;
		$data = [];
		$featured_product = get_posts([
	        'posts_per_page'   => -1,
		    'post_type'        => 'product',
		    'meta_key'         => 'featured_product',
		    'meta_value'       => '1',
	    ]);
	    foreach ($featured_product as $f_p_data) {
			$currency = get_woocommerce_currency_symbol();
			$price = get_post_meta( $f_p_data->ID, '_regular_price', true);
			$sale = get_post_meta( $f_p_data->ID, '_sale_price', true);
			$pimg = wp_get_attachment_image_src( get_post_thumbnail_id( $f_p_data->ID ), 'single-post-thumbnail' );
			$plink = get_the_permalink( $f_p_data->ID );

			$product = wc_get_product( $f_p_data->ID );
			$rating_count = $product->get_rating_count();
			$review_count = $product->get_review_count();
			$average      = $product->get_average_rating();
			$starhtml = wc_get_rating_html($average, $rating_count);
			if ( $rating_count >= 0 ){
				//if ( comments_open() ){
					$starcount = $review_count;
				//}
			}

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ){
				return;
			}
			/*echo '<pre>';
			echo $starhtml;
			echo '</pre>';*/

			if($sale) {
				$price = '<del>'.$currency.$price.'</del>'.' '.$currency.$sale;
			}else{
				$price = $currency.$price;
			}

	        $all_f_p_data = (object) array(
	            'p_id' => $f_p_data->ID,
	            'p_title' => $f_p_data->post_title,
	            'p_price' => $price,
	            'p_img' => $pimg[0],
	            'p_link' => $plink,
	            'p_star' => $starhtml,
	            'p_average' => $average,
	        );

	        array_push($data, $all_f_p_data);
	    }
	    return $data;
	}
	//function to show favourite categories sidebar
	/*public function productcategories(){
		global $wpdb;
		global $post;
		global $woocommerce;
		global $product;
		global $woocommerce_loop;
		$data = [];
		$terms = get_terms( array(
		    'taxonomy' => 'product_cat',
		    'hide_empty' => false,
		    'exclude' => array( 16 ),
		) );
		foreach ($terms as $term) {
			$thumb_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
			$term_img = wp_get_attachment_url(  $thumb_id );
			$termlink = get_term_link($term->term_id);
			$p_cat = (object) array(
				'p_cat_img' => $term_img,
				'p_cat_link' => $termlink,
			);
			array_push($data, $p_cat);
		}
		return $data;
	}*/


}
