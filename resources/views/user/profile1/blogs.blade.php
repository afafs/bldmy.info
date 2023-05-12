@extends('user.profile1.layout')

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
    <section class="latest-blog-section inner-section-gap" id="blog">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        @foreach($blogs as $blog)
                        <div class="col-md-6">
                            <div class="latest-blog-post mb-30">
                                <div class="post-content">
                                    <h5 class="post-title">
                                        <a href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">{{strlen($blog->title) > 45 ? mb_substr($blog->title, 0, 45, 'UTF-8') . '...' : $blog->title}}</a>
                                    </h5>
                                    <ul class="post-meta">
                                        <li><a><i class="fas fa-user"></i> {{$keywords['by'] ?? "by"}} {{$user->username}}</a></li>
                                        <li><a><i class="fas fa-calendar-alt"></i> {{\Carbon\Carbon::parse($blog->created_at)->format('F j, Y')}}</a></li>
                                    </ul>
                                </div>
                                <a class="post-thumb" href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">
                                    <img class="lazy" data-src="{{asset('assets/front/img/user/blogs/'.$blog->image)}}" alt="Image">
                                </a>
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
    <!--====== End Page Content ======-->    
@endsection