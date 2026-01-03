@extends('layouts.landing')

@section('content')
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
                        <div class="col-12 text-center">
                            <div class="slider-content second-slider-content">
                                <h2 data-animation="fadeInUp animated" data-delay=".4s">Lorem Ipsum</h2> 
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
                                        @foreach($event->eventDates as $eventDate)
                                            {{ \Carbon\Carbon::parse($eventDate->date)->format('M d, Y') }}{{ !$loop->last ? ' | ' : '' }}
                                        @endforeach
                                    </li>
                                    <li><i class="fas fa-users"></i> 
                                        @if($event->capacity)
                                            Max {{ $event->capacity }} Participants (Joined: {{ $event->students()->count() }})
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
                                    <a href="#" class="btn mt-20">Read More</a>
                                    
                                    @if(Auth::guard('student')->check())
                                    @php 
                                        $isJoined = $event->students()->where('student_id', Auth::guard('student')->user()->student->id)->exists();
                                        $isFull = $event->capacity && $event->students()->count() >= $event->capacity;
                                    @endphp
                                    
                                    @if($isJoined)
                                        <button class="btn mt-20 ml-10" style="background: #28a745; border-color: #28a745;" disabled>Joined</button>
                                    @elseif($isFull)
                                        <button class="btn mt-20 ml-10" style="background: #dc3545; border-color: #dc3545;" disabled>Event Full</button>
                                    @else
                                        <form action="{{ route('student.events.join', $event) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn mt-20 ml-10">Join Event</button>
                                        </form>
                                    @endif
                                    @endif
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
@endsection
