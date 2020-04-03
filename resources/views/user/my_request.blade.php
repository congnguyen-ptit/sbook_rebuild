@extends('layout.app')

@section('header')
    @parent
    <!-- breadcrumbs-area-start -->
    <div class="breadcrumbs-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-menu">
                        <ul>
                            <li><a href="{{ asset('/') }}">{{ __('settings.default.home') }}</a></li>
                            <li><a href="#" class="active">{{ __('settings.req') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->
    <!-- entry-header-area-start -->
    <div class="entry-header-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header-title">
                        <h2>{{ __('settings.request.myReq') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right" role="group" aria-label="Vertical button group">
                        <a href="{{ route('my-request.index') }}" class="btn btn-sm {{ ($_GET['types'] ?? false) ? 'btn-outline-dark' : 'btn-success' }}" id="all-data">
                            @lang('settings.book.all') {{ $countTypes[config('view.request.all')]}}
                        </a>
                        <button
                            class="btn btn-sm sort-type {{ selectedRequest($_GET['types'] ?? false, config('view.request.waiting')) ? 'btn-success' : 'btn-outline-dark' }}"
                            data-type="{{ config('view.request.waiting') }}">
                            @lang('settings.book.waiting') {{ $countTypes[config('view.request.waiting')]}}
                        </button>
                        <button
                            class="btn btn-sm sort-type {{ selectedRequest($_GET['types'] ?? false, config('view.request.reading')) ? 'btn-success' : 'btn-outline-dark' }}"
                            data-type="{{ config('view.request.reading') }}">
                            @lang('settings.book.reading') {{ $countTypes[config('view.request.reading')]}}
                        </button>
                        <button
                            class="btn btn-sm sort-type {{ selectedRequest($_GET['types'] ?? false, config('view.request.returning')) ? 'btn-success' : 'btn-outline-dark' }}"
                            data-type="{{ config('view.request.returning') }}">
                            @lang('settings.book.returning') {{ $countTypes[config('view.request.returning')]}}
                        </button>
                        <button
                            class="btn btn-sm sort-type {{ selectedRequest($_GET['types'] ?? false, config('view.request.returned')) ? 'btn-success' : 'btn-outline-dark' }}"
                            data-type="{{ config('view.request.returned') }}">
                            @lang('settings.book.returned') {{ $countTypes[config('view.request.returned')]}}
                        </button>
                        <button
                            class="btn btn-sm sort-type {{ selectedRequest($_GET['types'] ?? false, config('view.request.abtExpire')) ? 'btn-success' : 'btn-outline-dark' }}"
                            data-type="{{ config('view.request.abtExpire') }}">
                            @lang('settings.book.abtExpire') {{ $countTypes[config('view.request.abtExpire')]}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->
@endsection

@section('content')
<!-- cart-main-area-start -->
<div class="cart-main-area mb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('layout.notification')
                <div id="result-req">
                    @include('user.requests')
                </div>
            </div>
        </div>
    </div>
</div>
<!-- cart-main-area-end -->
@endsection

@section('footer')
    @parent
@endsection

@section('script')
    @parent
    {{ Html::script('assets/user/js/my_request.js') }}
@endsection
