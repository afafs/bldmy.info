@extends('user.profile1.theme5.layout')

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
    <section class="blog-area pt-120 pb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        @foreach($blogs as $blog)
                            <div class="col-lg-6">
                                <div class="blog-post-item blog-post-item-one mb-40">
                                    <a class="post-thumbnail d-block" href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">
                                        <img class="lazy" data-src="{{asset('assets/front/img/user/blogs/'.$blog->image)}}" alt="Blog Image">
                                    </a>
                                    <div class="entry-content">
                                        <h3 class="title"><a href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">{{strlen($blog->title) > 45 ? mb_substr($blog->title, 0, 45, 'UTF-8') . '...' : $blog->title}}</a></h3>
                                        <div class="post-meta">
                                            <ul>
                                                <li><span><i class="fas fa-user"></i>{{$keywords['by'] ?? "by"}} <a>{{$user->username}}</a></span></li>
                                                <li><span><i class="fas fa-calendar-alt"></i><a>{{\Carbon\Carbon::parse($blog->created_at)->format('F j, Y')}}</a></span></li>
                                            </ul>
                                        </div>
                                        <p>{!! strlen(strip_tags($blog->content)) > 100 ? mb_substr(strip_tags($blog->content), 0, 100, 'utf-8') . '...' : strip_tags($blog->content) !!}</p>
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
    </section>
@endsection