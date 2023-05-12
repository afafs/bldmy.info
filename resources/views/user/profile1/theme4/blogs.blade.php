@extends('user.profile1.theme4.layout')

@section('tab-title')
{{$keywords["Blogs"] ?? "Blogs"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->blogs_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->blogs_meta_keywords : '')

@section('br-title')
{{$keywords["Blogs"] ?? "Blogs"}}
@endsection
@section('br-link')
{{$keywords["Blogs"] ?? "Blogs"}}
@endsection

@section('content')
    <!--====== Start Page Content ======-->
    <section class="page-content-section pt-115 pb-115">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-area">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row">
                                    @foreach($blogs as $blog)
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="blog-post-item mb-40">
                                                <a class="post-thumbnail d-block" href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">
                                                    <img class="lazy" data-src="{{asset('assets/front/img/user/blogs/'.$blog->image)}}" alt="Blog Image">
                                                </a>
                                                <div class="entry-content">
                                                    <div class="post-meta">
                                                        <ul>
                                                            <li><span><i class="fas fa-user"></i>{{$keywords['by'] ?? "by"}} <a>{{$user->username}}</a></span></li>
                                                            <li><span><i class="fas fa-calendar-alt"></i><a>{{\Carbon\Carbon::parse($blog->created_at)->format('F j, Y')}}</a></span></li>
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
                            <div class="col-lg-4">
                                @includeIf('user.profile-common.blog-sidebar')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Page Content ======-->
@endsection