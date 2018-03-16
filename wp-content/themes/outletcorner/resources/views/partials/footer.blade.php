 <!--<footer class="content-info">
  <div class="container">
    @php(dynamic_sidebar('sidebar-footer'))
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
							<h2><a href="{!! site_url(); !!}">{!! the_field('logo_text','option'); !!}</a></h2>
						</div>
						<div class="f-social-icons">
							<h3>Social Icons:</h3>
							<ul>
								@foreach ($socialshare as $index => $s_share)
									<li><a href="{{ $s_share->s_link }}"><img src="{{ $s_share->s_icon }}" alt=""></a></li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="sec-col">
						<div class="quick-links">
							<h3>Quick Links:</h3>
							@if (has_nav_menu('footer_navigation'))
						       {!! wp_nav_menu(['theme_location' => 'footer_navigation']) !!}
						    @endif
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
								@foreach ($latestproduct as $index => $l_p_data)
									<li><a href="{{ $l_p_data->p_link }}"><img src="{{ $l_p_data->p_img }}" class="img-responsive" alt=""></a></li>
								@endforeach
								<!-- <li><a href=""><img src="@asset('images/f-img1.jpg')" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="@asset('images/f-img1.jpg')" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="@asset('images/f-img1.jpg')" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="@asset('images/f-img1.jpg')" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="@asset('images/f-img1.jpg')" class="img-responsive" alt=""></a></li>
								<li><a href=""><img src="@asset('images/f-img1.jpg')" class="img-responsive" alt=""></a></li> -->
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
						<p>{!! $copyright !!}</p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="terms">
						<p><a href="{!! $termcondition !!}">Terms and Conditions / Privacy Policy</a></p>
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
        {!! do_shortcode('[wppb-login]') !!}
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
        {!! do_shortcode('[wppb-register role="customer"]') !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--//model for register form-->