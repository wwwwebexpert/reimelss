<?php $__env->startSection('content'); ?>
  <?php while(have_posts()): ?> <?php (the_post()); ?>
    <!-- <?php echo $__env->make('partials.page-header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> -->
    <!-- <?php echo $__env->make('partials.content-page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> -->
    <?php ( $single_product_obj = new SingleProduct() ); ?>

	<?php ( $productId = get_the_ID() ); ?>


	<?php echo e($src = wp_get_attachment_image_src( get_post_thumbnail_id($productId), 'full' )); ?>



	<?php ( $product = new WC_product($productId) ); ?>
    <?php ( $attachment_ids = $product->get_gallery_attachment_ids() ); ?>

    




    <section class="single-product-sec">
		<div class="container">
			<div class="product-view">
				<div class="col-sm-4">
					<div class="product-slider">
						<div class="preview-pic tab-content">
							<div class="tab-pane active" id="pic-1"><img src="<?php echo $src[0]; ?>" /></div>
							<?php $__currentLoopData = $attachment_ids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
						        <?php ( $Original_image_url = wp_get_attachment_url( $attachment_id ) ); ?>
						        <div class="tab-pane" id="pic-<?php echo e($loop->iteration + '1'); ?>"><img src="<?php echo wp_get_attachment_url($attachment_id, 'full'); ?>" /></div>
						    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							
							<!-- <div class="tab-pane" id="pic-3"><img src="<?= App\asset_path('images/product1-img3.jpg'); ?>" /></div>
							<div class="tab-pane" id="pic-4"><img src="<?= App\asset_path('images/product1-img4.jpg'); ?>" /></div> -->
						</div>
						<ul class="preview-thumbnail nav nav-tabs">
							<li class="active"><a data-target="#pic-1" data-toggle="tab"><img src="<?php echo $src[0]; ?>" /></a></li>
							<?php $__currentLoopData = $attachment_ids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
						        <?php ( $Original_image_url = wp_get_attachment_url( $attachment_id ) ); ?>
						        <li><a data-target="#pic-<?php echo $loop->iteration + '1'; ?>" data-toggle="tab"><img src="<?php echo wp_get_attachment_url($attachment_id, 'full'); ?>" /></a></li>
						    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<!-- <li class="active"><a data-target="#pic-1" data-toggle="tab"><img src="<?= App\asset_path('images/product1-img.jpg'); ?>" /></a></li>
							<li><a data-target="#pic-2" data-toggle="tab"><img src="<?= App\asset_path('images/product1-img2.jpg'); ?>" /></a></li>
							<li><a data-target="#pic-3" data-toggle="tab"><img src="<?= App\asset_path('images/product1-img3.jpg'); ?>" /></a></li>
							<li><a data-target="#pic-4" data-toggle="tab"><img src="<?= App\asset_path('images/product1-img4.jpg'); ?>" /></a></li> -->
						</ul>		
					</div>
				</div>
				<div class="col-sm-5">
					<div class="product-detail">
						<h3><?php echo get_the_title(); ?></h3>
						<ul class="customer-review-rating">
							<li><?php echo $productstarrating['0']->p_star; ?></li>
							<li><span><a href=""><?php echo $productstarrating['0']->p_count; ?> customer reviews</a></span><!--  | <span><a href="">50 answered question</a></span> --></li>
						</ul>
						<hr>
						<div id="price" class="a-section a-spacing-small">
      						<table class="a-lineitem">
        						<tbody>
        							<?php ($p_currency = $singleproductdata['0']->p_currency ); ?>
        							<?php if($singleproductdata['0']->p_sale == ''): ?>
        							<tr id="priceblock_dealprice_row">
										<td id="priceblock_dealprice_lbl" class="a-text-right">Deal of the Day:</td>
										<td class="right-size-base">
    									   <span id="priceblock_dealprice" class="a-color-price"><?php echo $p_currency.$singleproductdata['0']->p_price; ?></span>
            							</td>
									</tr>
        							<?php else: ?>
        							<tr>
										<td class="a-text-right">List Price:</td>
										<td class="right-size-base">
											<span class="a-text-strike"><?php echo $p_currency.$singleproductdata['0']->p_price; ?></span>
    									</td>
									</tr>
                					<tr id="priceblock_dealprice_row">
										<td id="priceblock_dealprice_lbl" class="a-text-right">Deal of the Day:</td>
										<td class="right-size-base">
    									   <span id="priceblock_dealprice" class="a-color-price"><?php echo $p_currency.$singleproductdata['0']->p_sale; ?></span>
            							</td>
									</tr>
									<tr id="dealprice_savings">
										<td class="a-text-right">You Save:</td>
										<td class="right-size-base red-text"><?php echo $p_currency.$singleproductdata['0']->p_discount; ?> (<?php echo $singleproductdata['0']->p_discount_per; ?>%)</td>
									</tr>
        							<?php endif; ?>
								</tbody>
							</table>
      					</div>
						<hr>
						<div class="sale-details">
							<ul class="stock-details">
								<li>
									<h3><?php echo $addtocartform['0']->p_stockstatus; ?></h3>
									<div class="progress">
										<div class="progress-bar" role="progressbar" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100" style="width:32%"></div>
								  	</div>
									<p>32% Claimed</p>
								</li>
								<li>
									<p class="sale-txt">Sales Ends in: <span id="demo"></span></p>
								</li>
							</ul>
							<?php echo e(the_excerpt()); ?>

							<!-- <ul class="sale-details-info">
								<li>Using Cataclean can lower your total hydrocarbon emissions by up to 50 percent.</li>
								<li>Reduces carbon build-up in catalytic converter, oxygen sensors, fuel injectors and cylinder heads which results in improved fuel efficiency.</li>
								<li>Improves overall vehicle performance-including driveability issues such as power reduction, hesitation, rough idle, hard starts and lost fuel economy.</li>
								<li>Safe for gasoline, diesel, hybrid and flex-fuel vehicles; does not alter fuel</li>
								<li>Not for use in 2-stroke or oil / gas mix engines.</li>
							</ul> -->
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="add-cart-sec">
						<div class="cartmessages">
						</div>
						<?php ( $p_quantity = $addtocartform['0']->p_quantity ); ?>
						<?php if( $p_quantity !='' ): ?>
							<p class="a-dropdown-container">
								<label for="quantity" class="a-native-dropdown">Qty:</label>
								<select name="quantity" autocomplete="off" id="quantity" tabindex="-1" class="a-native-dropdown">
									<?php for($i = 1; $i <= $p_quantity; $i++): ?>
	                    				<option data-id="<?php echo e($productId); ?>" value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
									<?php endfor; ?>
	                            </select>
							</p>
							<button type="button" class="add-cart-btn addcartBtn">Add to Cart</button>
							<div class="ship-to">
								<p class="a-text-bold">Ship to:</p>
								<div class="shipping-add">
									<span class="a-declarative">
										<a href="" class="a-declarative">
											<span class="a-color-base lux-location-label">
												Select a shipping address:
											</span>
											<i class="fas fa-caret-down"></i>
										</a>
									</span>
								</div>
							</div>
							<hr>
							<div class="return-policy">
								<p class="a-text-bold">Return Policy:</p>
								<p>All items qualify for free returns within 30 days of receipt. A free prepaid return shipping label will be provided.</p>
							</div>
							<hr>
							<div class="wishlistbuton">
								<?php echo do_shortcode('[ti_wishlists_addtowishlist loop=yes]'); ?>

							</div>
							<?php else: ?>
	                        	<?php echo $addtocartform['0']->p_stockstatus; ?>

	                        <?php endif; ?>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<hr>
			<div class="similar-products">
				
				<?php ( $relatedProducts = $single_product_obj->relatedProducts() ); ?>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<h4 class="red-text">Similar products related to this item</h4>
				  <div class="carousel carousel-showmanymoveone slide" id="itemslider">
					<div class="carousel-inner">
						<?php ( $count = '1' ); ?>
						<?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r_p_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php if($count%6 == 1): ?>  
						        <div class="item <?php if($loop->iteration == 1): ?> <?php echo e('active'); ?> <?php endif; ?>">
						    <?php endif; ?>
								<div class="item <?php if($loop->iteration == 1): ?> <?php echo e('active'); ?> <?php endif; ?>">
									<div class="col-xs-12 col-sm-6 col-md-2">
									  <a href="<?php echo e($r_p_data->p_link); ?>">
										<img src="<?php echo e($r_p_data->p_img); ?>" class="img-responsive center-block">
										<p class="product-name"><?php echo e($r_p_data->p_title); ?></p>
										<!-- <img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive"> -->
										<p class="price"><?php echo $r_p_data->p_price; ?></p>
									  </a>
									</div>
								</div>
							<?php if($count%6 == 0): ?>
						        </div>
						    <?php endif; ?>
						<?php ( $count++ ); ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php if($count%6 != 1): ?>
							</div>
						<?php endif; ?>

					</div>

					<div id="slider-control">
					<a class="left carousel-control" href="#itemslider" data-slide="prev"><img src="<?= App\asset_path('images/left-arrow.png'); ?>" alt="Left" class="img-responsive"></a>
					<a class="right carousel-control" href="#itemslider" data-slide="next"><img src="<?= App\asset_path('images/right-arrow.png'); ?>" alt="Right" class="img-responsive"></a>
				  </div>
				  </div>
				
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="product-description">
				<h4 class="red-text">Product description</h4>
				<?php echo e(the_content()); ?>

				<!-- <ul class="desc-list">
					<li>Brand: Mr. Gasket</li>
					<li>Item Weight: 14.4 ounces</li>
					<li>Package Dimensions: 9.5 x 2.7 x 2.5 inches</li>
					<li>Item model number: 120007</li>
					<li>Manufacturer Part Number: 120007</li>
					<li>OEM Part Number: 120007</li>
					<li>Folding: No</li>
					<li>Vehicle Service Type: all-terrain-vehicles, utility-vehicles, street-sport-motorcycles, off-road-motorcycles, street-cruiser-motorcycles, street-touring-motorcycles, street-motor-scooters, snowmobiles, marine-personal-craft</li>
					<li>Shipping Weight: 14.4 ounces</li>
					<li>International Shipping: This item is not eligible for international shipping. Learn More</li>
					<li>Date First Available: July 7, 2004</li>
				</ul>
				<p class="a-text-bold">Description</p>
				<p>Size:16.0 Ounce Mr. Gasket Cataclean is a fuel and exhaust system cleaner that reduces carbon build-up and cleans your vehicle’s catalytic converter, oxygen sensors, fuel injectors, and cylinder heads. Cataclean does not alter fuel composition and is safe for gasoline, diesel and hybrid engines. Testing at an independent emissions testing facility showed using Cataclean can lower your total hydrocarbon emissions by up to 50 percent (performance results are dependent on driver habits and vehicle condition). Use Cataclean to fix drivability issues such as power reduction, hesitation and hard starts, as a pre-treatment before an emissions test or to extend the life of your vehicle engine. Using Cataclean may help to avoid the replacement of costly oxygen sensors or your vehicle’s catalytic converter. If you have to replace your catalytic converter, use Cataclean with the installation to extend its life. Cataclean is easy to use-simply pour in your fuel tank. Cataclean is 50 state legal and complies with VOC (including California) and OTC regulations, as well as Federal low sulphur content requirements for use in diesel motor vehicles and non-road engines. For optimum performance and protection, use Cataclean four times a year. Made in the USA.</p> -->
				
			</div>
			<hr>
			<div class="customer-reviews">
				<p><?php echo $productstarrating['0']->p_star; ?> <span class="tolal-sale"><?php echo $productstarrating['0']->p_count; ?></span></p>

				<!-- <p><img src="<?= App\asset_path('images/4str.png'); ?>" alt="" class="img-responsive star-rating"> <span class="tolal-sale">23,414</span></p> -->

				<div class="tooltip-col product-rating"><?php echo $productstarrating['0']->p_average; ?> out of 5 stars <i class="fas fa-caret-down"></i>
				 	<span class="tooltiptext4">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</span>
				</div>
				<div class="row">
					<div class="col-sm-4 averageratingsection">
				<!-- <table id="review-bars">
					<tbody>
						<tr>
							<td>5 star</td>
							<td>
								<div class="progress">
									<div class="progress-bar" role="progressbar" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100" style="width:64%"></div>
								</div>
							</td>
							<td>64%</td>
						</tr>
						<tr>
							<td>4 star</td>
							<td>
								<div class="progress">
									<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width:45%"></div>
								</div>
							</td>
							<td>45%</td>
						</tr>
						<tr>
							<td>3 star</td>
							<td>
								<div class="progress">
									<div class="progress-bar" role="progressbar" aria-valuenow="6" aria-valuemin="0" aria-valuemax="100" style="width:6%"></div>
								</div>
							</td>
							<td>6%</td>
						</tr>
						<tr>
							<td>2 star</td>
							<td>
								<div class="progress">
									<div class="progress-bar" role="progressbar" aria-valuenow="19" aria-valuemin="0" aria-valuemax="100" style="width:19%"></div>
								</div>
							</td>
							<td>19%</td>
						</tr>
						<tr>
							<td>1 star</td>
							<td>
								<div class="progress">
									<div class="progress-bar" role="progressbar" aria-valuenow="6" aria-valuemin="0" aria-valuemax="100" style="width:6%"></div>
								</div>
							</td>
							<td>6%</td>
						</tr>
					</tbody>
				</table> -->
					</div>
					<!-- <div class="col-sm-8">
						<div class="write-review">
							<p>Share your thoughts with other customers</p>
							<button type="button" class="write-review-btn">Write a Customer review</button>


						</div>
					</div> -->
				</div>
				<div class="top-reviews">
					<?php echo do_shortcode('[cusrev_reviews]'); ?>

					<!-- <h4>Top Customer Reviews</h4> -->	
					<!-- <div class="cust-review">
						<div class="a-row a-spacing-mini">
							<a href="" class="a-profile">
								<div aria-hidden="true" class="a-profile-avatar-wrapper">
									<div class="a-profile-avatar"><img src="<?= App\asset_path('images/customer.png'); ?>" class=""></div>
								</div>
								<div class="a-profile-content">
									<span class="a-profile-name">Nancy Thomas</span>
								</div>
							</a>
						</div>
						<div class="clearfix"></div>
						<div class="rating-line">
							<p class="rating"><img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive"> <span>Awesome Buy!</span></p>
							<p class="red-text">Verified Customer</p>
							<p class="customer-remark">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
						</div>
						
					</div> -->
					<!--<div class="cust-review">
						<div class="a-row a-spacing-mini">
							<a href="" class="a-profile">
								<div aria-hidden="true" class="a-profile-avatar-wrapper">
									<div class="a-profile-avatar"><img src="<?= App\asset_path('images/customer1.png'); ?>" class=""></div>
								</div>
								<div class="a-profile-content">
									<span class="a-profile-name">Aliana</span>
								</div>
							</a>
						</div>
						<div class="clearfix"></div>
						<div class="rating-line">
							<p class="rating"><img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive"> <span>Best Price</span></p>
							<p class="red-text">Verified Customer</p>
							<p class="customer-remark">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
						</div>
						
					</div> -->
				</div>
				
			</div>
		
	</div>  
</section>

  <?php endwhile; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>