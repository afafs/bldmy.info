@extends('user.profile1.theme3.layout')

@section('tab-title')
{{$keywords["Blogs"] ?? "Blogs"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->blogs_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->blogs_meta_keywords : '')

@section('content')
    <!--====== Start Main Wrapper ======-->
    <div class="main-wrapper inner">
        <section class="blog-area pt-120 pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="section-title mb-50">
                            <span class="sub-title">{{$home_text->blog_title ?? __('Blogs')}}</span>
                            <h2><span class="light-text">{{$home_text->blog_subtitle ?? "Blogs"}}</span></h2>
                        </div>
                    </div>
                </div>
                <div class="blog-slider-one">
                    <div class="row">
                        @foreach($blogs as $blog)
                            <div class="col-lg-4 col-md-6">
                                <div class="blog-post-item">
                                    <a class="post-thumbnail" href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">
                                        <img class="lazy" data-src="{{asset('assets/front/img/user/blogs/'.$blog->image)}}" alt="Blog Image">
                                        <div class="post-date">
                                            <span class="date">{{\Carbon\Carbon::parse($blog->created_at)->format('d')}}<span class="month">{{\Carbon\Carbon::parse($blog->created_at)->format('M')}}</span></span>
                                            <div class="icon">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="entry-content text-center">
                                        <div class="post-meta">
                                            <ul>
                                                <li><span><i class="fas fa-user"></i> {{$keywords['by'] ?? "by"}} <a>{{$user->username}}</a></span></li>
                                            </ul>
                                        </div>
                                        <h3 class="title"><a href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">{{strlen($blog->title) > 45 ? mb_substr($blog->title, 0, 45, 'UTF-8') . '...' : $blog->title}}</a></h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center">
                        {{$blogs->appends(['category' => request()->input('category'), 'term' => request()->input('term')])->links()}}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection