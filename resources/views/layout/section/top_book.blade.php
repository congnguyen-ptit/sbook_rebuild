<div class="product-wrapper">
    <div class="product-img">
        <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}">
            <img src="{{ mediaBook($book) }}" alt="{{ $book->title }}" class="primary" />
        </a>
        <div class="quick-view">
            <a class="action-view show-book-modal" href="#" data-url="{{ route('modal-book', $book->id) }}" data-target="#productModal" data-toggle="modal" title="Quick View">
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
                @php $countOwnerTop = $book->owners->count() @endphp
                @if ($countOwnerTop > 3)
                    @for ($i = 0; $i < 2; $i++)
                        <div class="owner" id="{{ 'user-' . $book->owners[$i]->id }}">
                            <a href="{{ route('user', $book->owners[$i]->id) }}" title="{{ $book->owners[$i]->name }}">
                                <img src="{{ $book->owners[$i]->avatar ? $book->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                            </a>
                        </div>
                    @endfor
                    <div class="owner owner-show">
                        <a href="/" title="{{ 'And more' }}" class="owner-more" data-toggle="tooltip">
                            <span>+</span>
                            <span>{{ $countOwnerTop - 2 }}</span>
                        </a>
                        <div class="owner-hover">
                            @for ($i = 0; $i < $book->owners->count(); $i++)
                                <div class="owner" id="{{ 'user-' . $book->owners[$i]->id }}">
                                    <a href="{{ route('user', $book->owners[$i]->id) }}" title="{{ $book->owners[$i]->name }}">
                                        <img src="{{ $book->owners[$i]->avatar ? $book->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                    </a>
                                </div>
                            @endfor
                        </div>
                    </div>
                @else
                    @for ($i = 0; $i < $book->owners->count(); $i++)
                        <div class="owner" id="{{ 'user-' . $book->owners[$i]->id }}">
                            <a href="{{ route('user', $book->owners[$i]->id) }}" title="{{ $book->owners[$i]->name }}">
                                <img src="{{ $book->owners[$i]->avatar ? $book->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                            </a>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </div>
</div>
