@extends('user.profile1.theme3.layout')

@section('tab-title')
{{$keywords["Experience"] ?? "Experience"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->testimonial_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->testimonial_meta_keywords : '')

@section('content')
    <!--====== Start Main Wrapper ======-->
    <div class="main-wrapper inner">
        
        <section class="testimonial-area pt-110 pb-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-title mb-30">
                            <span class="sub-title">{{$home_text->testimonial_title ?? __('Testimonials')}}</span>
                            <h2><span class="light-text">{{$home_text->testimonial_subtitle ?? __('Testimonials')}}</span></h2>
                        </div>
                    </div>
                </div>
                <div class="row no-gutters testimonial-wrapper">
                    @foreach($testimonials as $testimonial)
                        <div class="col-md-6">
                            <div class="testimonial-item">
                                <div class="testimonial-author-content">
                                    <div class="author-thumb">
                                        <img data-src="{{asset('assets/front/img/user/testimonials/'.$testimonial->image)}}" class="lazy" alt="Author">
                                        <svg width="97" height="94" viewBox="0 0 97 94" fill="none">
                                            <g clip-path="url(#clip0_317_137)">
                                                <path d="M41.8372 80.6417L41.8384 80.6424C45.6647 82.957 50.4392 83.0507 54.3614 80.8864L74.4391 69.8074C78.3612 67.6431 80.8276 63.5538 80.9095 59.0827L80.9095 59.0813L81.3598 36.1664C81.4417 31.6937 79.1407 27.5238 75.3132 25.2082C75.3131 25.2082 75.313 25.2081 75.3128 25.208L55.6865 13.3715L55.6853 13.3708C51.8591 11.0562 47.0845 10.9625 43.1624 13.1268L23.0846 24.2058C19.1625 26.3701 16.6961 30.4594 16.6143 34.9305L16.6142 34.9319L16.164 57.8468C16.0821 62.3194 18.3829 66.4893 22.2103 68.8048C22.2105 68.805 22.2107 68.8051 22.2109 68.8052L41.8372 80.6417Z" stroke="#F57236" stroke-width="2"/>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="content">
                                        <p>{!! nl2br($testimonial->content) !!}</p>
                                    </div>
                                </div>
                                <div class="author-info">
                                    <h5>{{$testimonial->name}}</h5>
                                    <span class="location">{{$testimonial->occupation}}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div><!--====== End Main Wrapper ======-->
@endsection