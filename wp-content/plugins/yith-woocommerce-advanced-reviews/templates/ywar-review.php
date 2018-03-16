<?php
/**
 * Advanced Review  Template
 *
 * @author        Yithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$review_author_data = YITH_YWAR()->get_review_author( $review->ID );

$author = YITH_YWAR()->get_meta_value_author( $review->ID );
$user   = isset( $author["review_user_id"] ) ? get_userdata( $author["review_user_id"] ) : null;

if ( $user ) {
	$author_name = $user->display_name;
	//  Add class name by author name
	$classes = sprintf( "%s comment-author-%s", $classes, sanitize_html_class( $user->user_nicename, $author_name ) );
} else if ( isset( $author["review_user_id"] ) ) {
	$author_name = $author["review_author"];
} else {
	$author_name = __( 'Anonymous', 'yith-woocommerce-advanced-reviews' );
}
?>

<li itemprop="review" itemscope itemtype="http://schema.org/Review" id="li-comment-<?php echo $review->ID; ?>"
    class="clearfix <?php echo $classes; ?>">

	<div id="comment-<?php echo $review->ID; ?>" class="comment_container clearfix <?php echo $classes; ?>">
		<?php if ( $featured ) : ?>
			<img class="featured-badge" src="<?php echo YITH_YWAR_ASSETS_URL . '/images/featured-review.png'; ?>">
		<?php endif; ?>

		<?php if ( $user && ! $review_author_data['is_modified_user'] ):
			echo get_avatar( $user->ID, apply_filters( 'woocommerce_review_gravatar_size', '60' ), '', apply_filters( 'yith_advanced_reviews_avatar_email', $user->user_email ) );
		else:
			echo get_avatar( $review_author_data["display_email"],
				apply_filters( 'woocommerce_review_gravatar_size', '60' ), '', apply_filters( 'yith_advanced_reviews_avatar_email', $review_author_data["display_email"] ) );
		endif; ?>

		<div class="comment-text clearfix <?php echo $classes; ?>">

			<?php if ( ! $review->post_parent && $rating && get_option( 'woocommerce_enable_review_rating' ) == 'yes' ) : ?>

				<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating trr9"
				     title="<?php echo sprintf( __( 'Rated %d out of 5', 'yith-woocommerce-advanced-reviews' ), $rating ) ?>">
					<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"><strong
							itemprop="ratingValue"><?php echo $rating; ?></strong> <?php _e( 'out of 5', 'yith-woocommerce-advanced-reviews' ); ?></span>
				</div>

			<?php endif; ?>

			<?php if ( $approved == '0' ) : ?>

				<p class="meta">
					<em><?php _e( 'Your comment is waiting for approval', 'yith-woocommerce-advanced-reviews' ); ?></em>
				</p>

			<?php else : ?>

				<p class="meta">
					<strong itemprop="author"><?php echo $review_author_data['display_name']; ?></strong>
					<?php

					if ( $user && get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' ) {
						if ( wc_customer_bought_product( $user->user_email, $user->ID, $product_id ) ) {
							echo '<em class="verified">(' . __( 'verified owner', 'yith-woocommerce-advanced-reviews' ) . ')</em> ';
						}
					}

					?>
					<time itemprop="datePublished"
					      datetime="<?php echo mysql2date( 'c', $review->post_date ); ?>"><?php echo $review_date; ?></time>
				</p>

			<?php endif; ?>

			<?php do_action( 'ywar_woocommerce_review_before_comment_text', $review ); ?>

			<div itemprop="description" class="description ywar-description">
				<p><?php echo apply_filters( 'yith_advanced_reviews_review_content', $review ); ?></p>
			</div>

			<?php do_action( 'ywar_woocommerce_review_after_comment_text', $review ); ?>

		</div>
	</div>
</li>