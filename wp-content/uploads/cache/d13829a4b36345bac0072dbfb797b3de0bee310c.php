<?php $__env->startSection('content'); ?>
  <?php while(have_posts()): ?> <?php (the_post()); ?>
    <?php echo $__env->make('partials.page-header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('partials.content-page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<section>
		<div class="shop-by-category">
			<div class="container">
				<h2>Categories at Outlet Corner</h2>
				<p>Shop by Category</p>
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
					  <div class="carousel carousel-showmanymoveone slide" id="itemslider">
						<div class="carousel-inner">

						  <div class="item active">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/CABEL.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Cable</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#"><img src="<?= App\asset_path('images/books.png'); ?>" class="img-responsive center-block"></a>
							  <h4 class="text-center">Books</h4>
							</div>
						  </div>

						</div>

						<div id="slider-control">
						<a class="left carousel-control" href="#itemslider" data-slide="prev"><img src="<?= App\asset_path('images/left-arrow.png'); ?>" alt="Left" class="img-responsive"></a>
						<a class="right carousel-control" href="#itemslider" data-slide="next"><img src="<?= App\asset_path('images/right-arrow.png'); ?>" alt="Right" class="img-responsive"></a>
					  </div>
					  </div>
					</div>
				  </div>
			</div>
	  	</div>
		<div class="categories-bottom-sec">
			<div class="container">
				<div class="row">
					<div class="col-sm-3">
						<div class="cat-left-menu desktop-menu">
							<aside id="popular-cat">
								<p>Popular Categories:</p>
								<div class="list">
									<ul>
										<li><a href="">Lorem ipsum</a></li>
										<li><a href="">Dolor consectetuer</a></li>
										<li><a href="">Adipiscing elit</a></li>
										<li><a href="">Aenean commodo</a></li>
										<li><a href="">Eget dolor</a></li>
										<li><a href="">Consectetuer</a></li>
										<li><a href="">Ligula adipiscing</a></li>
									</ul>
								</div>
							</aside>
							<aside id="cat2">
								<p>Lorem Categories:</p>
								<div class="list">
									<ul>
										<li><a href="">Lorem ipsum</a></li>
										<li><a href="">Dolor consectetuer</a></li>
										<li><a href="">Adipiscing elit</a></li>
										<li><a href="">Aenean commodo</a></li>
										<li><a href="">Eget dolor</a></li>
										<li><a href="">Consectetuer</a></li>
										<li><a href="">Ligula adipiscing</a></li>
									</ul>
								</div>
							</aside>
							<aside id="cat3">
								<p>Lorem Categories:</p>
								<div class="list">
									<ul>
										<li><a href="">Lorem ipsum</a></li>
										<li><a href="">Dolor consectetuer</a></li>
										<li><a href="">Adipiscing elit</a></li>
										<li><a href="">Aenean commodo</a></li>
										<li><a href="">Eget dolor</a></li>
										<li><a href="">Consectetuer</a></li>
										<li><a href="">Ligula adipiscing</a></li>
									</ul>
								</div>
							</aside>
							<div class="refine-sec mar-b-20">
								<h4>Refine by:</h4>
								<p class="mar-bot-10">Eligible for Free Shipping</p>
								<form action="">
									<input type="checkbox" name="free-shipping" value="Shipping">Free Shipping 
								</form>
							</div>
							<div class="avg-customer-review mar-b-20">
								<p class="mar-bot-10">Avg Customer Review:</p>
								<form action="#">
									<div class="row stock-images">
									  <ul>
										  <li>
											  <input id="test0" name="same-group-name" type="radio" />
											  <label for="test0">
										  			<div class="image" style="background-image: url(../images/4str.png); background-repeat:no-repeat;"><span>& Up</span></div>
											  </label>
										  </li>
										  <li>
											  <input id="test1" name="same-group-name" type="radio" />
											  <label for="test1">
											  		<div class="image" style="background-image: url(../images/3-star.png); background-repeat:no-repeat;"><span>& Up</span></div>
											  </label>
										  </li>
										  <li>
											  <input id="test2" name="same-group-name" type="radio" />
											  <label for="test2">
										  			<div class="image" style="background-image: url(../images/2str.png); background-repeat:no-repeat;"><span>& Up</span></div>
											  </label>
										  </li>
										  <li>
											  <input id="test3" name="same-group-name" type="radio" />
											  <label for="test3">
											  		<div class="image" style="background-image: url(../images/1str.png); background-repeat:no-repeat;"><span>& Up</span></div>
											  </label>
										  </li>
										</ul>
									  </div>
									 
									
								  </form>	
							</div>
							<div class="available  mar-b-20">
								<p class="mar-bot-10">Availability:</p>
								<form action="">
									<input type="checkbox" name="free-shipping" value="Shipping">Include Out of Stock
								</form>
							</div>
							
						</div>
						<div class="cat-left-menu mob-menu">
							<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
								<aside id="popular-cat">
								<p>Popular Categories:</p>
								<div class="list">
									<ul>
										<li><a href="">Lorem ipsum</a></li>
										<li><a href="">Dolor consectetuer</a></li>
										<li><a href="">Adipiscing elit</a></li>
										<li><a href="">Aenean commodo</a></li>
										<li><a href="">Eget dolor</a></li>
										<li><a href="">Consectetuer</a></li>
										<li><a href="">Ligula adipiscing</a></li>
									</ul>
								</div>
							</aside>
								<aside id="cat2">
								<p>Lorem Categories:</p>
								<div class="list">
									<ul>
										<li><a href="">Lorem ipsum</a></li>
										<li><a href="">Dolor consectetuer</a></li>
										<li><a href="">Adipiscing elit</a></li>
										<li><a href="">Aenean commodo</a></li>
										<li><a href="">Eget dolor</a></li>
										<li><a href="">Consectetuer</a></li>
										<li><a href="">Ligula adipiscing</a></li>
									</ul>
								</div>
							</aside>
								<aside id="cat3">
								<p>Lorem Categories:</p>
								<div class="list">
									<ul>
										<li><a href="">Lorem ipsum</a></li>
										<li><a href="">Dolor consectetuer</a></li>
										<li><a href="">Adipiscing elit</a></li>
										<li><a href="">Aenean commodo</a></li>
										<li><a href="">Eget dolor</a></li>
										<li><a href="">Consectetuer</a></li>
										<li><a href="">Ligula adipiscing</a></li>
									</ul>
								</div>
							</aside>
								<div class="refine-sec mar-b-20">
								<h4>Refine by:</h4>
								<p class="mar-bot-10">Eligible for Free Shipping</p>
								<form action="">
									<input type="checkbox" name="free-shipping" value="Shipping">Free Shipping 
								</form>
							</div>
							<div class="avg-customer-review mar-b-20">
								<p class="mar-bot-10">Avg Customer Review:</p>
								<form action="#">
									<div class="row stock-images">
									  <ul>
										  <li>
											  <input id="test0" name="same-group-name" type="radio" />
											  <label for="test0">
										  			<div class="image" style="background-image: url(../images/4str.png); background-repeat:no-repeat;"><span>& Up</span></div>
											  </label>
										  </li>
										  <li>
											  <input id="test1" name="same-group-name" type="radio" />
											  <label for="test1">
											  		<div class="image" style="background-image: url(../images/3-star.png); background-repeat:no-repeat;"><span>& Up</span></div>
											  </label>
										  </li>
										  <li>
											  <input id="test2" name="same-group-name" type="radio" />
											  <label for="test2">
										  			<div class="image" style="background-image: url(../images/2str.png); background-repeat:no-repeat;"><span>& Up</span></div>
											  </label>
										  </li>
										  <li>
											  <input id="test3" name="same-group-name" type="radio" />
											  <label for="test3">
											  		<div class="image" style="background-image: url(../images/1str.png); background-repeat:no-repeat;"><span>& Up</span></div>
											  </label>
										  </li>
										</ul>
									  </div>
									 
									
								  </form>	
							</div>
								<div class="available  mar-b-20">
								<p class="mar-bot-10">Availability:</p>
								<form action="">
									<input type="checkbox" name="free-shipping" value="Shipping">Include Out of Stock
								</form>
							</div>
							</nav>
						</div>
						<div class="mob-side-menus">
							<h4>Categories</h4>
							<button id="showLeft"><i class="fas fa-bars"></i></button>
							
						</div>
					</div>
					<div class="col-sm-9">
						<div class="cat-right-sec">
							<div class="row">
								<div class="category-box">
									<div class="col-sm-3 pad-7">
										<div class="category-img-sec">
											<div class="category-img">
												<img src="<?= App\asset_path('images/curtain.jpg'); ?>" alt="" class="img-responsive">
												<h3><a href="">Category Name</a></h3>
											</div>
										</div>
									</div>
									<div class="col-sm-6 pad-7">
										<div class="category-img-sec">
											<div class="category-img">
												<img src="<?= App\asset_path('images/books.jpg'); ?>" alt="" class="img-responsive">
												<h3><a href="">Category Name</a></h3>
											</div>
										</div>
									</div>
									<div class="col-sm-3 pad-7">
										<div class="category-img-sec">
											<div class="category-img">
												<img src="<?= App\asset_path('images/cosmetic.jpg'); ?>" alt="" class="img-responsive">
												<h3><a href="">Category Name</a></h3>
											</div>
										</div>
									</div>
								</div>
								<div class="category-box2">
									<div class="col-sm-4 pad-7">
										<div class="category-img-sec">
											<div class="category-img">
												<img src="<?= App\asset_path('images/bag.jpg'); ?>" alt="" class="img-responsive">
												<h3><a href="">Category Name</a></h3>
											</div>
										</div>
									</div>
									<div class="col-sm-4 pad-7">
										<div class="category-img-sec">
											<div class="category-img">
												<img src="<?= App\asset_path('images/grocery.jpg'); ?>" alt="" class="img-responsive">
												<h3><a href="">Category Name</a></h3>
											</div>
										</div>
									</div>
									<div class="col-sm-4 pad-7">
										<div class="category-img-sec">
											<div class="category-img">
												<img src="<?= App\asset_path('images/mobile.jpg'); ?>" alt="" class="img-responsive">
												<h3><a href="">Category Name</a></h3>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="category-carousel1">
								<h4>Lorem ipsum dolor sit amet</h4>
								<div class="row">
									<div class="col-md-12">
									  <div class="carousel carousel-showmanymove slide" id="carousel123">
										<div class="carousel-inner">
										  <div class="item active">
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img1.png'); ?>" class="img-responsive center-block">
													<p class="product-name">A Perfect Murder</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img1.png'); ?>" class="img-responsive center-block">
													<p class="product-name">A Perfect Murder</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img1.png'); ?>" class="img-responsive center-block">
													<p class="product-name">A Perfect Murder</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img1.png'); ?>" class="img-responsive center-block">
													<p class="product-name">A Perfect Murder</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img2.png'); ?>" class="img-responsive center-block">
													<p class="product-name">A-Zoom Precision Snap Caps 12 Gauge (2 Pack)</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img1.png'); ?>" class="img-responsive center-block">
													<p class="product-name">A Perfect Murder</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img1.png'); ?>" class="img-responsive center-block">
													<p class="product-name">A Perfect Murder</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img1.png'); ?>" class="img-responsive center-block">
													<p class="product-name">A Perfect Murder</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										</div>
										<a class="left carousel-control" href="#carousel123" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
										<a class="right carousel-control" href="#carousel123" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
									  </div>
									</div>
								  </div>
									
								<!--	 <div class="carousel carousel-showmanymoveone1 slide" id="itemslider1">
        <div class="carousel-inner">

          <div class="item active">
										<div class="col-md-5th-1 col-sm-4 col-md-offset-0 col-sm-offset-2">
											<a href="#"><img src="images/img1.png" class="img-responsive center-block"></a>
							  				<p class="product-name"><a href="">A Perfect Murder</a></p>
											<img src="images/5-star.png" alt="" class="img-responsive">
											<p class="price">$15.79</p>
										</div>
			</div>
										<div class="col-md-5th-1 col-sm-4">
											<a href="#"><img src="images/img2.png" class="img-responsive center-block"></a>
							  				<p class="product-name"><a href="">A-Zoom Precision Snap Caps 12 Gauge (2 Pack)</a></p>
											<img src="images/4-star.png" alt="" class="img-responsive">
											<p class="price">$15.79</p>
										</div>
										<div class="col-md-5th-1 col-sm-4">
											<a href="#"><img src="images/img3.png" class="img-responsive center-block"></a>
							  				<p class="product-name"><a href="">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</a></p>	
											<img src="images/5-star.png" alt="" class="img-responsive">
											<p class="price">$15.79</p>
										</div>
										<div class="col-md-5th-1 col-sm-4">
											<a href="#"><img src="images/img4.png" class="img-responsive center-block"></a>
							  				<p class="product-name"><a href="">ALEX Toys Craft Eco Crafts Scrapbook</a></p>
											<img src="images/5-star.png" alt="" class="img-responsive">
											<p class="price">$15.79</p>
										</div>
										<div class="col-md-5th-1 col-sm-4">
											<a href="#"><img src="images/img5.png" class="img-responsive center-block"></a>
											<p class="product-name"><a href="">AmazonBasics Shower Curtain with Hooks</a></p>
											<img src="images/4-star.png" alt="" class="img-responsive">
											<p class="price">$15.79</p>
										</div>
	
										</div>

										<div id="slider-control1">
										<a class="left carousel-control" href="#itemslider1" data-slide="prev"><img src="images/left-arrow.png" alt="Left" class="img-responsive"></a>
										<a class="right carousel-control" href="#itemslider1" data-slide="next"><img src="images/right-arrow.png" alt="Right" class="img-responsive"></a>
									  </div>
									  </div>-->
									
								  	
							</div>
							<div class="category-carousel2">
								<h4>Lorem ipsum dolor sit amet</h4>
								<div class="row">
									<div class="col-md-12">
									  <div class="carousel carousel-showmanymove slide" id="carouselABC">
										<div class="carousel-inner">
										  <div class="item active">
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img6.png'); ?>" class="img-responsive center-block">
													<p class="product-name">Battery Tender 081-0148-12 12.5′ Extension Cable</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>	
											 </div>
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
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
							<div class="category-carousel2">
								<h4>Lorem ipsum dolor sit amet</h4>
								<div class="row">
									<div class="col-md-12">
									  <div class="carousel carousel-showmanymove slide" id="carouselXYZ">
										<div class="carousel-inner">
										  <div class="item active">
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img9.png'); ?>" class="img-responsive center-block">
													<p class="product-name">Dupli-Color BSP100 Gray Paint Shop Finish System Primer – 32 oz. 32 Ounce</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img10.png'); ?>" class="img-responsive center-block">
													<p class="product-name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
													<img src="<?= App\asset_path('images/4-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>	
											 </div>
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
												</a>
											</div>
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="<?= App\asset_path('images/img3.png'); ?>" class="img-responsive center-block">
													<p class="product-name">ACDelco PF2257G Professional Engine Oil Filter and O-Ring</p>
													<img src="<?= App\asset_path('images/5-star.png'); ?>" alt="" class="img-responsive">
													<p class="price">$15.79</p>
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
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php endwhile; ?>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>