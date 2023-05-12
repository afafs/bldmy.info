<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<title>{{$bs->website_title}} - User Dashboard</title>
	<link rel="icon" href="{{!empty($userBs->favicon) ? asset('assets/front/img/user/'.$userBs->favicon) : ''}}">
	@includeif('user.partials.styles')
    @php
         $selLang = App\Models\User\Language::where('code', request()->input('language'))->where('user_id',Auth::guard('web')->user()->id)->first();
         $currentLang = App\Models\User\Language::where('code',Session::get('currentLangCode'))->where('user_id',Auth::guard('web')->user()->id)->first();
    @endphp
    @if (!empty($selLang) && $selLang->rtl == 1)
    <style>
    #editModal form input,
    #editModal form textarea,
    #editModal form select {
        direction: rtl;
    }
    #editModal form .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
    </style>
    @endif

    @if (!empty($selLang) && $selLang->rtl == 1 )
    <!--====== RTL Style css ======-->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-rtl.css') }}">
    @endif

     @if($currentLang->code !== 'ar')
       <style>
        .navbar-expand-lg .navbar-nav .dropdown-menu {
            left: auto;
            right: 0;
        }
    </style>
     @endif

    @if($currentLang->rtl == 1 && $selLang == null)
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-rtl.css') }}">
    @endif

</head>

<body @if(request()->cookie('user-theme') == 'dark') data-background-color="dark" @endif>
	<div class="wrapper">

    {{-- top navbar area start --}}
    @includeif('user.partials.top-navbar')
    {{-- top navbar area end --}}


    {{-- side navbar area start --}}
    @includeif('user.partials.side-navbar')
    {{-- side navbar area end --}}


		<div class="main-panel">
        <div class="content">
            <div class="page-inner">
            @yield('content')
            </div>
        </div>
            @includeif('user.partials.footer')
		</div>

	</div>

      @php
    $user = Auth::guard('web')->user();
     $holidays = App\Models\User\UserHoliday::where('user_id', $user->id)
            ->pluck('date')
            ->toArray();
        $dats = [];
        foreach ($holidays as $value) {
            $dats[] = Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        $holidays = $dats;
        $weekends = App\Models\User\UserDay::where('user_id', $user->id)
            ->where('weekend', 1)
            ->pluck('index')
            ->toArray();
    @endphp

     <script>
        var $holidays = '<?php echo json_encode($holidays); ?>'
        var $weekends = '<?php echo json_encode($weekends); ?>'
    
    </script>

	@includeif('user.partials.scripts')

    {{-- Loader --}}
    <div class="request-loader">
        <img src="{{asset('assets/admin/img/loader.gif')}}" alt="">
    </div>
    {{-- Loader --}}
</body>
</html>
