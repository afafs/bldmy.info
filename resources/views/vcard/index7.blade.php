@extends('vcard.layout')

@if ($vcard->direction == 2)
    @section('rtl', 'dir=rtl')
@endif

@if ($vcard->direction == 2)
@section('rtl-css')
<link rel="stylesheet" href="{{asset('assets/front/css/profile/vcard/template5-10/rtl-style.css')}}">
@endsection
@endif

@section('base-color')
<link rel="stylesheet" href="{{asset('assets/front/css/profile/vcard/template5-10/vcard-base-color.php').'?color='.$vcard->base_color}}">
@endsection

@section('og-image', asset('assets/front/img/user/vcard/' . $vcard->cover_image))
@section('og-title')
    {{$vcard->name}} ({{$vcard->occupation}})
@endsection
@section('og-description')
    {{$vcard->introduction}}
@endsection
@section('styles')
<style>
    .admin-box-two:before {
        background: url({{!empty($vcard->cover_image) ? asset('assets/front/img/user/vcard/' . $vcard->cover_image) : asset('assets/front/img/user/vcard/vcard_cover.jpg')}});
    }
</style>
@endsection

@section('content')
    <!--====== Start Page Wrapper ======-->
    <div class="page-wrapper dark-page">
        <div class="container p-0">
            <!--====== Page Area Start ======-->
            <div class="page-area page-area-three">
                <!--====== Start Admin  ======-->
                <div class="admin-box-three bg_cover lazy" data-bg="{{!empty($vcard->cover_image) ? asset('assets/front/img/user/vcard/' . $vcard->cover_image) : asset('assets/front/img/user/vcard/vcard_cover.jpg')}}">
                    <div class="admin-info-box text-center">
                        <div class="user-info">
                            <div class="user-title">
                                <h3>{{$vcard->name}}</h3>
                                @if (!empty($vcard->occupation))
                                <p class="position">
                                    {{$vcard->occupation}}
                                    @if (!empty($vcard->company))
                                    ({{$vcard->company}})
                                    @endif
                                </p>
                                @endif
                            </div>
                            <p>{{$vcard->introduction}}</p>
                        </div>
                    </div>
                    <div class="user-thumb">
                        <img class="lazy" data-src="{{!empty($vcard->profile_image) ? asset('assets/front/img/user/vcard/' . $vcard->profile_image) : asset('assets/front/img/user/blank_propic.png')}}" alt="user thumb">
                    </div>
                </div><!--====== End Admin  ======-->
                <!--====== Start User Social ======-->
                <div class="user-social user-social-one text-center">
                    <ul class="d-flex">
                        @if (!empty($vcard->phone) && (is_array($prefs) && in_array('Call', $prefs)))
                        <li><a style="background: #{{$vcard->call_button_color}}" href="tel:{{$vcard->phone}}"><i class="fas fa-phone"></i></a></li>
                        @endif
                        @if (!empty($vcard->phone) && (is_array($prefs) && in_array('Whatsapp', $prefs)))
                        <li><a style="background: #{{$vcard->whatsapp_button_color}}" href="https://wa.me/{{$vcard->phone}}?text=Hello"><i class="fab fa-whatsapp"></i></a></li>
                        @endif
                        @if (!empty($vcard->email) && (is_array($prefs) && in_array('Mail', $prefs)))
                        <li><a style="background: #{{$vcard->mail_button_color}}" href="mailto:{{$vcard->email}}"><i class="fas fa-envelope"></i></a></li>
                        @endif
                        @if (is_array($prefs) && in_array('Share vCard', $prefs))
                        <li><a href="#" style="background: #{{$vcard->share_vcard_button_color}}" data-toggle="modal" data-target="#socialMediaModal" class="share-btn purple-bg"><i class="fas fa-share-alt"></i></a></li>
                        @endif
                    </ul>
                    @if (!empty($vcard->phone) && (is_array($prefs) && in_array('Add to Contact', $prefs)))
                    <a style="background: #{{$vcard->add_to_contact_button_color}}" href="{{route('front.user.vcardImport', [getParam(), $vcard->id])}}" class="user-btn"><i class="fas fa-plus"></i>{{$keywords["Add_to_Contact"] ?? "Add to Contact"}}</a>
                    @endif
                </div><!--====== End User Social ======-->

                @if (is_array($prefs) && in_array('Information', $prefs))
                    <!--====== Start Contact-information ======-->
                    <div class="contact-info-three">
                        @if (!empty($vcard->phone))
                        <div class="info-box d-flex">
                            <div class="icon" style="color: #{{$vcard->phone_icon_color}}">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="text">
                                <h3>{{$keywords["Phone"] ?? "Phone"}}</h3>
                                <p><a href="tel:{{$vcard->phone}}">{{$vcard->phone}}</a></p>
                            </div>
                        </div>
                        @endif
                        @if (!empty($vcard->email))
                        <div class="info-box d-flex">
                            <div class="icon" style="color: #{{$vcard->email_icon_color}}">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="text">
                                <h3>{{$keywords["Email"] ?? "Email"}}</h3>
                                <p><a href="mailto:{{$vcard->email}}">{{$vcard->email}}</a></p>
                            </div>
                        </div>
                        @endif
                        @if (!empty($vcard->address))
                        <div class="info-box d-flex">
                            <div class="icon" style="color: #{{$vcard->address_icon_color}}">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="text">
                                <h3>{{$keywords["Address"] ?? "Address"}}</h3>
                                <p>{{$vcard->address}}</p>
                            </div>
                        </div>
                        @endif
                        @if (!empty($vcard->website_url))
                        <div class="info-box d-flex">
                            <div class="icon" style="color: #{{$vcard->website_url_icon_color}}">
                                <i class="fas fa-link"></i>
                            </div>
                            <div class="text">
                                <h3>{{$keywords["Website_URL"] ?? "Website URL"}}</h3>
                                <p><a href="{{$vcard->website_url}}">{{$vcard->website_url}}</a></p>
                            </div>
                        </div>
                        @endif
                        @foreach ($infos as $info)
                            <div class="info-box d-flex">
                                <div class="icon" style="color: #{{$info['color']}};">
                                    <i class="{{$info['icon']}}"></i>
                                </div>
                                <div class="text">
                                    <h3>{{$info['label']}}</h3>
                                    <p><a @if ($info['link'] == 1) target="_blank" href="{{$info['value']}}" @endif>{{$info['value']}}</a></p>
                                </div>
                            </div>
                        @endforeach
                    </div><!--====== End Contact-information ======-->
                @endif

                @if (is_array($prefs) && in_array('About Us', $prefs))
                    <!--====== Start About ======-->
                    <div class="section-widget about-area-three mb-25">
                        <h3 class="widget-title">{{$keywords["About_us"] ?? "About us"}}</h3>
                        <div class="about-content">
                            {!! $vcard->about !!}
                        </div>
                    </div><!--====== End About ======-->
                @endif

                @if (is_array($prefs) && in_array('Services', $prefs))
                    <!--====== Start Service ======-->
                    <div class="section-widget service-area-three mb-25">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="widget-title">{{$keywords["Our_Service"] ?? "Our Service"}}</h3>
                            </div>
                            <div class="col-4">
                                <div class="service-arrows"></div>
                            </div>
                        </div>
                        <div class="service-slider-one">
                            @php
                                $services = !empty($vcard->user_vcard_services) ? $vcard->user_vcard_services()->orderBy('serial_number', 'ASC')->get() : [];
                            @endphp
                            @foreach ($services as $service)
                                @if ($service->external_link_status == 1 && !empty($service->external_link))

                                <a class="d-block service-item" href="{{$service->external_link}}" target="_blank">

                                @elseif ($service->external_link_status == 0 && !empty($service->short_details))

                                <a href="#" data-toggle="modal" data-target="#serviceDetails" class="d-block no-ext service-item" data-details="{{$service->short_details}}">

                                @endif
                                    <div class="img-holder">
                                        <img data-src="{{asset('assets/front/img/user/services/' . $service->image)}}" alt="Image" class="lazy w-100">
                                        <div class="img-overlay"></div>
                                    </div>
                                    <div class="text text-center">
                                        <h3>{{$service->title}}</h3>
                                    </div>
                                </a>
                            @endforeach
                            
                        </div>
                    </div><!--====== End Service ======-->

                    <!-- Modal -->
                    <div class="modal fade" id="serviceDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{$keywords["Details"] ?? "Details"}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body"></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (is_array($prefs) && in_array('Projects', $prefs))
                    <!--====== Start Project ======-->
                    <div class="section-widget project-area-three mb-25">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="widget-title">{{$keywords["Projects"] ?? "Projects"}}</h3>
                            </div>
                            <div class="col-4">
                                <div class="project-arrows"></div>
                            </div>
                        </div>
                        @php
                            $projects = !empty($vcard->user_vcard_projects) ? $vcard->user_vcard_projects()->orderBy('serial_number', 'ASC')->get() : [];
                        @endphp
                        @if (!empty($projects))
                            <div class="project-slider-two">
                                @foreach ($projects as $project)
                                    <div class="project-item">
                                        <div class="img-holder">
                                            <img data-src="{{asset('assets/front/img/user/projects/' . $project->image)}}" alt="portfolio" class="w-100 lazy">
                                            <a href="{{asset('assets/front/img/user/projects/' . $project->image)}}" class="img-popup img-overlay"></a>
                                        </div>
                                        <div class="text text-center">
                                            <h3>{{$project->title}}</h3>
                                            @if ($project->external_link_status == 1 && !empty($project->external_link))
                                                <a href="{{$project->external_link}}" target="_blank" class="btn_link">{{$keywords["Details"] ?? "Details"}}</a>
                                            @elseif ($project->external_link_status == 0 && !empty($project->short_details))
                                                <a href="#" data-toggle="modal" data-target="#projectDetails" class="btn_link" data-details="{{$project->short_details}}">{{$keywords["Details"] ?? "Details"}}</a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div><!--====== End Project ======-->

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
                @endif

                @if (is_array($prefs) && in_array('Video', $prefs))
                <!--====== Start Video ======-->
                <div class="section-widget intro-area-three mb-25">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="widget-title">{{$keywords["Video"] ?? "Video"}}</h3>
                        </div>
                    </div>
                    @if (!empty($vcard->video))
                        <div class="intro-item">
                            <div class="img-holder">
                                <iframe class="embed-responsive-item" src="{{$vcard->video}}" allowfullscreen width="100%" height="220"></iframe>
                            </div>
                        </div>
                    @endif
                </div><!--====== End Video ======-->
                @endif

                @if (is_array($prefs) && in_array('Testimonials', $prefs))
                    <!--====== Start Testimonial ======-->
                    <div class="section-widget testimonial-area-three mb-25">
                        @php
                            $testimonials = !empty($vcard->user_vcard_testimonials) ? $vcard->user_vcard_testimonials()->orderBy('serial_number', 'ASC')->get() : [];
                        @endphp
                        @if (!empty($testimonials))
                            <div class="testimonial-slider-two">
                                @foreach ($testimonials as $testimonial)
                                    <div class="testimonial-item">
                                        <div class="testimonial-content text-center">
                                            <div class="author-thumb d-inline-flex">
                                                <img data-src="{{asset('assets/front/img/user/testimonials/' . $testimonial->image)}}" class="lazy" alt="">
                                            </div>
                                            <div class="author-title">
                                                <h3>{{$testimonial->name}}</h3>
                                            </div>
                                            <p>{{$testimonial->comment}}</p>
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
                                @endforeach
                            </div>
                        @endif
                    </div><!--====== End Testimonial ======-->
                @endif

                @if (is_array($prefs) && in_array('Enquiry Form', $prefs))
                    <!--====== Start Contact ======-->
                    <div class="section-widget contact-area-three mb-25">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="widget-title">{{$keywords["Enquiry_Form"] ?? "Enquiry_Form"}}</h3>
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
                                            <button class="form-btn" type="submit">{{$keywords["Send"] ?? "Send"}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!--====== End Contact ======-->
                @endif
            </div><!--====== Page Area End ======-->
        </div>
    </div><!--====== End Page Wrapper ======-->
@endsection