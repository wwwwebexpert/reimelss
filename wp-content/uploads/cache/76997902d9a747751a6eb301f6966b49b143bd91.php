<?php $__env->startSection('content'); ?>
  <?php while(have_posts()): ?> <?php (the_post()); ?>
    <?php echo $__env->make('partials.page-header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('partials.content-page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <section>
		<div class="container">
			<!--banner section start here-->
			
			<!--//banner section end here-->
		  <div class="top-banner">
			<div id="transition-timer-carousel" class="carousel slide transition-timer-carousel" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<?php $__currentLoopData = $slider; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s_img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li data-target="#transition-timer-carousel" data-slide-to="<?php echo e($index); ?>" class="<?php if($index == '0'): ?> <?php echo e('active'); ?> <?php endif; ?>;"></li>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</ol>

			<!-- Wrapper for slides -->
			<div class="carousel-inner">
				<?php $__currentLoopData = $slider; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s_img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				    <div class="item <?php if($index == '0'): ?> <?php echo e('active'); ?> <?php endif; ?>;">
	                    <img src="<?php echo e($s_img->slides); ?>" alt="" class="img-responsive"/>
	                </div>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

			<!-- Controls -->
			<a class="left carousel-control" href="#transition-timer-carousel" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left"></span>
			</a>
			<a class="right carousel-control" href="#transition-timer-carousel" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
            
            <!-- Timer "progress bar" -->
            <hr class="transition-timer-carousel-progress-bar animate" />
			</div>		
		  </div>
		  <div class="row">
				<div class="col-sm-9">
			    	<div class="featured-products">
						<div class="category-carousel3">
							<h4>Featured Products <span><a href="">See More</a></span></h4>
								<div class="row">
									<div class="col-md-12">
									  <div class="carousel carousel-showmanymove slide" id="carouselABC">
										<div class="carousel-inner">
										  <div class="item active">
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block" alt="">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img6.png'); ?>" class="img-responsive center-block" alt="">
													<p class="product-name">Battery Tender 081-0148-12 12.5′ Extension Cable</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>	
											 </div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img1.png'); ?>" class="img-responsive center-block" alt="">
													<p class="product-name">A Perfect Murder</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>          
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img4.png'); ?>" class="img-responsive center-block" alt="">
													<p class="product-name">ALEX Toys Craft Eco Crafts Scrapbook</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img5.png'); ?>" class="img-responsive center-block" alt="">
													<p class="product-name">AmazonBasics Shower Curtain with Hooks</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img2.png'); ?>" class="img-responsive center-block" alt="">
													<p class="product-name">A-Zoom Precision Snap Caps 12 Gauge (2 Pack)</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img7.png'); ?>" class="img-responsive center-block" alt="">
													<p class="product-name">Bosch 3330 Premium FILTECH Oil Filter</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img8.png'); ?>" class="img-responsive center-block" alt="">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										</div>
										<a class="left carousel-control" href="#carouselABC" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
										<a class="right carousel-control" href="#carouselABC" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
									  </div>
									</div>
								  </div>
									
								</div>	
							</div>
							<hr>
							<div class="deal-day">
								<img src="<?= App\asset_path('images/deal-day.jpg'); ?>" alt="" class="img-responsive">
								<div id="countdown">
								  <div id="tiles"></div>
								  <div class="labels">
									<li>Days</li>
									<li>Hours</li>
									<li>Mins</li>
									<li>Secs</li>
								  </div>
								</div>
								<div class="shop-now-link">
									<span><a href="" class="hvr-icon-forward">Shop Now  </a></span>
								</div>
							</div>
							<hr>
							<div class="more-item-consider">
								<div class="category-carousel3">
								<h4>More items to consider <span><a href="">See More</a></span></h4>
									<div class="row">
									<div class="col-md-12">
									  <div class="carousel carousel-showmanymove slide" id="carouselXYZ">
										<div class="carousel-inner">
										  <div class="item active">
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img9.png'); ?>" class="img-responsive center-block" alt="">
												</a>
												
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img10.png'); ?>" class="img-responsive center-block" alt="">
												</a>	
											 </div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img11.png'); ?>" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>          
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img12.png'); ?>" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img13.png'); ?>" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img14.png'); ?>" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img15.png'); ?>" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img8.png'); ?>" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>
										</div>
										<a class="left carousel-control" href="#carouselXYZ" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
										<a class="right carousel-control" href="#carouselXYZ" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
									  </div>
									</div>
								  </div>
								</div>	
							</div>
							<hr>
							<div class="related-items">
								<div class="category-carousel3">
									<h4>Related to items you've viewed <span><a href="">See More</a></span></h4>
									<div class="row">
										<div class="col-md-12">
											 <div class="carousel carousel-showmanymove slide" id="carousel123">
												<div class="carousel-inner">
												  <div class="item active">
													<div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img16.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img17.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img18.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>          
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img19.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img5.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img6.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img7.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img8.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												</div>
												<a class="left carousel-control" href="#carousel123" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
												<a class="right carousel-control" href="#carousel123" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
											  </div>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="shop-now-sec">
								<div class="row">
									<div class="col-sm-6">
										<div class="shop-img-left">
											<a href=""><img src="<?= App\asset_path('images/shop-now-img1.jpg'); ?>" alt="" class="img-responsive"></a>
										</div>	
									</div>
									<div class="col-sm-6">
										<div class="shop-img-right">
											<a href=""><img src="<?= App\asset_path('images/shop-now-img2.jpg'); ?>" alt="" class="img-responsive"></a>
										</div>	
									</div>
								</div>
							</div>
							<hr>
							<div class="browsing-history">
								<div class="category-carousel3">
									<h4>Inspired by your browsing history <span><a href="">See More</a></span></h4>
									<div class="row">
										<div class="col-md-12">
											 <div class="carousel carousel-showmanymove slide" id="carousel456">
												<div class="carousel-inner">
												  <div class="item active">
													<div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img17.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img10.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img13.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>          
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img9.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img15.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img6.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img7.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="<?= App\asset_path('images/img8.png'); ?>" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												</div>
												<a class="left carousel-control" href="#carousel456" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
												<a class="right carousel-control" href="#carousel456" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
											  </div>
										</div>
									</div>
								</div>
							</div>
							
					
			    </div>
			  	<div class="col-sm-3">
			    	<div class="right-side-adv">
						<div class="advs">
							<a href="#"><img src="<?= App\asset_path('images/adv1.jpg'); ?>" alt="" class="img-responsive"></a>
						</div>
						<hr>
						<div class="advs">
							<a href="#"><img src="<?= App\asset_path('images/adv2.jpg'); ?>" alt="" class="img-responsive"></a>
						</div>
						<hr>
						<div class="advs">
							<a href="#"><img src="<?= App\asset_path('images/adv3.jpg'); ?>" alt="" class="img-responsive"></a>
						</div>
						<hr>
						<div class="advs">
							<a href="#"><img src="<?= App\asset_path('images/adv4.jpg'); ?>" alt="" class="img-responsive"></a>
						</div>
					</div>	
			    </div>
			</div>
			<hr>
			 <div class="recommendations">
			  	 <div class="col-xs-12 col-sm-12 col-md-12">
						<h4 class="text-center">We Have Recommendations for You</h4>
					  <div class="carousel carousel-showmanymoveone slide" id="recommendations-slider">
						<div class="carousel-inner">
						  <div class="item active">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="<?= App\asset_path('images/img1.png'); ?>" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="<?= App\asset_path('images/img2.png'); ?>" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="<?= App\asset_path('images/img4.png'); ?>" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="<?= App\asset_path('images/img5.png'); ?>" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="<?= App\asset_path('images/img6.png'); ?>" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						</div>

						<div id="slider-control">
						<a class="left carousel-control" href="#recommendations-slider" data-slide="prev"><img src="<?= App\asset_path('images/left-arrow.png'); ?>" alt="Left" class="img-responsive"></a>
						<a class="right carousel-control" href="#recommendations-slider" data-slide="next"><img src="<?= App\asset_path('images/right-arrow.png'); ?>" alt="Right" class="img-responsive"></a>
					  </div>
			 	   </div>
				</div>
			   </div>
			   <div class="bottom-block">
				   <div class="row">
						<div class="col-sm-4">
							<div class="block">
								<img src="<?= App\asset_path('images/free-delivery.jpg'); ?>" alt="" class="img-responsive">	
							</div>
						</div>
						<div class="col-sm-4">
							<div class="block">
								<img src="<?= App\asset_path('images/money-back.jpg'); ?>" alt="" class="img-responsive">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="block">
								<img src="<?= App\asset_path('images/supports.jpg'); ?>" alt="" class="img-responsive">
							</div>
						</div>
				   </div>
			   </div>
			
			
			
			
			
		</div>
	</section>











  <?php endwhile; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>