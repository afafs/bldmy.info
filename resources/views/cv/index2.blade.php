@extends('cv.layout')

@if ($cv->direction == 2)
    @section('rtl', 'dir=rtl')
@endif

@section('main-css')
<link rel="stylesheet" href="{{asset('assets/front/css/profile/cv2-base-color.php') . '?color=' . $cv->base_color}}">
<link rel="stylesheet" href="{{asset('assets/front/css/profile/cv2.css')}}">
@if ($cv->direction == 2)
<link rel="stylesheet" href="{{asset('assets/front/css/profile/cv2-rtl.css')}}">
@endif
@endsection

@section('content')
<button class="btn btn-primary printBtn btn-sm" onclick="printCv()"><i class="fas fa-file-export mr-1"></i> Export PDF</button>
<section class="cv-page">
    <div class="container">
        <div class="cv-wrapper" id="cvWrapper">
            <div class="cv-header">
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <div class="user-thumb">
                            <img src="{{asset('assets/front/img/user/cv/' . $cv->image)}}" alt="User Thumbnail">
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="user-content">
                            <div class="title">
                                <h3>{!! $cv->name !!}</h3>
                                <span class="span">{!! $cv->occupation !!}</span>
                            </div>

                            @if (!empty($infos))
                            <ul class="user-info d-flex justify-content-between">
                                @foreach ($infos as $info)
                                    <li>
                                        <div class="icon">
                                            <i class="{{$info['icon']}}" style="background-color: #{{$info['color']}}"></i>
                                        </div>
                                        <div class="info">
                                            <h5>{!! $info['content'] !!}</h5>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="cv-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="user-info-wrapper">
                            @if (!empty($sections))
                                @foreach ($sections as $section)
                                <div class="info-box-item">
                                    <page-break>
                                        <div class="title">
                                            <h5><i class="{{$section->icon}}" style="color: #{{$cv->base_color}}"></i>{!! $section->name !!}</h5>
                                        </div>
                                    </page-break>
                                    @php
                                        $contents = json_decode($section->content, true);
                                    @endphp

                                    @if (!empty($contents))
                                    <div class="content">
                                        @foreach ($contents as $content)
                                            <page-break>
                                                <div class="single-timeline">
                                                    @if (!empty($content['duration']))
                                                    <h6><span class="date">{!! $content['duration'] !!}</span></h6>
                                                    @endif
                                                    <div class="timeline-content {{$content['left_border'] == 0 ? 'no-left-border' : ''}}">
                                                        @if (!empty($content['title']))
                                                        <h5>{!! $content['title'] !!}</h5>
                                                        @endif
                                                        @if (!empty($content['subtitle']))
                                                        <span class="position">{!! $content['subtitle'] !!}</span>
                                                        @endif
                                                        @if (!empty($content['description']))
                                                            <p>{!! nl2br($content['description']) !!}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if (array_key_exists('newLines', $content))
                                                    @for($i=0; $i < $content['newLines']; $i++)
                                                    <br>
                                                    @endfor
                                                @endif
                                            </page-break>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@php
    $username = $cv->user->username;
@endphp
@section('scripts')
<script>
    function printCv() {
        // Get the element.
        var element = document.getElementById('cvWrapper');

        // Generate the PDF.
        html2pdf().from(element).set({
            filename: "{{$username}}" + ".pdf",
            jsPDF: {orientation: 'portrait', unit: 'in', format: 'letter', compressPDF: true},
            pagebreak: { before: '.beforeClass', after: ['#after1', '#after2'], avoid: 'page-break' }
        }).save();
    }
</script>
@endsection