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
                            <li><a href="{{ route('home') }}">{{ __('settings.default.home') }}</a></li>
                            <li><a class="active">{{ __('settings.book.booklist') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->
@endsection

@section('content')
<!-- shop-main-area-start -->
<div class="shop-main-area mb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <div class="shop-left">
                    <div class="section-title-5 mb-30">
                        <h2>{{ __('page.book.showOption') }}</h2>
                    </div>
                    <div class="left-title mb-20">
                        <h4>{{ __('page.book.category') }}</h4>
                    </div>
                    <div class="left-menu mb-30">
                        <ul>
                            @foreach ($categories as $key => $category)
                                <li>
                                    <a href="{{ route('book.category', $category->slug . '-' . $category->id) }}" class="{{ isset($cate) && $cate->name == $category->name ? 'active' : '' }}" title="{{ $category->name }}">
                                        <span class="cate-name">{{ $category->name }}</span>
                                        <span class="count-cate">({{ $category->books_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="left-title mb-20">
                        <h4>{{ __('page.book.office') }}</h4>
                    </div>
                    <div class="left-menu mb-30">
                        <ul>
                            @foreach ($offices as $office)
                                <li>
                                    <a href="{{ route('book.office', str_slug($office->name)) }}" class="{{ isset($off) && $off == $office->name ? 'active' : '' }}">
                                        {{ $office->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="section-title-5 mb-30">
                    <h2>{{ __('settings.books') }}</h2>
                </div>
                <div class="toolbar mb-30">
                    <div class="shop-tab">
                        <div class="tab-3">
                            <ul>
                                <li class="{{ activeTab(config('view.request.grid')) }}">
                                    <a href="#th" data-toggle="tab" class="tab-type" data-type="grid"><i class="fa fa-th-large"></i>{{ config('view.request.grid') }}</a>
                                </li>
                                <li class="{{ activeTab(config('view.request.list')) }}">
                                    <a href="#list" data-toggle="tab" class="tab-type" data-type="list"><i class="fa fa-bars"></i>{{ config('view.request.list') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- tab-area-start -->
                <div class="tab-content">
                    <div class="tab-pane {{ activeTab(config('view.request.grid')) }}" id="th">
                        <div class="row">
                            @foreach ($books as $book)
                                <div class="col-lg-3 col-md-4 col-sm-6 mh-406">
                                    <!-- single-product-start -->
                                    <div class="product-wrapper mb-40">
                                        <div class="product-img">
                                            <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}">
                                                <img src="{{ $book->medias->count() > 0 ? asset(config('view.image_paths.book') . $book->medias[0]->path) : asset(config('view.image_paths.book') . 'default_book.jpg') }}" alt="book" class="primary" />
                                            </a>
                                            <div class="quick-view">
                                                <a class="action-view show-book-modal" href="#" data-url="{{ route('modal-book', $book->id) }}" title="Quick View">
                                                    <i class="fa fa-search-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-details text-center">
                                            <div class="book-info">
                                                <h4 class="title-book">
                                                    <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}" title="{{ $book->title }}">{{ $book->title }}</a>
                                                </h4>
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
                                                            'data-rating' => $book->avg_star
                                                        ])
                                                    !!}
                                               </div>
                                               <div class="owner-avatar">
                                                    @php $countOwner = $book->owners->count() @endphp
                                                    @if ($countOwner > 3)
                                                        @for ($i = 0; $i < 2; $i++)
                                                            <div class="owner" id="{{ 'user-' . $book->owners[$i]->id }}">
                                                                <a href="{{ route('user', $book->owners[$i]->id) }}" title="{{ $book->owners[$i]->name }}">
                                                                    <img src="{{ $book->owners[$i]->avatar ? $book->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};" class="owner-avatar-icon">
                                                                </a>
                                                            </div>
                                                        @endfor
                                                        <div class="owner owner-show">
                                                            <a href="/" title="{{ 'And more' }}" class="owner-more" data-toggle="tooltip">
                                                                <span>+</span>
                                                                <span>{{ $countOwner - 2 }}</span>
                                                            </a>
                                                            <div class="owner-hover">
                                                                @for ($i = 0; $i < $book->owners->count(); $i++)
                                                                    <div class="owner" id="{{ 'user-' . $book->owners[$i]->id }}">
                                                                        <a href="{{ route('user', $book->owners[$i]->id) }}" title="{{ $book->owners[$i]->name }}">
                                                                            <img src="{{ $book->owners[$i]->avatar ? $book->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};" class="owner-avatar-icon">
                                                                        </a>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    @else
                                                        @for ($i = 0; $i < $book->owners->count(); $i++)
                                                            <div class="owner" id="{{ 'user-' . $book->owners[$i]->id }}">
                                                                <a href="{{ route('user', $book->owners[$i]->id) }}" title="{{ $book->owners[$i]->name }}">
                                                                    <img src="{{ $book->owners[$i]->avatar ? $book->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};" class="owner-avatar-icon">
                                                                </a>
                                                            </div>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single-product-end -->
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade in {{ activeTab(config('view.request.list')) }}" id="list">
                        @foreach ($books as $book)
                            <div class="single-shop mb-30">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="product-wrapper-2">
                                            <div class="product-img">
                                                <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}">
                                                    <img src="{{ asset(config('view.image_paths.book') . ($book->medias->count() > 0 ? $book->medias[0]->path : 'default_book.jpg')) }}" alt="book" class="primary" />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <div class="product-wrapper-content">
                                            <div class="product-details">
                                                <h4>
                                                    <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}" title="{{ $book->title }}">{{ $book->title }}</a>
                                                </h4>

                                                <div class="product-info mt-0">
                                                    <div class="product-reviews-summary owner-list">
                                                        <div class="rating-summary ofl-x">
                                                            <p class="share-by"><b>{{ trans('settings.book.owners') }}</b></p>
                                                            <div class="owner-avatar">
                                                                @if (count($book->owners) > 0)
                                                                    @foreach ($book->owners as $owner)
                                                                        <div class="owner mr-6 reltop-10" id="{{ 'user-' . $owner->id }}">
                                                                            <a href="{{ route('user', $owner->id) }}" title="{{ $owner->name }} ({{ $owner->office ? $owner->office->name : '' }})">
                                                                                <img src="{{ $owner->avatar ? $owner->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon">
                                                                            </a>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <span class="text-danger reltop-15">{{ __('settings.modal.no_owners') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-reviews-summary lh-35">
                                                        <div class="product-rating-book">
                                                            <span class="rate"><b>{{ __('settings.review.rating') . ':' }}</b></span>
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
                                                            <b>{{ __('settings.book.view') }}</b>
                                                            <span>{{ $book->count_viewed ? $book->count_viewed : '0' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php $str = strip_tags($book->description) @endphp

                                                @if (strlen($str) > 200)
                                                    {!! mb_substr($str, 0, 200) . '...' !!}
                                                @else
                                                    {!! strip_tags($book->description) !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- tab-area-end -->
                <!-- pagination-area-start -->
                <div class="pagination-area mt-50">
                    <div class="list-page-2">
                        <p>
                            @if($books->count() >0)
                                {{ __('settings.showing')  }}
                                {{ ($books->currentpage() - 1) * $books->perpage() + 1 }} {{ __('settings.to') }} {{ ($books->currentpage() - 1) * $books->perpage() + $books->count() }} {{ __('settings.of') }} {{ $books->total() }} {{ __('settings.items') }}
                            @endif
                        </p>
                    </div>
                    <div class="page-numbers">
                        {{ $books->appends(request()->only(['type']))->links() }}
                    </div>
                </div>
                <!-- pagination-area-end -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @parent
@endsection
@section('script')
    @parent
    {{ Html::script('assets/user/js/book.js') }}
@endsection
