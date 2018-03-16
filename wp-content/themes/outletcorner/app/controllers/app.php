<?php

namespace App;

use Sober\Controller\Controller;

class App extends Controller
{
    public function siteName()
    {
        return get_bloginfo('name');
    }

    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'sage');
        }
        return get_the_title();
    }

    public function socialshare(){
        $data = [];
        $footerShare = get_field('field_5aaaaf5f61954','options');
        foreach ($footerShare as $s_share) {
            $social_share = (object) array(
                's_icon' => $s_share['upload_icon'],
                's_link' => $s_share['add_link'],
            );
            array_push($data, $social_share);
        }
        return $data;
    }

    //function to show latest product
    public function latestproduct()
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
        $latest_product = get_posts([
            'posts_per_page'   => '10',
            'post_type'        => 'product'
        ]);
        foreach ($latest_product as $l_p_data) {
            $pimg = wp_get_attachment_image_src( get_post_thumbnail_id( $l_p_data->ID ), 'single-post-thumbnail' );
            $plink = get_the_permalink( $l_p_data->ID );
            $all_l_p_data = (object) array(
                'p_img' => $pimg[0],
                'p_link' => $plink,
            );

            array_push($data, $all_l_p_data);
        }
        return $data;
    }

    //function for copyright section
    public function copyright(){
        $copyrighttext = get_field('field_5aaab7260899c','options');
        return $copyrighttext;
    }

    //function for Terms And Conditions section
    public function termcondition(){
        $term_condition = get_field('field_5aaab965271a8','options');
        return $term_condition;
    }
    //function to show product categories
    public function productcategories(){
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
    }

}
