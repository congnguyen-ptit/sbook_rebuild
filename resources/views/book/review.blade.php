@extends('layout.app')

@section('header')
    @parent
    <div class="breadcrumbs-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-menu">
                        <ul>
                            <li><a href="{{ asset('/') }}">{{ __('page.home') }}</a></li>
                            <li><a class="active">{{ __('page.review') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if ($flag)
        <div class="contact-area mb-70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                        @include('layout.notification')
                        <div class="contact-form">
                            <h3><i class="fa fa-envelope-o"> {{ __('page.review') }}</i> </h3>
                            {!! Form::open(['method' => 'post', 'route' => ['review.store', $book->slug . '-' . $book->id]]) !!}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="single-form-3">
                                            {!! Form::label('rate', __('page.rate'), ['class' => 'add-book']) !!}
                                            <div class="cont">
                                                <div class="stars">
                                                    {!! Form::radio('star', 5, false, ['class' => 'star star-5', 'id' => 'star-5']) !!}
                                                    {!! Form::label('star-5', ' ', ['class' => 'star star-5']) !!}
                                                    {!! Form::radio('star', 4, false, ['class' => 'star star-4', 'id' => 'star-4']) !!}
                                                    {!! Form::label('star-4', ' ', ['class' => 'star star-4']) !!}
                                                    {!! Form::radio('star', 3, false, ['class' => 'star star-3', 'id' => 'star-3']) !!}
                                                    {!! Form::label('star-3', ' ', ['class' => 'star star-3']) !!}
                                                    {!! Form::radio('star', 2, false, ['class' => 'star star-2', 'id' => 'star-2']) !!}
                                                    {!! Form::label('star-2', ' ', ['class' => 'star star-2']) !!}
                                                    {!! Form::radio('star', 1, false, ['class' => 'star star-1', 'id' => 'star-1']) !!}
                                                    {!! Form::label('star-1', ' ', ['class' => 'star star-1']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-lg-12">
                                            <div class="single-form-3">
                                                {!! Form::label('title', __('page.summary'), ['class' => 'add-book']) !!}
                                                {!! Form::text('title', null, ['placeHolder' => __('page.summary'), 'required' => 'required', 'title' => __('page.summary')]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="single-form-3 mytextarea">
                                                {!! Form::textarea('content', null, ['placeHolder' => trans('page.reviews.contentLabel'), 'id' => 'mytextarea']) !!}
                                                {!! Form::button(__('page.submit'), ['type' => 'submit', 'class' => 'submit']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                        <div class="contact-info text-center">
                            <h4><a href="{{ route('books.show', $book->slug . '-' . $book->id) }}">{{ $book->title }}</a></h4>
                            <ul>
                                <li>
                                    <div class="product-rating">
                                        @if ($book->medias->count() > 0)
                                            <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}">
                                                <img src="{{ asset(config('view.image_paths.book') . $book->medias[0]->path) }}" alt="book" />
                                            </a>
                                        @else
                                            <img src="{{ asset(config('view.image_paths.book') . 'default_book.jpg') }}" alt="woman" />
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <div class="product-addto-links-text more">
                                        {!! $book->description !!}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="contact-area mb-70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                        <div class="alert alert-info" role="alert">
                            <strong> {{ __('page.reviews.hasReview') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('footer')
    @parent
@endsection

@section('script')
    {!! Html::script('assets/tinymce/js/tinymce/tinymce.min.js') !!}
    <script>
        jQuery(document).ready(function() {
            tinymce.init({
                selector: 'textarea#mytextarea'
            });
        });
    </script>
@endsection
