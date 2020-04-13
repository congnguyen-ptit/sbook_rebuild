@extends('layout.app')

@section('header')
    @parent
    <div class="breadcrumbs-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-menu">
                        <ul>
                            <li><a href="/">{{ __('page.home') }}</a></li>
                            <li><a href="/" class="active">{{ __('page.review') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="contact-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                    @include('layout.notification')
                    <div class="contact-form text-center">
                        <h3>{{ htmlspecialchars_decode($review->title) }}</h3>
                    </div>
                    <div class="row content">
                        <div class="col-md-2">
                            {!! Form::hidden('upvote', 1, ['id' => 'upvote', 'data-id' => $review->id]) !!}
                            <button class="btn-vote up-vote" data-upvote="{{ $flag }}" 
                            title=" {{ Auth::id() == $review->user_id ? __('settings.vote') : config('view.vote.upVote') }} " value="1" 
                            {{ Auth::id() == $review->user_id ? 'disabled' : '' }}>
                                <i class="fa fa-caret-up" aria-hidden="true"></i>
                            </button>
                            <span class="count-vote">{{ $review->upvote - $review->downvote }}</span>
                            <button class="btn-vote down-vote" value="-1" 
                            title=" {{ Auth::id() == $review->user_id ? __('settings.vote') : config('view.vote.downVote') }} "
                            {{ Auth::id() == $review->user_id ? 'disabled' : '' }}>
                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                            </button>
                            {!! Form::hidden('upvote', -1, ['id' => 'downvote']) !!}
                        </div>
                        <div class="col-md-10">
                            <div class="body">
                                {{ htmlspecialchars_decode($review->content) }}
                            </div>
                        </div>
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
                                        <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}">
                                            <img src="{{ asset(config('view.image_paths.book') . 'default_book.jpg') }}" alt="woman" />
                                        </a>
                                    @endif
                                </div>
                            </li>
                            <li>
                                <div class="product-addto-links-text">
                                    <div class="more hideContent">{!! ($book->description) !!}</div>
                                </div>
                                <div class="more-link">
                                    <a href="#">{{ __('page.book.show') }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @parent
@endsection

@section('script')
    <script>
        jQuery(document).ready(function() {

            var upVote = $('.up-vote').data('upvote');
            $('.btn-vote').removeClass('voted');
            if (upVote == 'up') {
                $('.up-vote').addClass('voted');
            } if (upVote == 'down') {
                $('.down-vote').addClass('voted');
            }

            $('.btn-vote').click(function(e) {
                e.preventDefault();
                var review = $('#upvote').data('id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/review/' + review + '/vote',
                    dataType: 'JSON',
                    data: {
                        vote: $(this).val()
                    },
                    success: function(data) {
                        if (data.status == 'up') {
                            $('.btn-vote').removeClass('voted');
                            $('.up-vote').addClass('voted');
                            var vote = parseInt($('.count-vote').text());
                            $('.count-vote').text(vote + 1);
                        } else if (data.status == 'down') {
                            $('.btn-vote').removeClass('voted');
                            $('.down-vote').addClass('voted');
                            var vote = parseInt($('.count-vote').text());
                            $('.count-vote').text(vote - 1);
                        } else if (data.status == 'nodown') {
                            $('.btn-vote').removeClass('voted');
                            var vote = parseInt($('.count-vote').text());
                            $('.count-vote').text(vote - 1);
                        } else if (data.status == 'noup') {
                            $('.btn-vote').removeClass('voted');
                            var vote = parseInt($('.count-vote').text());
                            $('.count-vote').text(vote + 1);
                        } else if (data == 2) {
                            swal({
                                text: 'Login to vote!',
                            });
                        } else if (data == 'error') {
                            swal({
                                text: 'You Voted!',
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
