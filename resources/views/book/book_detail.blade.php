@extends('layout.app')

@section('header')
    @parent
    <div class="breadcrumbs-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-menu">
                        <ul>
                            <li><a href="{{ asset('/') }}">{{ trans('settings.default.home') }}</a></li>
                            <li><a class="active">{{ trans('settings.book.detail_book') }}</a></li>
                            @if ($tmp)
                                <li>
                                    <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-info mb-4">
                                        {{ trans('settings.book.edit') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="product-main-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                    <div class="product-main-area">
                        @if ($book)
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                                    <div class="detail-img">
                                        <ul class="slides text-center">
                                            <li>
                                                <img class="picture" src="{{ asset(config('view.image_paths.book') . (count($book->medias) > 0 ? $book->medias[0]->path : 'default.jpg')) }}" alt="woman"/>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="product-info">
                                        <div class="product-reviews-summary owner-list">
                                            <div class="rating-summary ofl-x">
                                                <p class="share-by"><b>{{ trans('settings.book.owners') }}</b></p>
                                                <div id="list-owners">
                                                    @include('layout.section.list_owners')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-reviews-summary lh-35">
                                            <div class="product-rating-book">
                                                <span class="rate"><b>{{ trans('settings.review.rating') . ':' }}</b></span>
                                                {!! Form::select('rating',
                                                    [
                                                        '' => '',
                                                        '1' => 1,
                                                        '2' => 2,
                                                        '3' => 3,
                                                        '4' => 4,
                                                        '5' => 5
                                                    ],
                                                    null,
                                                    [
                                                        'class' => 'rating',
                                                        'data-rating' => $book->avg_star
                                                    ])
                                                !!}
                                            </div>
                                            <div class="reviews-actions-book">
                                                <b>{{ trans('settings.default.totalReview') . ': ' }}</b>
                                                <a href="#review" id="review">
                                                    {{ count($book->reviews) . ' ' . (count($book->reviews) <= 1 ? trans('settings.book.review') : trans('settings.default.reviews')) }}
                                                </a>
                                            </div>
                                            <div class="view-sku">
                                                <div class="reviews-actions-book">
                                                    <b>{{ trans('settings.book.view') }}</b>
                                                    <span>{{ $book->count_viewed ? $book->count_viewed : '0' }}</span>
                                                </div>
                                                <div class="reviews-actions-book">
                                                    <b>{{ trans('settings.book.sku') }}</b>
                                                    <span>{{ $book->sku ? $book->sku : '0' }}</span>
                                                </div>
                                            </div>
                                            <div class="qr-code">
                                                {!! QrCode::size(100)->generate(
                                                    Request::url(config('view.link') . $book->slug . '-' . $book->id)); !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                                    <div class="product-info-main">
                                        <div class="page-title">
                                            <h1>{{ $book->title }}</h1>
                                        </div>
                                        <div class="product-reviews-summary">
                                            <b>{{ trans('settings.book.author') }}</b>
                                            <span>{{ $book->author }}</span>
                                        </div>
                                        <div class="product-reviews-summary">
                                            <div class="rating-summary">
                                                <b>{{ trans('settings.book.categories') }}</b>
                                            </div>
                                            <div class="reviews-actions">
                                                @if (count($book->categories) > 0)
                                                    @for ($i = 0; $i < count($book->categories); $i++)
                                                        @if ($i == (count($book->categories) - 1))
                                                            <span class="category-book">
                                                                <a href="{{ route('book.category', $book->categories[$i]['slug'].'-'.$book->categories[$i]['id']) }}">
                                                                    {{ $book->categories[$i]['name'] }}
                                                                </a>
                                                            </span>
                                                        @else
                                                            <span class="category-book">
                                                                <a href="{{ route('book.category', $book->categories[$i]['slug'].'-'.$book->categories[$i]['id']) }}">
                                                                    {{ $book->categories[$i]['name'] . ' - ' }}
                                                                </a>
                                                            </span>
                                                        @endif
                                                    @endfor
                                                @else
                                                    <span>{{ trans('settings.book.no_category') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-add-form">
                                            @if ($book->owners->count() > 0)
                                                <form>
                                                    @if ($bookStatus)
                                                        @switch ($bookStatus->type)
                                                            @case (config('view.request.waiting'))
                                                                <a data-toggle="modal" data-id="{{ $book->id }}" class="btn-cancel-borrowing">
                                                                    {{ trans('settings.book.cancel_borrowing') }}
                                                                </a>
                                                                @break
                                                            @case (config('view.request.reading'))
                                                                <a data-toggle="modal" href="#returningModal" data-id="{{ $book->id }}" class="btn-returning">
                                                                    {{ trans('settings.book.return_book') }}
                                                                </a>
                                                                @break
                                                            @case (config('view.request.returning'))
                                                                <a data-toggle="modal" href="/" class="btn disabled">
                                                                    {{ trans('settings.book.returning') }}
                                                                </a>
                                                                @break
                                                            @default
                                                                <a data-toggle="modal" href="{{ Auth::check() ? '#borrowingModal' : '' }}"
                                                                    data-id="{{ $book->id }}"
                                                                    class="{{ Auth::check() ? 'btn-borrow book-info-link' : 'login' }} {{ $isOwner ? 'disabled' : '' }}"
                                                                    >
                                                                    {{ trans('settings.book.borrow_book') }}
                                                                </a>
                                                        @endswitch
                                                    @else
                                                        <a data-toggle="modal" href="{{ Auth::check() ? '#borrowingModal' : '' }}"
                                                            data-id="{{ $book->id }}"
                                                            class="{{ Auth::check() ? 'btn-borrow book-info-link' : 'login' }} {{ $isOwner ? 'hide' : '' }}">
                                                            {{ trans('settings.book.borrow_book') }}
                                                        </a>
                                                    @endif
                                                    @if (Auth::check() && $isOwner)
                                                        @if(!$anyReadingOrReturning)
                                                            <a data-toggle="modal" class="btn-remove-owner" data-id="{{ $book->id }}" owner="{{ Auth::id() }}">
                                                                {{ trans('settings.book.remove_owner') }}
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a data-toggle="modal" href="#" class="{{ Auth::check() ? 'btn-share book-info-link' : 'login' }} {{ $bookStatus && $bookStatus->type != 'returned' ? 'disabled' : '' }}" data-id="{{ $book->id }}" owner="{{ Auth::id() }}">
                                                            {{ trans('settings.book.i_have_this_book') }}
                                                        </a>
                                                    @endif
                                                </form>
                                            @else
                                                <form>
                                                    @if ($isOwner)
                                                        <a data-toggle="modal" class="btn-remove-owner" data-id="{{ $book->id }}" owner="{{ Auth::id() }}">
                                                            {{ trans('settings.book.remove_owner') }}
                                                        </a>
                                                    @else
                                                        <a data-toggle="modal" class="btn-share book-info-link {{ Auth::check() ? '' : 'login' }}" disabled data-id="{{ $book->id }}" owner="{{ Auth::id() }}">
                                                            {{ trans('settings.book.i_have_this_book') }}
                                                        </a>
                                                    @endif
                                                </form>
                                            @endif
                                        </div>
                                        <div class="product-social-links">
                                            <div class="product-addto-links">
                                                @if (Auth::check())
                                                    <a href="#"
                                                       class="vote-book"
                                                       data-id="{{ $book->id }}"
                                                       data-url="{{ route('add-favorite', $book->id) }}"
                                                    >
                                                        <i class="fa fa-heart {{ $likedBook ? 'text-danger' : '' }}" id="favorite-icon-{{ $book->id }}"></i>
                                                    </a>
                                                @endif
                                                 <a id="chartUserEvaluationBt" data-toggle="modal" href="#chartUserEvaluationModel"><i class="fa fa-pie-chart"></i></a>
                                            </div>
                                            <div class="product-addto-links-text">
                                                <p class="more hideContent">{!! strip_tags(($book->description)) !!}</p>
                                            </div>
                                            <div class="more-link">
                                                <a href="#">{{ trans('page.book.show') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="product-info-area mt-80">
                        @auth
                            <ul class="nav nav-tabs detail-tabs" role="tablist" data-id="{{ $book->id }}">
                                <li class="active tab-pane" id="li-reviews">
                                    <a href="#reviews" data-toggle="tab" class="panel-tab">
                                        {{ trans('settings.book.review') }}
                                    </a>
                                </li>
                                <li class="tab-pane" id="li-waiting">
                                    <a href="#waiting" data-toggle="tab" class="panel-tab">
                                        {{ trans('settings.book.waiting') }}
                                    </a>
                                </li>
                                <li class="tab-pane" id="li-reading">
                                    <a href="#reading" data-toggle="tab" class="panel-tab">
                                        {{ trans('settings.book.reading') }}
                                    </a>
                                </li>
                                <li class="tab-pane" id="li-returning">
                                    <a href="#returning" data-toggle="tab" class="panel-tab">
                                        {{ trans('settings.book.returning') }}
                                    </a>
                                </li>
                                <li class="tab-pane" id="li-returned">
                                    <a href="#returned" data-toggle="tab" class="panel-tab">
                                        {{ trans('settings.book.returned') }}
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="reviews" status="done">
                                    <div class="row event-list">
                                        <div class="col-xs-12 col-sm-8 col-md-12 revsdv fix" id="load-data">
                                            @if ($reviews->count() > 0)
                                                @foreach ($reviews as $review)
                                                    <div class="event-item wow fadeInRight comment-box">
                                                        <div class="well padding5 owner-clear relative-position">
                                                            <div class="media padding5">
                                                                <div class="medialeft">
                                                                    <img src="{{ ($review->user && $review->user->avatar != '') ? $review->user->avatar : asset(config('view.image_paths.user') . '1.png') }}"
                                                                        class="img-thumbnail avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};" alt="avatar">
                                                                </div>
                                                                <div class="media-body relative-position">
                                                                    <div class="content-comment">
                                                                        <div class="show tip-left">
                                                                            <strong>{{ $review->user->name }}</strong>
                                                                            <i>{{ $review->updated_at }}</i>
                                                                            @if ($review->user->id == Auth::id())
                                                                                <div class="action">
                                                                                    <a href="{{ route('review.edit', [$book->slug . '-' . $book->id, $review->id]) }}" class="btn btn-info btn-sm a-btn-slide-text">
                                                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                                                    </a>
                                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['review.destroy', $book->slug . '-' . $book->id, $review->id], 'id' => "$review->id"]) !!}
                                                                                    {!! Form::button('<i class="fa fa-trash"></i>', ['class' => 'btn btn-danger m-btn m-btn--custom btn-9 btn-sm notify', 'type' => 'submit']) !!}
                                                                                    {!! Form::close() !!}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <h4 class="media-heading list-inline list-unstyled rating-star m-0">
                                                                            @for ($i = 1; $i <= ($review->star); $i++)
                                                                                <li class="active">
                                                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                                                </li>
                                                                            @endfor
                                                                            @for ($j = 1; $j <= 5 - ($review->star); $j++)
                                                                                <li class="">
                                                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                                                </li>
                                                                            @endfor
                                                                        </h4>
                                                                        <p class="m-0 review-title">{{ $review->title }}</p>
                                                                        <div class="custom-vote">
                                                                            <div class="float-right">
                                                                                <div class="vote">
                                                                                    <p class="no-margin">
                                                                                        <i class="fa fa-caret-up"
                                                                                           aria-hidden="true"></i>
                                                                                    </p>
                                                                                    <p class="no-margin mtop">
                                                                                        <i class="fa fa-caret-down"
                                                                                           aria-hidden="true"></i>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="count-voted">
                                                                                    <span>{{ $review->upvote - $review->downvote }}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <a href="{{ route('review.show', [$book->slug . '-' . $book->id, $review->id]) }}" class="view_more {{ Auth::check() ? '' : 'login' }}">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        {{ trans('settings.review.viewMore') }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                {{ $reviews->appends(Request::all())->links() }}
                                            @else
                                                <div class="alert alert-info">
                                                    {{ trans('page.reviews.noReview') }}
                                                </div>
                                            @endif
                                            @if ($flag == true)
                                                <div class="button">
                                                    <a href="{{ route('review.create', $book->slug . '-' . $book->id) }}"
                                                       class="btn btn-primary">{{ trans('settings.review.add') }}</a>
                                                </div>
                                            @endif
                                            @if (!Auth::check())
                                                <div class="alert alert-success">
                                                    {{ trans('page.reviews.sign') }}
                                                    <b><a class="nav-link"
                                                          href="{{ route('framgia.login') }}">{{ trans('Login') }}</a></b>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="waiting" status="none">
                                </div>
                                <div class="tab-pane" id="reading" status="none">
                                </div>
                                <div class="tab-pane" id="returning" status="none">
                                </div>
                                <div class="tab-pane" id="returned" status="none">
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="shop-left">
                        @if (isset($topBorrowed))
                            <div class="left-title mb-20">
                                <h4>{{ trans('settings.home.top_borrowed') }}</h4>
                            </div>
                            <div class="random-area mb-30">
                                <div class="product-active owl-carousel">
                                    <div class="product-total-2">
                                        @for ($i = 0; $i < count($topBorrowed); $i++)
                                            <div class="single-most-product top-interesting bd mb-18">
                                                @if ($topBorrowed[$i]->medias->count() > 0)
                                                    <div class="most-product-img">
                                                        <a href="{{ route('books.show', $topBorrowed[$i]->slug . '-' . $topBorrowed[$i]->id) }}">
                                                            <img src="{{ asset(config('view.image_paths.book') . $topBorrowed[$i]->medias[0]->path) }}"
                                                                 alt="book"/>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="most-product-img">
                                                        <a href="{{ route('books.show', $topBorrowed[$i]->slug . '-' . $topBorrowed[$i]->id) }}">
                                                            <img src="{{ asset(config('view.image_paths.book') . 'default.jpg') }}"
                                                                 alt="woman"/>
                                                        </a>
                                                    </div>
                                                @endif
                                                <div class="most-product-content top-interesting">
                                                    <h4>
                                                        <a href="{{ route('books.show', ['id' => $topBorrowed[$i]->slug . '-' . $topBorrowed[$i]->id]) }}"
                                                            title="{{ $topBorrowed[$i]->title }}">
                                                            {{ $topBorrowed[$i]->title }}
                                                        </a>
                                                    </h4>
                                                    <div class="product-rating">
                                                        {!! Form::select('rating',
                                                            [
                                                                '' => '',
                                                                '1' => 1,
                                                                '2' => 2,
                                                                '3' => 3,
                                                                '4' => 4,
                                                                '5' => 5
                                                            ],
                                                            null,
                                                            [
                                                                'class' => 'rating',
                                                                'data-rating' => $topBorrowed[$i]->avg_star
                                                            ])
                                                        !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="new-book-area mt-60">
                        @if (isset($relatedBooks) && $relatedBooks->count() >= 3)
                            <div class="section-title text-center mb-30 text-uppercase">
                                <h3>{{ trans('page.book.upsell') }}</h3>
                            </div>
                            <div class="tab-active-2 owl-carousel">
                                @foreach($relatedBooks as $bookRelated)
                                    <div class="product-wrapper">
                                        <div class="product-img">
                                            @if ($bookRelated->medias->count() > 0)
                                                <a href="{{ route('books.show', $bookRelated->slug . '-' . $bookRelated->id) }}">
                                                    <img src="{{ asset(config('view.image_paths.book') . $bookRelated->medias[0]->path) }}" alt="book" class="primary"/>
                                                </a>
                                            @else
                                                <a href="{{ route('books.show', $bookRelated->slug . '-' . $bookRelated->id) }}">
                                                    <img src="{{ asset(config('view.image_paths.book') . 'default.jpg') }}" alt="woman"/>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="quick-view">
                                            <a class="action-view show-book-modal" href="#" data-url="{{ route('modal-book', $bookRelated->id) }}" data-toggle="modal" title="Quick View">
                                                <i class="fa fa-search-plus"></i>
                                            </a>
                                        </div>
                                        <div class="product-details text-center">
                                            <div class="product-rating">
                                                <div class="book-info">
                                                    <h4 class="title-book">
                                                        <a href="{{ route('books.show', ['id' => $bookRelated->slug . '-' . $bookRelated->id]) }}"
                                                            title="{{ $bookRelated->title }}">
                                                            {{ $bookRelated->title }}
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div class="avg_star">
                                                    {!! Form::select('rating',
                                                        [
                                                            '' => '',
                                                            '1' => 1,
                                                            '2' => 2,
                                                            '3' => 3,
                                                            '4' => 4,
                                                            '5' => 5
                                                        ],
                                                        null,
                                                        [
                                                            'class' => 'rating',
                                                            'data-rating' => $bookRelated->avg_star
                                                        ])
                                                    !!}
                                                </div>
                                                <div class="owner-avatar">
                                                    @php $countOwnerLatest = $bookRelated->owners->count() @endphp
                                                    @if ($countOwnerLatest > 3)
                                                        @for ($j = 0; $j < 2; $j++)
                                                            <div class="owner" id="{{ 'user-' . $bookRelated->owners[$j]->id }}">
                                                                <a href="{{ route('user', $bookRelated->owners[$j]->id) }}" title="{{ $bookRelated->owners[$j]->name }}">
                                                                    <img src="{{ $bookRelated->owners[$j]->avatar ? $bookRelated->owners[$j]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                                                </a>
                                                            </div>
                                                        @endfor
                                                        <div class="owner owner-show">
                                                            <a href="/" title="{{ 'And more' }}" class="owner-more">
                                                                <span>+</span>
                                                                <span>{{ $countOwnerLatest - 2 }}</span>
                                                            </a>
                                                            <div class="owner-hover">
                                                                @for ($j = 0; $j < $bookRelated->owners->count(); $j++)
                                                                    <div class="owner" id="{{ 'user-' . $bookRelated->owners[$j]->id }}">
                                                                        <a href="{{ route('user', $bookRelated->owners[$j]->id) }}" title="{{ $bookRelated->owners[$j]->name }}">
                                                                            <img src="{{ $bookRelated->owners[$j]->avatar ? $bookRelated->owners[$j]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                                                        </a>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    @elseif ($countOwnerLatest > 0)
                                                        @for ($j = 0; $j < $bookRelated->owners->count(); $j++)
                                                            <div class="owner" id="{{ 'user-' . $bookRelated->owners[$j]->id }}">
                                                                <a href="{{ route('user', $bookRelated->owners[$j]->id) }}" title="{{ $bookRelated->owners[$j]->name }}">
                                                                    <img src="{{ $bookRelated->owners[$j]->avatar ? $bookRelated->owners[$j]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                                                </a>
                                                            </div>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- chartUserEvaluationModel --}}
    <div class="modal animated zoomIn faster" id="chartUserEvaluationModel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">{{ trans('settings.modal.statistic_book') }}</h4>
                </div>

                <div class="modal-body row">
                    <div class="col-lg-4 pb-25">
                        <select id="yearFilterSelect" class="form-control" data-bookId="{{ $book->id }}">
                            @foreach ($filterYears as $year)
                                <option value="{{ $year }}" @if ($year == now()->year) selected @endif>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12 p-0">
                        <div class="chart" id="userEvaluationchart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end chartUserEvaluationModel --}}

    <div class="modal animated zoomIn faster overflow-none" id="borrowingModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">{{ trans('settings.modal.borrow_book') }}</h4>
                </div>
                    {!! Form::open([
                        'method' => 'POST',
                        'id' => 'borrowingForm',
                        'class' => 'form-groupt',
                        'data-id' => $book->id,
                    ]) !!}
                <div class="modal-body row">
                    <div class="owner-input scroll-h-3">
                        @if ($book->owners)
                            @foreach($book->owners->groupBy('office_id') as $workspace => $owners)
                                <div class="mb-10">
                                    <h4>
                                        <i class="fa fa-map-marker" aria-hidden="true"></i> {{ $owners->first()->office->name ?? 'N/A' }}
                                    </h4>
                                    @foreach ($owners as $owner)
                                        @if ($owner->id != Auth::id())
                                            <div class="row ml-15">
                                                <div class="col-xs-12 col-sm-12" id="owner{{ $owner->id }}">
                                                    <label class="radio">
                                                        @if (!bookBorrowed($owner, $book->id))
                                                            {{ $owner->name }}
                                                            {!! Form::radio(
                                                                'owner_id',
                                                                $owner->id,
                                                                [
                                                                    'required' => 'required',
                                                                ]
                                                            ) !!}
                                                            <span class="checkround"></span>
                                                        @else
                                                            {{ $owner->name }}
                                                        @endif
                                                            <small>
                                                                ( {{ !bookBorrowed($owner, $book->id) ? trans('settings.modal.available') : trans('settings.modal.unavailable') }} )
                                                            </small>
                                                    </label>
                                                </div>
                                            </div>
                                        @elseif ($owner->id == Auth::id() && $book->owners->count() == 1)
                                            @php $status = 0; @endphp
                                            <div class="alert alert-info text-center mb-4" role="alert">
                                                <p class="no-owner">{{ trans('page.noOwner') }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            @php $status = 0; @endphp
                            <div class="alert alert-info text-center mb-4" role="alert">
                                <p class="no-owner">{{ trans('page.noOwner') }}</p>
                            </div>
                        @endif
                    </div>
                    @if (!isset($status))
                        <div class="row mt-4">
                            <div class="col-sm-8">
                                <p class="choose">{{ trans('settings.modal.choose_time_to_borrow') }}</p>
                            </div>
                            <div class="col-lg-4">
                                {!! Form::selectRange('days_to_read', 3, 30, null, ['class' => 'form-control day']) !!}
                                <span>{{ trans('settings.modal.days') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
                @if (!isset($status))
                    <div class="modal-footer">
                        <a data-dismiss="modal" class="btn btn-danger">{{ trans('settings.modal.btn_close') }}</a>
                        {!! Form::submit(
                            trans('settings.modal.btn_submit'),
                            [
                                'class' => 'btn btn-success',
                            ]
                        )!!}
                    </div>
                @endif
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal animated zoomIn faster" id="returningModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">{{ trans('settings.modal.borrow_book') }}</h4>
                </div>
                {!! Form::open([
                        'method' => 'POST',
                        'route' => ['return-book', $book->id],
                        'id' => 'returning-book',
                        'data-id' => $book->id,
                ]) !!}
                <div class="modal-body row">
                    <div class="owner-input">
                        <p>{{ trans('settings.book.sure') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" class="btn btn-danger">{{ trans('settings.modal.btn_close') }}</a>
                    {!! Form::submit(
                        trans('settings.modal.btn_submit'),
                        [
                            'class' => 'btn btn-success',
                        ]
                    )!!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @parent
@endsection
