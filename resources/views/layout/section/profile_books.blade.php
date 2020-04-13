@if (count($books) > 0)
<div class="row">
    @foreach ($books as $item)
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="product-wrapper mb-40">
            <div class="product-img">
                <a href="{{ route('books.show', $item->slug . '-' . $item->id) }}">
                    <img src="{{ $item->medias->count() > 0 ? asset(config('view.image_paths.book') . $item->medias[0]->path) : asset(config('view.image_paths.book') . 'default_book.jpg') }}" alt="item" class="primary" />
                </a>
                <div class="quick-view">
                    <a class="action-view show-book-modal" href="#" data-url="{{ route('modal-book', $item->id) }}" data-toggle="modal" title="Quick View">
                        <i class="fa fa-search-plus"></i>
                    </a>
                </div>
            </div>
            <div class="product-details text-center">
                <div class="book-info">
                    <h4 class="title-book">
                        <a href="{{ route('books.show', $item->slug . '-' . $item->id) }}" title="{{ $item->title }}">{{ $item->title }}</a>
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
                                'data-rating' => $item->avg_star
                            ])
                        !!}
                    </div>
                    <div class="owner-avatar">
                        @php $countOwnerTop = $item->owners->count() @endphp
                        @if ($countOwnerTop > 3)
                            @for ($i = 0; $i < 2; $i++)
                                <div class="owner" id="{{ 'user-' . $item->owners[$i]->id }}">
                                    <a href="{{ route('user', $item->owners[$i]->id) }}" title="{{ $item->owners[$i]->name }}">
                                        <img src="{{ $item->owners[$i]->avatar ? $item->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                    </a>
                                </div>
                            @endfor
                            <div class="owner owner-show">
                                <a href="/" title="{{ 'And more' }}" class="owner-more" data-toggle="tooltip">
                                    <span>+</span>
                                    <span>{{ $countOwnerTop - 2 }}</span>
                                </a>
                                <div class="owner-hover">
                                    @for ($i = 0; $i < $item->owners->count(); $i++)
                                        <div class="owner" id="{{ 'user-' . $item->owners[$i]->id }}">
                                            <a href="{{ route('user', $item->owners[$i]->id) }}" title="{{ $item->owners[$i]->name }}">
                                                <img src="{{ $item->owners[$i]->avatar ? $item->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                            </a>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        @else
                            @for ($i = 0; $i < $item->owners->count(); $i++)
                                <div class="owner" id="{{ 'user-' . $item->owners[$i]->id }}">
                                    <a href="{{ route('user', $item->owners[$i]->id) }}" title="{{ $item->owners[$i]->name }}">
                                        <img src="{{ $item->owners[$i]->avatar ? $item->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                    </a>
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="text-center">
    <div class="profile">
        {{ $books->appends(Request::all())->links() }}
    </div>
</div>
@endif
