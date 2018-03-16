{{--
  Template Name: Front Page
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    @include('partials.content-page')
    <section>
		<div class="container">
			<!--banner section start here-->
		  <div class="top-banner">
			<div id="transition-timer-carousel" class="carousel slide transition-timer-carousel" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				@foreach ($slider as $index => $s_img)
					<li data-target="#transition-timer-carousel" data-slide-to="{{ $index }}" class="@if($index == '0') {{ 'active' }} @endif;"></li>
				@endforeach
			</ol>

			<!-- Wrapper for slides -->
			<div class="carousel-inner">
				@foreach ($slider as $index => $s_img)
				    <div class="item @if($index == '0') {{ 'active' }} @endif;">
	                    <img src="{{ $s_img->slides }}" alt="" class="img-responsive"/>
	                </div>
				@endforeach
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
			<!--//banner section end here-->
		  <div class="row">
				<div class="col-sm-9">
			    	<div class="featured-products">
						<div class="category-carousel3">
							<h4>Featured Products <span><a href="{!! get_page_link(8) !!}">See More</a></span></h4>
								<div class="row">
									<div class="col-md-12">
									  <div class="carousel carousel-showmanymove slide" id="carouselABC">
										<div class="carousel-inner">
											@php ( $count = '1' )
											@foreach ($featuredproduct as $index => $f_p_data)
												@if ($count%4 == 1)  
											        <div class="item @if ($loop->iteration == 1) {{ 'active' }} @endif">
											    @endif
													<div class="col-xs-12 col-sm-6 col-md-3">
														<a href="{{ $f_p_data->p_link }}">
															<img src="{{ $f_p_data->p_img }}" class="img-responsive center-block" alt="">
															<p class="product-name">{{ $f_p_data->p_title }}</p>
															@php ( $average = $f_p_data->p_average )
															<input id="input-{!! $index !!}" name="input-{!! $index !!}" class="rating rating-loading" data-min="0" data-max="5" data-step="0.{!! $index !!}" value="@if( $average !='' ){!! $average !!} @else {!! '0' !!} @endif" readonly>
															<p class="price">{!! $f_p_data->p_price !!}</p>
														</a>
													</div>
												@if ($count%4 == 0)
											        </div>
											    @endif
											@php ( $count++ )
											@endforeach
											@if ($count%4 != 1)
												</div>
											@endif	
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
								<img src="@asset('images/deal-day.jpg')" alt="" class="img-responsive">
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
											@php ( $count = '1' )
											@foreach ($featuredproduct as $index => $f_p_data)
												@if ($count%4 == 1)  
											        <div class="item @if ($loop->iteration == 1) {{ 'active' }} @endif">
											    @endif
													<div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img9.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												@if ($count%4 == 0)
											        </div>
											    @endif
											@php ( $count++ )
											@endforeach
											@if ($count%4 != 1)
												</div>
											@endif
										  <!-- <div class="item active">
											<div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="@asset('images/img9.png')" class="img-responsive center-block" alt="">
												</a>
												
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="@asset('images/img10.png')" class="img-responsive center-block" alt="">
												</a>	
											 </div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="@asset('images/img11.png')" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>          
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="@asset('images/img12.png')" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="@asset('images/img13.png')" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="@asset('images/img14.png')" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="@asset('images/img15.png')" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div>
										  <div class="item">
											 <div class="col-xs-12 col-sm-6 col-md-3">
												<a href="#">
													<img src="@asset('images/img8.png')" class="img-responsive center-block" alt="">
												</a>
											</div>
										  </div> -->
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
													@php ( $count = '1' )
														@foreach ($featuredproduct as $index => $f_p_data)
															@if ($count%4 == 1)  
														        <div class="item @if ($loop->iteration == 1) {{ 'active' }} @endif">
														    @endif
																<div class="col-xs-12 col-sm-6 col-md-3">
																	<a href="#">
																		<img src="@asset('images/img9.png')" class="img-responsive center-block" alt="">
																	</a>
																</div>
															@if ($count%4 == 0)
														        </div>
														    @endif
														@php ( $count++ )
														@endforeach
														@if ($count%4 != 1)
															</div>
														@endif
												  <!-- <div class="item active">
													<div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img16.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img17.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img18.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>          
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img19.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img5.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img6.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img7.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img8.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div> -->
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
											<a href=""><img src="@asset('images/shop-now-img1.jpg')" alt="" class="img-responsive"></a>
										</div>	
									</div>
									<div class="col-sm-6">
										<div class="shop-img-right">
											<a href=""><img src="@asset('images/shop-now-img2.jpg')" alt="" class="img-responsive"></a>
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
													@php ( $count = '1' )
													@foreach ($featuredproduct as $index => $f_p_data)
														@if ($count%4 == 1)  
													        <div class="item @if ($loop->iteration == 1) {{ 'active' }} @endif">
													    @endif
															<div class="col-xs-12 col-sm-6 col-md-3">
																<a href="#">
																	<img src="@asset('images/img9.png')" class="img-responsive center-block" alt="">
																</a>
															</div>
														@if ($count%4 == 0)
													        </div>
													    @endif
													@php ( $count++ )
													@endforeach
													@if ($count%4 != 1)
														</div>
													@endif
												  <!-- <div class="item active">
													<div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img17.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img10.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img13.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>          
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img9.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img15.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img6.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img7.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div>
												  <div class="item">
													 <div class="col-xs-12 col-sm-6 col-md-3">
														<a href="#">
															<img src="@asset('images/img8.png')" class="img-responsive center-block" alt="">
														</a>
													</div>
												  </div> -->
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
			    		@foreach ($productcategories as $index => $p_cat)
				    		<div class="advs">
								<a href="{!! $p_cat->p_cat_link !!}"><img src="{!! $p_cat->p_cat_img !!}" alt="" class="img-responsive"></a>
							</div>
							<hr>
			    		@endforeach
						<!-- <div class="advs">
							<a href="#"><img src="{!! $p_cat->p_cat_img !!}" alt="" class="img-responsive"></a>
						</div>
						<hr> -->
						<!-- <div class="advs">
							<a href="#"><img src="@asset('images/adv2.jpg')" alt="" class="img-responsive"></a>
						</div>
						<hr>
						<div class="advs">
							<a href="#"><img src="@asset('images/adv3.jpg')" alt="" class="img-responsive"></a>
						</div>
						<hr>
						<div class="advs">
							<a href="#"><img src="@asset('images/adv4.jpg')" alt="" class="img-responsive"></a>
						</div> -->
					</div>	
			    </div>
			</div>
			<hr>
			 <div class="recommendations">
			  	 <div class="col-xs-12 col-sm-12 col-md-12">
						<h4 class="text-center">We Have Recommendations for You</h4>
					  <div class="carousel carousel-showmanymoveone slide" id="recommendations-slider">
						<div class="carousel-inner">
							@php ( $count = '1' )
							@foreach ($featuredproduct as $index => $f_p_data)
								@if ($count%6 == 1)  
							        <div class="item @if ($loop->iteration == 1) {{ 'active' }} @endif">
							    @endif
									<div class="col-xs-12 col-sm-6 col-md-2">
									  <a href="#">
										<img src="@asset('images/img1.png')" class="img-responsive center-block" alt="">
									  </a>
									</div>
								@if ($count%6 == 0)
							        </div>
							    @endif
							@php ( $count++ )
							@endforeach
							@if ($count%6 != 1)
								</div>
							@endif
						  <!-- <div class="item active">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="@asset('images/img1.png')" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="@asset('images/img2.png')" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="@asset('images/img3.png')" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="@asset('images/img4.png')" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="@asset('images/img5.png')" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div>

						  <div class="item">
							<div class="col-xs-12 col-sm-6 col-md-2">
							  <a href="#">
								<img src="@asset('images/img6.png')" class="img-responsive center-block" alt="">
							  </a>
							</div>
						  </div> -->

						</div>

						<div id="slider-control">
						<a class="left carousel-control" href="#recommendations-slider" data-slide="prev"><img src="@asset('images/left-arrow.png')" alt="Left" class="img-responsive"></a>
						<a class="right carousel-control" href="#recommendations-slider" data-slide="next"><img src="@asset('images/right-arrow.png')" alt="Right" class="img-responsive"></a>
					  </div>
			 	   </div>
				</div>
			   </div>
			   <div class="bottom-block">
				   <div class="row">
						<div class="col-sm-4">
							<div class="block">
								<img src="@asset('images/free-delivery.jpg')" alt="" class="img-responsive">	
							</div>
						</div>
						<div class="col-sm-4">
							<div class="block">
								<img src="@asset('images/money-back.jpg')" alt="" class="img-responsive">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="block">
								<img src="@asset('images/supports.jpg')" alt="" class="img-responsive">
							</div>
						</div>
				   </div>
			   </div>
			
			
			
			
			
		</div>
	</section>











  @endwhile
@endsection