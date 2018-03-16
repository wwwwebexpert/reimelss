
<header>
	<!--top section start here-->
    <div class="top-header">
  		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<ul class="top-header-nav pull-left">
						<?php if(is_user_logged_in()): ?>
						<li><a href="<?php echo get_page_link(28); ?>"><i class="fas fa-user"></i> My Account</a></li>
						<li><i class="fas fa-heart"></i><?php echo do_shortcode('[ti_wishlist_products_counter]'); ?></li>
						<li class="logoutbtn"><a href="<?php echo wp_logout_url( home_url() ); ?>"><i class="fas fa-sign-in-alt"></i> Logout</a></li>
						<?php else: ?>
						<li><a href="#" data-toggle="modal" data-target="#loginModal"><i class="fas fa-user"></i> My Account</a></li>
						<li><i class="fas fa-heart"></i><?php echo do_shortcode('[ti_wishlist_products_counter]'); ?></li>
						<li><a href="#" data-toggle="modal" data-target="#loginModal"><i class="fas fa-sign-in-alt"></i> Login</a></li>
						<li><a href="#" data-toggle="modal" data-target="#registerModal"><i class="fas fa-edit"></i> Register</a></li>
						<?php endif; ?>
				  	</ul>
				</div>  
				<div class="col-sm-6">
					<ul class="welcome-text pull-right">
						<li><p>Welcome you to <span class="orange-text">Outlet Corner Shop</span>!</p></li>
				  	</ul>
				</div>
			</div>
		</div>
  	</div>
	<!--//top section end here-->
	<!--middle header start here-->
  	<div class="middle-header">
  		<div class="navbar navbar-inverse" role="navigation">
	  		<div class="header-top"><!--header-top-->
				<div class="container">
					<div class="row">
						<div class="col-sm-6">
							<div class="logo">
								<h2 class="header-logo"><a href="<?php echo site_url();; ?>"><?php echo the_field('logo_text','option');; ?></a></h2>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="logo-right pull-right">
								<ul class="">
									<li class="dropdown">
									  	<a href="#" class="dropdown-toggle cart" data-toggle="dropdown" role="button" aria-expanded="false"> <i class="fas fa-shopping-bag"></i> <?php echo sprintf ( _n( '%d item', '%d item(s)', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?><span class="caret"></span></a>
									  		<div class="dropdown-menu dropdown-cart" role="menu">
									  			<?php ( dynamic_sidebar('Mini Cart') ); ?>
											  	<!-- <li>
												  	<span class="item">
														<span class="item-left">
															<img src="http://lorempixel.com/50/50/" alt="" />
															<span class="item-info">
																<span>Item name</span>
																<span>23$</span>
															</span>
														</span>
														<span class="item-right">
															<button class="btn btn-xs btn-danger pull-right">x</button>
														</span>
													</span>
											  	</li>
										  		<li class="divider"></li>
										  		<li><a class="text-center" href="">View Cart</a></li> -->
									  		</div>
									</li>
									<li class="search-bar">
										<form id="custom-search-form" class="form-search form-horizontal" action="<?php echo site_url(); ?>" method="get">
											<div class="input-append span12">
												<input type="text" name="s" class="search-query mac-style" placeholder="Search">
												<a><i class="fas fa-search"></i></a>
											</div>
										</form>
									</li>	  
								</ul>
							</div>	
						</div>
					</div>
				</div>
	  		</div><!--//header-top-->
			<div class="header-bottom"><!--header-bottom-->
	  			<div class="container">
		  			<div class="row">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="collapse navbar-collapse">
							<?php if(has_nav_menu('primary_navigation')): ?>
						       <?php echo wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav']); ?>

						    <?php endif; ?>
					  <!-- <ul class="nav navbar-nav">
								<li class="active"><a href="#">Home</a></li>
								<li><a href="#about">About</a></li>
								<li class="dropdown dropdown-large">
									<a href="#contact" class="dropdown-toggle" data-toggle="dropdown">Shop <b class="caret"></b></a>
									<ul class="dropdown-menu dropdown-menu-large row">
										<li class="col-sm-12">
											<ul>
												<li><a href="#">Available glyphs</a></li>
												<li><a href="#">Examples</a></li>
											</ul>
										</li>
									</ul>  
								</li>
								<li><a href="#contact">Tracking</a></li>
								<li><a href="#contact">Contact</a></li>
							</ul> -->
						  	<div class="pull-right sl-nav">
							 	<ul>
							  		<li><i class="fas fa-globe"></i> EN <i class="fa fa-angle-down" aria-hidden="true"></i>
										<ul>
									   		<li><i class="sl-flag flag-usa"><div id="eng"></div></i>  <span>English</span></li>
									   		<li><span><?php echo do_shortcode('[glt language="German" label="German" image="yes" text="yes" image_size="15"]'); ?></span></li>
								   			<!-- <li><i class="sl-flag flag-de"><div id="germany"></div></i> <span class="active">German</span></li> -->
								   			<!-- <li><i class="sl-flag flag-de"><div id="germany"></div></i> <span class="active"></span></li> -->
										</ul>
							  		</li>
								</ul>
						  	</div>	
						</div>
		  			</div>
	  			</div>
			</div>
		</div>
  	</div>
	<!--//middle header end here-->
</header>