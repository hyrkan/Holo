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
@endsection
