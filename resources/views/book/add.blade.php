@extends('layout.app')
@section('header') @parent
<div class="breadcrumbs-area mb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumbs-menu">
                    <ul>
                        <li><a href="{{ route('home') }}">{{ __('page.home') }}</a></li>
                        <li><a href="#" class="active">{{ __('page.book.add') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="user-login-area mb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="login-title text-center mb-30">
                    <h2>{{ __('page.book.add') }}</h2>
                </div>
            </div>
            <div class="offset-lg-2 col-lg-8 col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
                @include('layout.notification')
                <div class="billing-fields">
                    {!! Form::open([
                        'method' => 'POST',
                        'route' => ['books.store'],
                        'class' => 'form-groupt',
                        'files' => 'true'
                    ]) !!}
                    <div class="single-register">
                        {!! Form::label(
                            'title',
                            __('page.book.title'),
                            [
                                'class' => 'col-2 col-form-label add-book',
                            ]
                        ) !!}
                        {!! Form::text(
                            'title',
                            null,
                            [
                                'required' => 'required',
                                'class' => 'form-control m-input',
                                'placeHolder' => __('page.book.placeHolder.title'),
                                'title' => __('page.book.title'),
                                'id' => 'book-title',
                                'autocomplete' => 'off',
                                'list' => 'browsers'
                            ]
                        ) !!}
                        <datalist id="browsers">
                        </datalist>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="single-register">
                                {!! Form::label(
                                    'author',
                                    __('page.book.author'),
                                    [
                                        'class' => 'add-book',
                                    ]
                                ) !!}
                                {!! Form::text(
                                    'author',
                                    null,
                                    [
                                        'required' => 'required',
                                        'class' => 'form-control m-input',
                                        'placeHolder' => __('page.book.placeHolder.author'),
                                        'title' => __('page.book.author'),
                                        'autocomplete' => 'off',
                                        'list' => 'authors',
                                    ]
                                ) !!}
                            </div>
                            <datalist id="authors"></datalist>
                        </div>
                    </div>
                    <div class="single-register mb-4">
                        <div class="row">
                            {!! Form::label(
                                'avatar',
                                __('page.book.avatar'),
                                [
                                    'class' => 'ml-15',
                                ])
                            !!}
                            <div class="col-md-12 custom-file" id="custom">
                                {!!
                                    Form::file('avatar',
                                    [
                                        'id' => 'customFile',
                                        'accept' => 'image/png, image/jpg, image/jpeg, image/bmp, image/gif',
                                        'onchange' => 'changeFile(event)',
                                        'onclick' => 'clickFile(event)',
                                        'title' => __('page.book.avatar')
                                    ])
                                !!}
                                {!! Form::label('customFile', __('page.book.browse'), ['class' => 'custom-file-label col-10 ml-3', 'id' => 'label']) !!}
                            </div>
                            <div class="col-md-4 col-md-offset-3 mt-3">
                                <img src="" alt="" class="img-book">
                            </div>
                        </div>
                    </div>
                    <div class="single-register">
                        {!! Form::label(
                            'category',
                            __('page.book.category'),
                            [
                                'class' => 'add-book',
                            ]
                        ) !!}
                        <div class="row">
                            @foreach ($categories as $category)
                                <div class="col-md-4">
                                    <label>
                                        {!! Form::checkbox(
                                            'categories[]',
                                            $category->id
                                        ) !!}
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="single-register">
                        {!! Form::label(
                            'description',
                            __('page.book.description'),
                            [
                                'class' => 'add-book',
                            ]
                        ) !!}
                        {!! Form::textarea(
                            'description',
                            null,
                            [
                                'id' => 'mytextarea',
                                'class' => 'form-control m-input',
                                'required' => 'required',
                            ]
                        ) !!}
                    </div>
                    <div class="single-register">
                        {!! Form::submit(__('page.submit'), ['class' => 'btn btn-info']) !!}
                        {!! Form::reset(__('page.reset'), ['class' => 'btn btn-secondary', 'id' => 'cancel']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer') @parent
@endsection

@section('script')
    {!! Html::script('assets/tinymce/js/tinymce//tinymce.min.js') !!}
    {!! Html::script('assets/admin/js/uploadFile.js') !!}
    <script>
        jQuery(document).ready(function() {
            tinymce.init({
                selector: 'textarea#mytextarea'
            });
        });
    </script>
@endsection