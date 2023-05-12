@extends('user.profile1.theme3.layout')

@section('tab-title')
{{$keywords["Experience"] ?? "Experience"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->experience_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->experience_meta_keywords : '')

@section('content')
    <!--====== Start Main Wrapper ======-->
    <div class="main-wrapper">
        <section class="resume-area pt-80 pb-45">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-title mb-30">
                            <span class="sub-title">{{$home_text->experience_title ?? __('Experience')}}</span>
                            <h2><span class="light-text">{{$home_text->experience_subtitle ?? __('Experience')}}</span></h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if (count($educations) > 0)
                        <div class="col-lg-6">
                            <div class="resume-title mb-20">
                                <h4 class="title"><i class="fas fa-user-graduate"></i>{{$keywords["Education"] ?? "Education"}}</h4>
                            </div>
                            <div class="resume-wrapper">
                                @foreach($educations as $education)
                                    <div class="resume-item mb-35">
                                        <div class="resume-inner-item">
                                            <div class="resume-content">
                                                <h5>{{$education->degree_name}}</h5>
                                                <span class="duration">
                                                    {{\Carbon\Carbon::parse($education->start_date)->format('M j, Y')}}
                                                    -
                                                    @if (!empty($education->end_date))
                                                        {{ \Carbon\Carbon::parse($education->end_date)->format('M j, Y') }}
                                                    @else
                                                        {{$keywords["Present"] ?? "Present"}}
                                                    @endif
                                                </span>
                                                <p>{!! nl2br($education->short_description) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if (count($job_experiences) > 0)
                        <div class="col-lg-6">
                            <div class="resume-title mb-20">
                                <h4 class="title"><i class="fas fa-briefcase"></i>{{$keywords["Job"] ?? "Job"}}</h4>
                            </div>
                            <div class="resume-wrapper">
                                @foreach($job_experiences as $job_experience)
                                    <div class="resume-item mb-35">
                                        <div class="resume-inner-item">
                                            <div class="resume-content">
                                                <h5>{{$job_experience->designation}} [{{$job_experience->company_name}}]</h5>
                                                <span class="duration">
                                                    {{\Carbon\Carbon::parse($job_experience->start_date)->format('M j, Y')}} - 
                                                    @if ($job_experience->is_continue == 0)
                                                    {{ \Carbon\Carbon::parse($job_experience->end_date)->format('M j, Y') }}
                                                    @else
                                                    {{$keywords["Present"] ?? "Present"}}
                                                    @endif
                                                </span>
                                                <p>{!! nl2br($job_experience->content) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div><!--====== End Main Wrapper ======-->
@endsection