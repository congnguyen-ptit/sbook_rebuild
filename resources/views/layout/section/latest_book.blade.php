@if(isset($latestBook) && !empty($latestBook))
    @for($i = 0; $i < count($latestBook) - 1; $i++)
        <div class="tab-total">
            <div class="product-wrapper mb-40">
                <div class="product-img">
                    <a href="{{ route('books.show', $latestBook[$i]->slug . '-' . $latestBook[$i]->id) }}">
                        <img src="{{ mediaBook($latestBook[$i]) }}" alt="{{ $latestBook[$i]->title }}" class="primary" />
                    </a>
                    <div class="quick-view">
                        <a class="action-view show-book-modal" href="#" data-url="{{ route('modal-book', $latestBook[$i]->id) }}" data-toggle="modal" title="Quick View">
                            <i class="fa fa-search-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="product-details text-center">
                    <div class="product-rating">
                        <div class="book-info">
                            <h4 class="title-book">
                                <a href="{{ route('books.show', $latestBook[$i]->slug . '-' . $latestBook[$i]->id) }}" title="{{ $latestBook[$i]->title }}">{{ $latestBook[$i]->title }}</a>
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
                                        'data-rating' => $latestBook[$i]->avg_star
                                    ])
                                !!}
                            </div>
                            <div class="owner-avatar">
                                @php $countOwnerLatest = $latestBook[$i]->owners->count() @endphp
                                @if ($countOwnerLatest > 3)
                                    @for ($j = 0; $j < 2; $j++)
                                        <div class="owner" id="{{ 'user-' . $latestBook[$i]->owners[$j]->id }}">
                                            <a href="{{ route('user', $latestBook[$i]->owners[$j]->id) }}" title="{{ $latestBook[$i]->owners[$j]->name }}">
                                                <img src="{{ $latestBook[$i]->owners[$j]->avatar ? $latestBook[$j]->owners[$j]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon">
                                            </a>
                                        </div>
                                    @endfor
                                    <div class="owner owner-show">
                                        <a href="/" title="{{ 'And more' }}" class="owner-more">
                                            <span>+</span>
                                            <span>{{ $countOwnerLatest - 2 }}</span>
                                        </a>
                                        <div class="owner-hover">
                                            @for ($i = 0; $i < $latestBook->owners->count(); $i++)
                                                <div class="owner" id="{{ 'user-' . $latestBook->owners[$i]->id }}">
                                                    <a href="{{ route('user', $latestBook->owners[$i]->id) }}" title="{{ $latestBook->owners[$i]->name }}">
                                                        <img src="{{ $latestBook->owners[$i]->avatar ? $latestBook->owners[$i]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                                    </a>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                @else
                                    @for ($j = 0; $j < $latestBook[$i]->owners->count(); $j++)
                                        <div class="owner" id="{{ 'user-' . $latestBook[$i]->owners[$j]->id }}">
                                            <a href="{{ route('user', $latestBook[$i]->owners[$j]->id) }}" title="{{ $latestBook[$i]->owners[$j]->name }}">
                                                <img src="{{ $latestBook[$i]->owners[$j]->avatar ? $latestBook[$i]->owners[$j]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon">
                                            </a>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-wrapper">
                <div class="product-img">
                    <a href="{{ route('books.show', $latestBook[$i]->slug . '-' . $latestBook[$i]->id) }}">
                        <img src="{{ mediaBook($latestBook[$i])  }}" alt="{{ $latestBook[$i]->title }}" class="primary"
                        />
                    </a>
                    <div class="quick-view">
                        <a class="action-view show-book-modal" href="#" data-url="{{ route('modal-book', $latestBook[$i]->id) }}" data-toggle="modal" title="Quick View">
                            <i class="fa fa-search-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="product-details text-center">
                    <div class="product-rating">
                        <div class="book-info">
                            <h4 class="title-book">
                                <a href="{{ route('books.show', $latestBook[$i]->slug . '-' . $latestBook[$i]->id) }}" title="{{ $latestBook[$i]->title }}">{{ $latestBook[$i]->title }}</a>
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
                                        'data-rating' => $latestBook[$i]->avg_star
                                    ])
                                !!}
                            </div>
                            <div class="owner-avatar">
                                @php $countOwnerLatest = $latestBook[$i]->owners->count() @endphp
                                @if ($countOwnerLatest > 3)
                                    @for ($j = 0; $j < 2; $j++)
                                        <div class="owner" id="{{ 'user-' . $latestBook[$i]->owners[$j]->id }}">
                                            <a href="{{ route('user', $latestBook[$i]->owners[$j]->id) }}" title="{{ $latestBook[$i]->owners[$j]->name }}">
                                                <img src="{{ $latestBook[$i]->owners[$j]->avatar ? $latestBook[$j]->owners[$j]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                            </a>
                                        </div>
                                    @endfor
                                    <div class="owner owner-show">
                                        <a href="/" title="{{ 'And more' }}" class="owner-more">
                                            <span>+</span>
                                            <span>{{ $countOwnerLatest - 2 }}</span>
                                        </a>
                                        <div class="owner-hover">
                                            @for ($j = 0; $j < $latestBook[$i]->owners->count(); $j++)
                                                <div class="owner" id="{{ 'user-' . $latestBook[$i]->owners[$j]->id }}">
                                                    <a href="{{ route('user', $latestBook[$i]->owners[$j]->id) }}" title="{{ $latestBook[$i]->owners[$j]->name }}">
                                                        <img src="{{ $latestBook[$i]->owners[$j]->avatar ? $latestBook[$i]->owners[$j]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                                    </a>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                @else
                                    @for ($j = 0; $j < $latestBook[$i]->owners->count(); $j++)
                                        <div class="owner" id="{{ 'user-' . $latestBook[$i]->owners[$j]->id }}">
                                            <a href="{{ route('user', $latestBook[$i]->owners[$j]->id) }}" title="{{ $latestBook[$i]->owners[$j]->name }}">
                                                <img src="{{ $latestBook[$i]->owners[$j]->avatar ? $latestBook[$i]->owners[$j]->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                            </a>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
@endif
