@extends('cv.layout')

@if ($cv->direction == 2)
    @section('rtl', 'dir=rtl')
@endif

@section('main-css')
<link rel="stylesheet" href="{{asset('assets/front/css/profile/cv1-base-color.php') . '?color=' . $cv->base_color}}">
<link rel="stylesheet" href="{{asset('assets/front/css/profile/cv1.css')}}">
@if ($cv->direction == 2)
<link rel="stylesheet" href="{{asset('assets/front/css/profile/cv1-rtl.css')}}">
@endif
@endsection

@section('content')
<button class="btn btn-primary printBtn btn-sm" onclick="printCv()"><i class="fas fa-file-export mr-1"></i> Export PDF</button>
<section class="cv-page" id="first-page">
    <div class="container">
        <div class="cv-wrapper" id="cvWrapper">
            <div class="row">
                <div class="col-lg-4">
                    <div class="user-info-wrapper">
                        <div class="user-box text-center">
                            <div class="title">
                                <h4>{!! $cv->name !!}</h4>
                                <span class="position">{!! $cv->occupation !!}</span>
                            </div>
                            <div class="user-thumb">
                                <img src="{{asset('assets/front/img/user/cv/' . $cv->image)}}" alt="User Thumbnail">
                            </div>
                        </div>

                        @if (!empty($infos))
                        <div class="info-box-item">
                            <div class="title">
                                <h5><i class="fas fa-address-card"></i>{!! $cv->contact_title !!}</h5>
                            </div>
                            <div class="content">
                                <ul class="info-list">
                                    @foreach ($infos as $info)
                                        <page-break>
                                            <li><i class="{{$info['icon']}}" style="color: #{{$info['color']}}"></i><span>{!! $info['content'] !!}</span></li>
                                        </page-break>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        @if (!empty($lsections))
                            @foreach ($lsections as $lsection)
                                <div class="info-box-item">
                                    <page-break>
                                        <div class="title">
                                            <h5><i class="{{$lsection->icon}}"></i>{!! $lsection->name !!}</h5>
                                        </div>
                                    </page-break>

                                    @php
                                        $contents = json_decode($lsection->content, true);
                                    @endphp
                                    @if (!empty($contents))
                                        <div class="content">
                                            @foreach ($contents as $content)
                                                <page-break>
                                                    <div class="single-timeline {{$content['left_border'] == 0 ? 'no-left-border' : ''}}">
                                                        <div class="timeline-content">
                                                            @if (!empty($content['duration']))
                                                                <span class="year">{!! $content['duration'] !!}</span>
                                                            @endif
                                                            @if (!empty($content['title']) || !empty($content['subtitle']))
                                                                <p>
                                                                    <span>{!! $content['title'] ?? '' !!}{{!empty($content['title']) && !empty($content['subtitle']) ? ', ' : ''}}{!! $content['subtitle'] ?? '' !!}</span>
                                                                </p>
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
                <div class="col-lg-8">
                    <div class="user-info-wrapper">
                        @if (!empty($rsections))
                            @foreach ($rsections as $rsection)
                                <div class="info-box-item">
                                    <page-break>
                                        <div class="title">
                                            <h5><i class="{{$rsection->icon}}"></i>{!! $rsection->name !!}</h5>
                                        </div>
                                    </page-break>

                                    @php
                                        $contents = json_decode($rsection->content, true);
                                    @endphp
                                    @if (!empty($contents))
                                        <div class="content">
                                            @foreach ($contents as $content)
                                                <page-break>
                                                    <div class="single-timeline {{$content['left_border'] == 0 ? 'no-left-border' : ''}}">
                                                        <div class="timeline-content">
                                                            @if (!empty($content['duration']))
                                                                <span class="year">{!! $content['duration'] !!}</span>
                                                            @endif
                                                            @if (!empty($content['title']) || !empty($content['subtitle']))
                                                                <p>
                                                                    <span>{!! $content['title'] ?? '' !!}{{!empty($content['title']) && !empty($content['subtitle']) ? ', ' : ''}}{!! $content['subtitle'] ?? '' !!}</span>
                                                                </p>
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