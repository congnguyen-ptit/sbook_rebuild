@extends('layout.app')
{{ Html::style('assets/user/css/search-page.css') }}
@section('header')
    @parent
    <div class="breadcrumbs-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="breadcrumbs-menu">
                        <ul>
                            <li><a href="{{ route('home') }}">{{ trans('settings.default.home') }}</a></li>
                            <li><a class="active">{{ trans('settings.header.search') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="breadcrumbs-menu">
                        <ul id="search-page">
                            <li>{{ trans('page.searchBy') }}</li>
                            <li class="active">
                                <input class="cursor" id="tab-title" name="tab-type" checked type="radio" data-target="#title" value="{{ trans('page.summary') }}">
                                <label class="cursor" for="tab-title">{{ trans('page.summary') }}</label>
                            </li>
                            <li>
                                <input class="cursor" id="tab-author" name="tab-type" type="radio" data-target="#author" value="{{ trans('page.book.author') }}">
                                <label class="cursor" for="tab-author">{{ trans('page.book.author') }}</label>
                            </li>
                            <li>
                                <input class="cursor" id="tab-desc" name="tab-type" type="radio" data-target="#description" value="{{ trans('page.book.description') }}">
                                <label class="cursor" for="tab-desc">{{ trans('page.book.description') }}</label>
                            </li>
                            <li>
                                <input class="cursor" id="tab-users" name="tab-type" type="radio" data-target="#users" value="{{ trans('settings.admin.layout.users') }}">
                                <label class="cursor" for="tab-users">{{ trans('settings.admin.layout.users') }}</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="search__append">
        @include('layout.section.search_page')
    </div>
@endsection

@section('footer')
    @parent
@endsection
