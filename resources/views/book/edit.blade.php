@extends('layout.app')
@section('header') @parent
<div class="breadcrumbs-area mb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumbs-menu">
                    <ul>
                        <li><a href="#">{{ __('page.home') }}</a></li>
                        <li><a href="#" class="active">{{ __('page.book.edit') }}</a></li>
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
                    <h2>{{ __('page.book.edit') }}</h2>
                </div>
            </div>
            <div class="offset-lg-2 col-lg-8 col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
                @include('layout.notification')
                <div class="billing-fields">
                    {!! Form::open([
                        'method' => 'PATCH',
                        'route' => ['books.update', $book->slug . '-' . $book->id],
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
                            $book->title,
                            [
                                'placeHolder' => 'Title of book ...',
                                'required' => 'required',
                                'class' => 'form-control m-input'
                            ]
                        ) !!}
                        {!! $errors->first('title', '<p style="color:red">:message</p>') !!}
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                                    $book->author,
                                    [
                                        'placeHolder' => 'Author of book ...',
                                        'required' => 'required',
                                        'class' => 'form-control m-input'
                                    ]
                                ) !!}
                                {!! $errors->first('author', '<p style="color:red">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="single-register">
                                {!! Form::label(
                                    'total_pages',
                                    __('page.book.totalPage'),
                                    [
                                        'class' => 'add-book',
                                    ]
                                ) !!}
                                {!! Form::number(
                                    'total_pages',
                                    $book->total_pages,
                                    [
                                        'placeHolder' => 'Total pages',
                                        'required' => 'required',
                                        'class' => 'form-control m-input',
                                        'min' => 0,
                                    ]
                                ) !!}
                                {!! $errors->first('total_pages', '<p style="color:red">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="single-register mb-4">
                        <div class="row">
                            {!! Form::label(
                                'avatar',
                                __('page.book.avatar'),
                                [
                                    'class' => 'add-book',
                                ]
                            ) !!}
                            <div class="col-md-10 custom-file" id="custom">
                                {!!
                                    Form::file('avatar',
                                    [
                                        'id' => 'customFile',
                                        'accept' => 'image/png, image/jpg, image/jpeg, image/bmp, image/gif',
                                        'onchange' => 'changeFile(event)',
                                        'onclick' => 'clickFile(event)'
                                    ])
                                !!}
                                {!! Form::label('customFile', __('page.book.browse'), ['class' => 'custom-file-label col-10 ml-3', 'id' => 'label']) !!}
                            </div>
                            @if ($book->medias[0]->count() > 0)
                                {!! Form::hidden('avatar_old', $book->medias[0]->id) !!}
                            @else
                                {!! Form::hidden('avatar_old') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4 col-sm-push-4 single-register mt-3">
                        @if ($book->medias->count() > 0)
                            <img src="{{ asset(config('view.image_paths.book') . $book->medias[0]->path) }}" alt="book" class="img-book">
                        @else
                            <img src="" alt="" class="img-book">
                        @endif
                    </div>
                    <div class="both"></div>
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
                                            'category[]',
                                            $category->id,
                                            $checked != null && in_array($category->id, $checked)
                                        ) !!}
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="single-register">
                                {!! Form::label(
                                    'sku',
                                    __('page.book.sku'),
                                    [
                                        'class' => 'add-book',
                                    ]
                                ) !!}
                                {!! Form::text(
                                    'sku',
                                    $book->sku,
                                    [
                                        'placeHolder' => 'Sku of book ...',
                                        'disabled' => 'disabled',
                                        'class' => 'form-control m-input',
                                    ]
                                ) !!}
                                {!! $errors->first('sku', '<p style="color:red">:message</p>') !!}
                            </div>
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
                            $book->description,
                            [
                                'id' => 'mytextarea',
                                'class' => 'form-control m-input',
                            ]
                        ) !!}
                        {!! $errors->first('description', '<p style="color:red">:message</p>') !!}
                    </div>
                    <div class="single-register">
                        {!! Form::submit(__('page.submit'), ['class' => 'btn btn-info']) !!}
                        {!! Form::reset(__('page.reset'), ['class' => 'btn btn-secondary']) !!}
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
