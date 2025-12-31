<!doctype html>
<html class="no-js" lang="zxx">
    
<!-- Mirrored from htmldemo.zcubethemes.com/eventes/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Dec 2025 09:29:32 GMT -->
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
         <title>Holo Board - Student Management System</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="{{ asset('landing/img') }}/favicon.ico">
        <!-- Place favicon.ico in the root directory -->

		<!-- CSS here -->
		<link href="https://fonts.googleapis.com/css?family=Karla:400,400i,700,700i&amp;display=swap" rel="stylesheet"> 
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="{{ asset('landing/css') }}/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('landing/css') }}/animate.min.css">
        <link rel="stylesheet" href="{{ asset('landing/css') }}/magnific-popup.css">
        <link rel="stylesheet" href="{{ asset('landing/fontawesome') }}/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('landing/css') }}/dripicons.css">
        <link rel="stylesheet" href="{{ asset('landing/css') }}/slick.css">
        <link rel="stylesheet" href="{{ asset('landing/css') }}/default.css">
        <link rel="stylesheet" href="{{ asset('landing/css') }}/style.css">
        <link rel="stylesheet" href="{{ asset('landing/css') }}/responsive.css">
        <style>
            #parallax::before {
                background: rgba(71, 0, 200, 0.7) !important;
                opacity: 1 !important;
            }
        </style>
    </head>
    <body>
        <!-- header -->
        <header id="home" class="header-area">            
            <div id="header-sticky" class="menu-area">
                <div class="container">
                    <div class="second-menu">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-lg-3">
                                <div class="logo">
                                    <a href="{{ url('/') }}"><img src="{{ asset('landing/img/logo.jpg') }}" alt="Holo Board" style="max-height: 100px; width: auto;"></a>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9">
                                <div class="responsive"><i class="icon dripicons-align-right"></i></div>
                                <div class="main-menu text-right">
                                    <nav id="mobile-menu">
                                        <ul>
                                            <li><a href="{{ url('/') }}">Home</a></li>
                                            <li><a href="#">Announcements</a></li>
                                            <li><a href="#">Lost and Found</a></li>
                                            @if(Auth::guard('student')->check())
                                                <li><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                                            @elseif(Auth::guard('web')->check())
                                                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                            @else
                                                <li><a href="{{ route('student.login') }}">Login</a></li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- header-end -->
        <!-- main-area -->
        <main>
            <!-- slider-area -->
            <section id="parallax" class="slider-area slider-bg second-slider-bg slider-bg2 d-flex align-items-center justify-content-center fix" style="background-image:url({{ asset('landing/img/background.jpg') }})">
                <div class="slider-shape ss-one layer" data-depth="0.10"><img src="{{ asset('landing/img') }}/doddle_6.png" alt="shape"></div>
                <div class="slider-shape ss-two layer" data-depth="0.30"><img src="{{ asset('landing/img') }}/doddle_8.png" alt="shape"></div>
                <div class="slider-shape ss-three layer" data-depth="0.40"><img src="{{ asset('landing/img') }}/doddle_9.png" alt="shape"></div>
                <div class="slider-shape ss-four layer" data-depth="0.60"><img src="{{ asset('landing/img') }}/doddle_7.png" alt="shape"></div>
                <div class="slider-active">
                    <div class="single-slider">
                        <div class="container">
                            <div class="row">
                                {{-- <div class="col-12 text-center">
                                    <div class="slider-content second-slider-content">
										<h2 data-animation="fadeInUp animated" data-delay=".4s">Digital World Conference</h2> 
                                        <ul data-animation="fadeInUp animated" data-delay=".2s">
											<li><i class="fas fa-map-marker-alt"></i> Waterfront Hotel, London</li>
											<li><i class="far fa-clock"></i>  5 - 7 June 2019, </li>
											<li><i class="fal fa-building"></i>  Edition </li>
										</ul>
                                        
                                    </div>
                                </div> --}}
                                <div class="col-12 text-center">
                                    <div class="slider-content second-slider-content">
										<h2 data-animation="fadeInUp animated" data-delay=".4s">Lorem Ipsum</h2> 
                                        {{-- <ul data-animation="fadeInUp animated" data-delay=".2s">
											<li><i class="fas fa-map-marker-alt"></i> Waterfront Hotel, London</li>
											<li><i class="far fa-clock"></i>  5 - 7 June 2019, </li>
											<li><i class="fal fa-building"></i>  Edition </li>
										</ul> --}}
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- slider-area-end -->

            <!-- about-area-end -->
			
			<!-- event -->
            <div class="event fix pt-120 pb-120">
			 <div class="section-t team-t paroller" data-paroller-factor="0.15" data-paroller-factor-lg="0.15" data-paroller-factor-md="0.15" data-paroller-factor-sm="0.15" data-paroller-type="foreground" data-paroller-direction="horizontal"><h2>Event</h2></div>
			 <div class="row justify-content-center">
                        <div class="col-xl-6 col-lg-8">
                            <div class="section-title text-center mb-80">
                                <span class="wow fadeInUp animated" data-animation="fadeInUp animated" data-delay=".2s">Event</span>
                                <h2 class="wow fadeInUp animated" data-animation="fadeInUp animated" data-delay=".2s">Event On This Month</h2>
                            </div>
                        </div>
                    </div>
                <div class="container">
				<div class="row">
			   <div class="col-lg-12 ">		
                {{-- use later		 --}}
                  {{-- <nav class="wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
                     <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-home-tab" data-toggle="tab" href="#one" role="tab" aria-selected="true">
						<img src="{{ asset('landing/img') }}/t-icon.png" alt="img" class="drk-icon">		
						<img src="{{ asset('landing/img') }}/t-w-icon1.png" alt="img" class="lgt-icon">  
						<div class="nav-content">
							<strong>First Day</strong>
							<span>10th January 2019</span>
						</div>
						</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#two" role="tab" aria-selected="false"><img src="{{ asset('landing/img') }}/t-icon.png" alt="img" class="drk-icon">		
						<img src="{{ asset('landing/img') }}/t-w-icon1.png" alt="img" class="lgt-icon"> 
						<div class="nav-content">
							<strong>Second Day</strong>
							<span>10th January 2019</span>
						</div>
						</a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#three" role="tab" aria-selected="false"><img src="{{ asset('landing/img') }}/t-icon.png" alt="img" class="drk-icon">		
						<img src="{{ asset('landing/img') }}/t-w-icon1.png" alt="img" class="lgt-icon"> 
						<div class="nav-content">
							<strong>Third Day</strong>
							<span>10th January 2019</span>
						</div>
						</a>
						<a class="nav-item nav-link" id="nav-contact-tab2" data-toggle="tab" href="#four" role="tab" aria-selected="false"><img src="{{ asset('landing/img') }}/t-icon.png" alt="img" class="drk-icon">		
						<img src="{{ asset('landing/img') }}/t-w-icon1.png" alt="img" class="lgt-icon"> 
						<div class="nav-content">
							<strong>Fourth Day</strong>
							<span>10th January 2019</span>
						</div>
						</a>
                     </div>
                  </nav> --}}
                  <div class="tab-content py-3 px-3 px-sm-0 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s" id="nav-tabContent">
                     <div class="tab-pane fade active show" id="one" role="tabpanel" aria-labelledby="nav-home-tab">
                        @forelse($events as $event)
                        <div class="row mb-30">
                           <div class="col-lg-2">
							  <div class="user">
								  <div class="title">  
                                     @if($event->speakers->count() > 0)
                                        @php $speaker = $event->speakers->first(); @endphp
                                        <img src="{{ $speaker->image ? asset('storage/' . $speaker->image) : asset('landing/img/event_avatar_1.png') }}" alt="img" style="border-radius: 50%; width: 60px; height: 60px; object-fit: cover;">							  
                                        <h5>{{ $speaker->first_name }} {{ $speaker->last_name }}</h5>
                                        <p>{{ $speaker->title ?? $speaker->company }}</p>
                                     @else
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="img" style="border-radius: 50%; width: 60px; height: 60px; object-fit: cover;">							  
                                        <h5>{{ $event->name }}</h5>
                                        <p>Event</p>
                                     @endif
								  </div>
								  <ul>
                                 @if($event->tags)
                                    @foreach($event->tags as $tag)
                                       <li><i class="fal fa-tag"></i> {{ $tag }}</li>
                                    @endforeach
                                 @endif
                              </ul>
							  </div>
                           </div>
                           <div class="col-lg-10">
                              <div class="event-list-content fix">
                                 <ul data-animation="fadeInUp animated" data-delay=".2s" style="animation-delay: 0.2s;" class="">
                                    <li><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</li>
                                    <li><i class="far fa-clock"></i> 
                                        @foreach($event->dates as $date)
                                            {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}{{ !$loop->last ? ' | ' : '' }}
                                        @endforeach
                                    </li>
                                    <li><i class="fas fa-users"></i> 
                                        @if($event->capacity)
                                            Max {{ $event->capacity }} Participants
                                        @else
                                            Unlimited Participants
                                        @endif
                                    </li>
                                    <li><i class="fas fa-graduation-cap"></i>
                                        @if(!$event->departments || in_array('All', $event->departments))
                                            Open for All Departments
                                        @else
                                            For: {{ implode(', ', $event->departments) }}
                                        @endif
                                    </li>
								 </ul>
								 <h2>{{ $event->name }}</h2>
								 <p>{{ $event->description }}</p>
								 {{-- <a href="#" class="btn mt-20 mr-10"><i class="far fa-ticket-alt"></i> Buy Ticket</a> --}}
								 <a href="#" class="btn mt-20">Read More</a>
								 {{-- <div class="crical"><i class="fal fa-video"></i> </div> --}}
                              </div>
                           </div>
                        </div>
                        @empty
                        <div class="row">
                            <div class="col-12 text-center">
                                <p>No events scheduled for this month.</p>
                            </div>
                        </div>
                        @endforelse
                     </div>
                  </div>
               </div>
			   <div class="col-lg-12 justify-content-center text-center">
				<a href="#" class="btn mt-20 mr-10">More Program  +</a>
			   </div>
			   </div>
            </div>
            </div>
            <!-- Sponsors-area-end -->
           
            <!-- blog-area -->
            <section id="blog" class="blog-area p-relative fix pt-100 pb-80">
			 
                <div class="container">
                  <div class="section-t team-t paroller" data-paroller-factor="0.15" data-paroller-factor-lg="0.15" data-paroller-factor-md="0.15" data-paroller-factor-sm="0.15" data-paroller-type="foreground" data-paroller-direction="horizontal"><h2>News</h2></div>
			 <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-8">
                            <div class="section-title mb-80">
                                <span class="wow fadeInUp animated" data-animation="fadeInUp animated" data-delay=".2s">feeds</span>
                                <h2 class="wow fadeInUp animated" data-animation="fadeInUp animated" data-delay=".2s">Announcements</h2>
                            </div>
                        </div>
						<div class="col-xl-4 col-lg-4 text-right">
                           
                        </div>
                    </div>
                    <div class="row blog-active2 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
                        @forelse($announcements as $announcement)
                        <div class="col-lg-4 col-md-6">
                            <div class="single-post mb-30">
                                <div class="blog-thumb">
                                    <a href="#"><img src="{{ $announcement->image ? asset('storage/' . $announcement->image) : asset('landing/img/blog_img_1.jpg') }}" alt="img" style="height: 250px; width: 100%; object-fit: cover;"></a>
                                </div>
                                <div class="blog-content">
                                    <div class="b-meta mb-20">
                                        <ul>
                                            <li><i class="far fa-calendar-alt"></i> {{ $announcement->start_date->format('M d, Y') }}</li>
                                        </ul>
                                    </div>
                                    <h4><a href="#">{{ $announcement->title }}</a></h4>
                                    <p>{{ Str::limit(strip_tags($announcement->content), 120) }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center">
                            <p>No announcements for this month.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </section>
            <!-- blog-area-end -->
        </main>
        <!-- main-area-end -->
        <!-- footer -->
        <footer class="footer-bg footer-p" style="background-image:url({{ asset('landing/img') }}/footer_bg_img.jpg);background-size: cover;">
          
            <div class="footer-top">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-12 col-lg-12 col-sm-12 text-center">
                            <div class="footer-widget pt-120 mb-30">
                                <div class="logo mb-35">
                                    <a href="{{ url('/') }}"><h2 class="text-white mb-0">Holo Board</h2></a>
                                </div>
                                <div class="footer-text mb-20">
                                    <p>The issue with any content strategy is time. Time to sit down and think about what kind of content should be created, time to stop and write, or record, edit and publish, and time to engage with your audience to promote the content you created.</p>
                                </div>
                                <div class="footer-social">                                    
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                    <a href="#"><i class="fab fa-google-plus-g"></i></a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="copyright-wrap pb-120">
                <div class="container">
                    <div class="row">
                        <div class="col-12">						
                            <div class="copyright-text text-center">
								<div class="footer-link">
                                    <ul>
                                        <li><a href="#">About</a></li>
                                        
                                        <li><a href="#">Blog</a></li>
                                        <li><a href="#">Contact</a></li>
                                        <li><a href="#">Tickets</a></li>
                                        <li><a href="#">Venue</a></li>
                                    </ul>
                                </div>                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- footer-end -->



		<!-- JS here -->
        <script src="{{ asset('landing/js') }}/vendor/modernizr-3.5.0.min.js"></script>
        <script src="{{ asset('landing/js') }}/vendor/jquery-1.12.4.min.js"></script>
        <script src="{{ asset('landing/js') }}/popper.min.js"></script>
        <script src="{{ asset('landing/js') }}/bootstrap.min.js"></script>
        <script src="{{ asset('landing/js') }}/one-page-nav-min.js"></script>
        <script src="{{ asset('landing/js') }}/slick.min.js"></script>
        <script src="{{ asset('landing/js') }}/ajax-form.js"></script>
        <script src="{{ asset('landing/js') }}/paroller.js"></script>
        <script src="{{ asset('landing/js') }}/wow.min.js"></script>
        <script src="{{ asset('landing/js') }}/parallax.min.js"></script>
        <script src="{{ asset('landing/js') }}/jquery.waypoints.min.js"></script>
        <script src="{{ asset('landing/js') }}/jquery.countdown.min.js"></script>
        <script src="{{ asset('landing/js') }}/jquery.counterup.min.js"></script>
        <script src="{{ asset('landing/js') }}/jquery.scrollUp.min.js"></script>
        <script src="{{ asset('landing/js') }}/jquery.magnific-popup.min.js"></script>
        <script src="{{ asset('landing/js') }}/element-in-view.js"></script>
		<script src="{{ asset('landing/js') }}/isotope.pkgd.min.js"></script>
        <script src="{{ asset('landing/js') }}/imagesloaded.pkgd.min.js"></script>
        <script src="{{ asset('landing/js') }}/main.js"></script>
    </body>


</html>
