@extends('layout.app')

@section('header')
    @parent
    <div class="breadcrumbs-area mb-70">
        <div class="container">
            <div class="fb-profile">
                <img align="left" class="fb-image-lg" src="{{ asset(config('view.image_paths.banner') . '32.jpg') }}" alt="banner"/>
                <img align="left" src="{{ $user->avatar ? asset($user->avatar) : asset(config('view.image_paths.user') . '1.png') }}" alt="avatar" class="fb-avatar-profile thumbnail" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                <div class="fb-profile-text floatleft">
                    @if ($user)
                        <h1 class="name-avatar">{{ $user->name }}</h1>
                    @endif
                    @if (in_array($user->id, $followingIds))
                        <button data-id="{{ $user->id }}" class="btn btn-follow following floatleft btn-lg">
                            {{ trans('settings.profile.following') }}
                        </button>
                    @elseif (Auth::id() == $user->id)
                    @else
                        <button data-id="{{ $user->id }}" class="btn btn-follow follow floatleft btn-lg">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            {{ trans('settings.profile.follow') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="shop-main-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="shop-left">
                        <div class="section-title-5 mb-30">
                            <h2>{{ trans('settings.profile.introduction') }}</h2>
                        </div>
                        <div class="left-title">
                            @auth
                                @if ($user)
                                    <ul  class="list-group intro-list">
                                        <li title="{{ __('settings.profile.email') }} {{ $user->email }}" class="list-group-item">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                            <span class="email">
                                            {{ substr(strip_tags($user->email), 0, 16) }}
                                                {{ strlen(strip_tags($user->email)) > 16 ? '...' : '' }}
                                        </span>
                                        </li>
                                        @if (isset($phoneUser))
                                            @if ($phoneUser == 0)
                                                <li title="{{ __('settings.profile.phone') }}" class="list-group-item">
                                                    <i class="fa fa-phone-square" aria-hidden="true"></i>
                                                    <span>{{ trans('page.phone') }}</span>
                                                </li>
                                            @else
                                                <li title="{{ __('settings.profile.phone') }}" class="list-group-item">
                                                    <i class="fa fa-phone-square" aria-hidden="true"></i>
                                                    <span>{{ $user->phone ? $user->phone : __('settings.profile.no_phone') }}</span>
                                                </li>
                                            @endif
                                        @else
                                            <li title="{{ __('settings.profile.phone') }}" class="list-group-item">
                                                <i class="fa fa-phone-square" aria-hidden="true"></i>
                                                <span>{{ $user->phone ? $user->phone : __('settings.profile.no_phone') }}</span>
                                            </li>
                                        @endif
                                        <li title="Employee Code" class="list-group-item">
                                            <i class="fa fa-barcode" aria-hidden="true"></i>
                                            <span>{{ $user->employee_code ? $user->employee_code : __('settings.profile.no_code') }}</span>
                                        </li>

                                        <li title="{{ __('settings.profile.position') }}" class="list-group-item">
                                            <i class="fa fa-building" aria-hidden="true"></i>
                                            <span>{{ $user->position ? $user->position : __('settings.profile.no_position') }}</span>
                                        </li>

                                        <li title="{{ __('settings.profile.workspace') }}" class="list-group-item">
                                            <i class="fa fa-home" aria-hidden="true"></i>
                                            <span>{{ $user->workspace ? $user->workspace : __('settings.profile.no_workspace') }}</span>
                                        </li>
                                        @if (count($followings->collapse()) > 0)
                                            <li title="Following Users" class="list-group-item">
                                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                <span>{{ count($followings->collapse()) }}
                                                    {{ __('settings.profile.following') }}
                                            </span>
                                            </li>
                                        @endif
                                        @if (count($followers->collapse()) > 0)
                                            <li title="Followers" class="list-group-item">
                                                <i class="fa fa-share-square" aria-hidden="true"></i>
                                                <span>{{ count($followers->collapse()) }}
                                                    {{ __('settings.profile.followers') }}
                                            </span>
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                    <div class="mb-30">
                        <ul class="nav nav-tabs status-tabs" role="tablist" id="profile-page">
                            <li class="active"><a id="borrow" href="#bio" data-toggle="tab">{{ trans('settings.profile.bio') }}</a></li>
                            <li><a href="#sharing" data-toggle="tab">{{ trans('settings.profile.sharing') }}</a></li>
                            <li><a id="borrow" href="#waiting" data-toggle="tab">{{ trans('settings.profile.waiting') }}</a></li>
                            <li><a id="borrow" href="#reading" data-toggle="tab">{{ trans('settings.profile.reading') }}</a></li>
                            <li><a id="borrow" href="#returned" data-toggle="tab">{{ trans('settings.profile.returned') }}</a></li>
                            <li><a id="borrow" href="#followings" data-toggle="tab">{{ trans('settings.profile.following') }}</a></li>
                            <li><a id="borrow" href="#followers" data-toggle="tab">{{ trans('settings.profile.followers') }}</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane" id="sharing" value="false">
                            @include ('layout.section.sharing_books')
                        </div>
                        <input type="" class="hidden" value="{{ $user->id }}" name="" id="userId">
                        <div class="tab-pane" id="waiting" value="true"></div>
                        <div class="tab-pane" id="reading" value="true"></div>
                        <div class="tab-pane" id="returned" value="true"></div>
                        <div class="tab-pane" id="followings" value="agree"></div>
                        <div class="tab-pane" id="followers" value="agree"></div>
                        <div class="tab-pane active" id="bio" value="agree">
                            <div class="row">
                                <h3 class="col-md-10">{{ trans('settings.profile.bioTitle') }}</h3>
                                <div class="col-md-10 col-md-offset-1" id="bio">
                                    <p id="text-bio">
                                        {{ $user->bio }}
                                    </p>
                                    @if (Auth::id() === $user->id)
                                        <button class="btn btn-sm btn-warning pull-right mt-16" id="edit-bio">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endif
                                </div>
                                @if (Auth::id() === $user->id)
                                    <div id="form-bio" class="col-md-10 col-md-offset-1 dp-none">
                                        <textarea class="form-control" id="input-bio" rows="4" cols="50">{{ $user->bio }}</textarea>
                                        <small><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ trans('page.maxBio') }}</small>
                                        <button class="btn btn-sm btn-success pull-right mt-16" id="submit-bio" data-url="{{ route('update-bio', $user->id) }}">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="loader"></div>
@endsection

@section('footer')
    @parent
@endsection
