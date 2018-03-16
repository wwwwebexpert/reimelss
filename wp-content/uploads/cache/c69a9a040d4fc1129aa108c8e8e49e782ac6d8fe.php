 <!--<footer class="content-info">
  <div class="container">
    <?php (dynamic_sidebar('sidebar-footer')); ?>
  </div>
</footer> -->
<footer>
	<div class="footer-top">
		<a href="javascript:" id="return-to-top">
			<div class="navFooterBackToTop">
				<span class="navFooterBackToTopText">Back to top</span>
			</div>
		</a>
	</div>
	<div class="footer-middle">
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<div class="first-col">
						<div class="f-logo">
							<h2><a href="<?php echo site_url();; ?>"><?php echo the_field('logo_text','option');; ?></a></h2>
						</div>
						<div class="f-social-icons">
							<h3>Social Icons:</h3>
							<ul>
								<?php $__currentLoopData = $socialshare; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s_share): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<li><a href="<?php echo e($s_share->s_link); ?>"><img src="<?php echo e($s_share->s_icon); ?>" alt=""></a></li>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="sec-col">
						<div class="quick-links">
							<h3>Quick Links:</h3>
							<?php if(has_nav_menu('footer_navigation')): ?>
						       <?php echo wp_nav_menu(['theme_location' => 'footer_navigation']); ?>

						    <?php endif; ?>
							<!-- <ul>
								<li><a href="index.html">Home</a></li>
								<li><a href="">About </a></li>
								<li><a href="">Shop</a></li>
								<li><a href="">Contact</a></li>
							</ul> -->
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="third-col">
						<div class="thumbnails latestproductsfooter">
							<h3>Latest Products:</h3>
							<ul>
								<?php $__currentLoopData = $latestproduct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $l_p_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<li><a href="<?php echo e($l_p_data->p_link); ?>"><img src="<?php echo e($l_p_data->p_img); ?>" class="img-responsive" alt=""></a></li>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<!-- <li><a href=""><img src="<?= App\asset_path('images/f-img1.jpg'); ?>" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="<?= App\asset_path('images/f-img1.jpg'); ?>" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="<?= App\asset_path('images/f-img1.jpg'); ?>" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="<?= App\asset_path('images/f-img1.jpg'); ?>" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="<?= App\asset_path('images/f-img1.jpg'); ?>" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="<?= App\asset_path('images/f-img1.jpg'); ?>" class="img-responsive" alt=""></a></li> -->
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<div class="copyright">
						<p><?php echo $copyright; ?></p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="terms">
						<p><a href="<?php echo $termcondition; ?>">Terms and Conditions / Privacy Policy</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<!--model for login form-->
<!-- Modal -->
<div id="loginModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <!-- <h4 class="modal-title">Modal Header</h4> -->
      </div>
      <div class="modal-body">
        <?php echo do_shortcode('[wppb-login]'); ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--//model for login form-->
<!--model for register form-->
<!-- Modal -->
<div id="registerModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Register Here</h4>
      </div>
      <div class="modal-body">
        <?php echo do_shortcode('[wppb-register role="customer"]'); ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--//model for register form-->