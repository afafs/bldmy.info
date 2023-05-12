@extends('vcard.layout')

@if ($vcard->direction == 2)
    @section('rtl', 'dir=rtl')
@endif

@if ($vcard->direction == 2)
@section('rtl-css')
<link rel="stylesheet" href="{{asset('assets/front/css/profile/vcard/template1234/vcard-rtl.css')}}">
@endsection
@endif

@section('base-color')
<link rel="stylesheet" href="{{asset('assets/front/css/profile/vcard/template1234/vcard-base-color.php').'?color='.$vcard->base_color}}">
@endsection

@section('og-image', asset('assets/front/img/user/vcard/' . $vcard->cover_image))
@section('og-title')
    {{$vcard->name}} ({{$vcard->occupation}})
@endsection
@section('og-description')
    {{$vcard->introduction}}
@endsection

@section('content')
    <!--====== Start Page Wrapper ======-->
    <div class="page-wrapper">
        <div class="container p-0">
            <div class="page-content page-content-two">
                <div class="page-banner bg_cover" style="background-image: url({{!empty($vcard->cover_image) ? asset('assets/front/img/user/vcard/' . $vcard->cover_image) : asset('assets/front/img/user/vcard/vcard_cover.jpg')}});"></div>
                <div class="admin-wrapper">
                    <div class="admin-card text-center">
                        <div class="thumb">
                            <img src="{{!empty($vcard->profile_image) ? asset('assets/front/img/user/vcard/' . $vcard->profile_image) : asset('assets/front/img/user/blank_propic.png')}}" alt="Thumb">
                        </div>
                        <div class="content">
                            <div class="admin-title">
                                <h5>{{$vcard->name}}</h5>
                                <span class="position">{{$vcard->occupation}}</span>
                            </div>
                            <p>{{$vcard->introduction}}</p>
                            <div class="actions">
                                @if (!empty($vcard->phone) && (is_array($prefs) && in_array('Call', $prefs)))
                                <div class="action-btn">
                                    <a class="call" href="tel:{{$vcard->phone}}" style="background: #{{$vcard->call_button_color}}"><i class="fas fa-mobile-alt"></i></a>
                                    <br>
                                    <span>{{$keywords["Call"] ?? "Call"}}</span>
                                </div>
                                @endif
                                @if (!empty($vcard->phone) && (is_array($prefs) && in_array('Whatsapp', $prefs)))
                                <div class="action-btn">
                                    <a href="https://wa.me/{{$vcard->phone}}?text=Hello" class="whatsapp" style="background: #{{$vcard->whatsapp_button_color}}"><i class="fab fa-whatsapp"></i></a>
                                    <br>
                                    <span>{{$keywords["Whatsapp"] ?? "Whatsapp"}}</span>
                                </div>
                                @endif
                                @if (!empty($vcard->email) && (is_array($prefs) && in_array('Mail', $prefs)))
                                <div class="action-btn">
                                    <a class="mail" href="mailto:{{$vcard->email}}" style="background: #{{$vcard->mail_button_color}}"><i class="far fa-envelope"></i></a>
                                    <br>
                                    <span>{{$keywords["Mail"] ?? "Mail"}}</span>
                                </div>
                                @endif
                            </div>
                            <div class="more-btns text-center mt-3">
                                @if (!empty($vcard->phone) && (is_array($prefs) && in_array('Add to Contact', $prefs)))
                                <a href="{{route('front.user.vcardImport', [getParam(), $vcard->id])}}" class="add-contact" style="background: #{{$vcard->add_to_contact_button_color}}"><i class="fas fa-plus"></i> {{$keywords["Add_to_Contact"] ?? "Add to Contact"}}</a>
                                @endif
                                @if (is_array($prefs) && in_array('Share vCard', $prefs))
                                <a href="#" data-toggle="modal" data-target="#socialMediaModal" class="share" style="background: #{{$vcard->share_vcard_button_color}}"><i class="fas fa-share-square"></i> {{$keywords["Share_vCard"] ?? "Share vCard"}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if (is_array($prefs) && in_array('Information', $prefs))
                <div class="page-info-widget">
                    @if (!empty($vcard->phone))
                    <div class="info-widget d-flex align-items-center">
                        <a href="tel:{{$vcard->phone}}" class="icon icon-1" style="background: #{{$vcard->phone_icon_color}}1a;">
                            <i class="fas fa-mobile-alt" style="color: #{{$vcard->phone_icon_color}};"></i>
                        </a>
                        <div class="content">
                            <span class="title">{{$keywords["Phone"] ?? "Phone"}}</span>
                            <h5><a href="tel:{{$vcard->phone}}">{{$vcard->phone}}</a></h5>
                        </div>
                    </div>
                    @endif
                    @if (!empty($vcard->email))
                    <div class="info-widget d-flex align-items-center">
                        <a href="mailto:{{$vcard->email}}" class="icon icon-1" style="background: #{{$vcard->email_icon_color}}1a;">
                            <i class="fas fa-at" style="color: #{{$vcard->email_icon_color}};"></i>
                        </a>
                        <div class="content">
                            <span class="title">{{$keywords["Email"] ?? "Email"}}</span>
                            <h5><a href="mailto:{{$vcard->email}}">{{$vcard->email}}</a></h5>
                        </div>
                    </div>
                    @endif
                    @if (!empty($vcard->address))
                    <div class="info-widget d-flex align-items-center">
                        <a href="https://www.google.com/maps?q={{$vcard->address}}" class="icon icon-1" style="background: #{{$vcard->address_icon_color}}1a;">
                            <i class="fas fa-map-marker-alt" style="color: #{{$vcard->address_icon_color}};"></i>
                        </a>
                        <div class="content">
                            <span class="title">{{$keywords["Address"] ?? "Address"}}</span>
                            <h5><a href="https://www.google.com/maps?q={{$vcard->address}}">{{$vcard->address}}</a></h5>
                        </div>
                    </div>
                    @endif
                    @if (!empty($vcard->website_url))
                    <div class="info-widget d-flex align-items-center">
                        <a class="icon icon-1" href="{{$vcard->website_url}}" target="_blank" style="background: #{{$vcard->website_url_icon_color}}1a;">
                            <i class="fas fa-link" style="color: #{{$vcard->website_url_icon_color}};"></i>
                        </a>
                        <div class="content">
                            <span class="title">{{$keywords["Website_URL"] ?? "Website_URL"}}</span>
                            <h5><a href="{{$vcard->website_url}}" target="_blank">{{$vcard->website_url}}</a></h5>
                        </div>
                    </div>
                    @endif
                    @foreach ($infos as $info)
                        <div class="info-widget d-flex align-items-center">
                            @if ($info['link'] == 1) 
                            <a class="icon icon-1" href="{{$info['value']}}" target="_blank" style="background: #{{$info['color']}}1a;">
                                <i class="{{$info['icon']}}" style="color: #{{$info['color']}}"></i>
                            </a>
                            @else
                            <div class="icon icon-1" style="background: #{{$info['color']}}1a">
                                <i class="{{$info['icon']}}" style="color: #{{$info['color']}}"></i>
                            </div>
                            @endif
                            <div class="content">
                                <span class="title">{{$info['label']}}</span>
                                <h5><a @if ($info['link'] == 1) target="_blank" href="{{$info['value']}}" @endif>{{$info['value']}}</a></h5>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif


                @if (is_array($prefs) && in_array('About Us', $prefs))
                <div class="about-section-widget about-section-three">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title">
                                <h4>{{$keywords["About_us"] ?? "About us"}}</h4>
                            </div>
                        </div>
                    </div>
                    <div>
                        {!! $vcard->about !!}
                    </div>
                </div>
                @endif

                @if (is_array($prefs) && in_array('Services', $prefs))
                <div class="service-section-widget service-section-three">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title">
                                <h4>{{$keywords["Our_Service"] ?? "Our Service"}}</h4>
                            </div>
                        </div>
                    </div>
                    @php
                        $services = !empty($vcard->user_vcard_services) ? $vcard->user_vcard_services()->orderBy('serial_number', 'ASC')->get() : [];
                    @endphp
                    @foreach ($services as $service)
                        <div class="service-item mb-3">
                            <div class="service-img">
                                <img class="w-100 lazy" data-src="{{asset('assets/front/img/user/services/' . $service->image)}}" alt="service img">
                            </div>
                            <div class="content">
                                <h4>{{$service->title}}</h4>
                                <div class="button text-center">
                                    @if ($service->external_link_status == 1 && !empty($service->external_link))
                                    <a href="{{$service->external_link}}" target="_blank" class="main-btn btn-color-three">{{$keywords["Details"] ?? "Details"}}</a>
                                    @elseif ($service->external_link_status == 0 && !empty($service->short_details))
                                    <a href="#" data-toggle="modal" data-target="#serviceDetails{{$service->id}}" class="main-btn btn-color-three">{{$keywords["Details"] ?? "Details"}}</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($service->external_link_status == 0 && !empty($service->short_details))
                        <!-- Modal -->
                        <div class="modal fade" id="serviceDetails{{$service->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">{{$keywords["Details"] ?? "Details"}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    {{$service->short_details}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif

                @if (is_array($prefs) && in_array('Projects', $prefs))
                <div class="portfolio-section-widget portfolio-section-three">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="section-title">
                                <h4>{{$keywords["Projects"] ?? "Projects"}}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="portfolio-arrows"></div>
                        </div>
                    </div>
                    @php
                        $projects = !empty($vcard->user_vcard_projects) ? $vcard->user_vcard_projects()->orderBy('serial_number', 'ASC')->get() : [];
                    @endphp
                    @if (!empty($projects))
                    <div class="portfolio-slider-one">
                        @foreach ($projects as $project)
                        <div class="portfolio-item">
                            <div class="portfolio-img">
                                <img data-src="{{asset('assets/front/img/user/projects/' . $project->image)}}" alt="portfolio" class="w-100 lazy">
                                <a href="{{asset('assets/front/img/user/projects/' . $project->image)}}" class="portfolio-overlay img-popup">
                                    <div class="info">
                                        <h4 class="title">{{$project->title}}</h4>
                                    </div>
                                </a>
                            </div>
                            <div class="content text-center">
                                @if ($project->external_link_status == 1 && !empty($project->external_link))
                                <a href="{{$project->external_link}}" target="_blank" class="main-btn btn-color-three">Details</a>
                                @elseif ($project->external_link_status == 0 && !empty($project->short_details))
                                <a href="#" data-toggle="modal" data-target="#projectDetails" class="main-btn btn-color-three" data-details="{{$project->short_details}}">{{$keywords["Details"] ?? "Details"}}</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                <!-- Modal -->
                <div class="modal fade" id="projectDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{$keywords["Details"] ?? "Details"}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>

                @if (is_array($prefs) && in_array('Video', $prefs))
                <div class="video-section-widget video-section-three">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title">
                                <h4>{{$keywords["Video"] ?? "Video"}}</h4>
                            </div>
                        </div>
                    </div>
                    @if (!empty($vcard->video))
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{$vcard->video}}" allowfullscreen></iframe>
                    </div>
                    @endif
                </div>
                @endif

                @if (is_array($prefs) && in_array('Testimonials', $prefs))
                <div class="testimonial-section-widget testimonial-section-three">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="section-title">
                                <h4>{{$keywords["Testimonial"] ?? "Testimonial"}}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="testimonial-arrows"></div>
                        </div>
                    </div>
                    @php
                        $testimonials = !empty($vcard->user_vcard_testimonials) ? $vcard->user_vcard_testimonials()->orderBy('serial_number', 'ASC')->get() : [];
                    @endphp
                    @if (!empty($testimonials))
                    <div class="testimonial-slider-one">
                        @foreach ($testimonials as $testimonial)
                        <div class="testimonial-item">
                            <div class="title-thumb d-flex align-items-center">
                                <div class="thumb">
                                    <img class="lazy" data-src="{{asset('assets/front/img/user/testimonials/' . $testimonial->image)}}" alt="thumb">
                                </div>
                                <div class="title">
                                    <h5>{{$testimonial->name}}</h5>
                                    <ul class="rating">
                                        @for ($i = 0; $i < $testimonial->rating; $i++)
                                        <li><i class="fas fa-star"></i></li>
                                        @endfor
                                        @for ($i = 0; $i < (5 - $testimonial->rating); $i++)
                                        <li><i class="far fa-star"></i></li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                            <div class="content">
                                <p>{{$testimonial->comment}}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                @if (is_array($prefs) && in_array('Enquiry Form', $prefs))
                <div class="contact-section-widget contact-section-three">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title">
                                <h4>{{$keywords["Enquiry_Form"] ?? "Enquiry_Form"}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="contact-form">
                        <form action="{{route('front.contact.message', getParam())}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$vcard->user_id}}">
                            <input type="hidden" name="type" value="vcard">
                            <input type="hidden" name="to_name" value="{{$vcard->name}}">
                            <input type="hidden" name="to_mail" value="{{$vcard->email}}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form_group">
                                        <input type="text" class="form_control" placeholder="{{$keywords["Enter_Name"] ?? "Enter_Name"}}" name="fullname" required>
                                        @if ($errors->has('fullname'))
                                            <p class="text-danger mb-0">{{$errors->first('fullname')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form_group">
                                        <input type="email" class="form_control" placeholder="{{$keywords["Enter_Email_Address"] ?? "Enter_Email_Address"}}" name="email" required>
                                        @if ($errors->has('email'))
                                            <p class="text-danger mb-0">{{$errors->first('email')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form_group">
                                        <input type="text" class="form_control" placeholder="{{$keywords["Enter_Subject"] ?? "Enter_Subject"}}" name="subject" required>
                                        @if ($errors->has('subject'))
                                            <p class="text-danger mb-0">{{$errors->first('subject')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form_group">
                                        <textarea class="form_control" placeholder="{{$keywords["Enter_Message"] ?? "Enter_Message"}}" name="message"></textarea>
                                        @if ($errors->has('message'))
                                            <p class="text-danger mb-0">{{$errors->first('message')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form_group">
                                        <button class="main-btn btn-color-three" type="submit">{{$keywords["Send"] ?? "Send"}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div><!--====== End Page Wrapper ======-->
    <!--====== Shape Curve SVG ======-->
    <svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
        <defs>
            <filter id="radius"><feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur" />    
                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 25 -10" result="goo" />
                <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
            </filter>
        </defs>
    </svg>
@endsection