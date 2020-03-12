<ul class="suggestions-list">
    @if (count($titles) > 0)
        @foreach ($titles as $book)
            <li class="result-entry" data-suggestion="Target 1" data-position="1"data-type="type" data-analytics-type="merchant">
                <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}" class="result-link">
                    <div class="media">
                        <div class="left-media">
                            @if ($book->medias->count() > 0)
                                <img src="{{ asset(config('view.image_paths.book') . $book->medias[0]->path) }}" alt="item" class="search-avatar"/>
                            @else
                                <img src="{{ asset(config('view.image_paths.book') . 'default.jpg') }}" alt="woman" class="search-avatar"/>
                            @endif
                        </div>
                        <div class="search-media">
                            <h4 class="media-heading">{{ $book->title }}</h4>
                            <p>{{ $book->author }}</p>
                            <div class="user-item">
                                @if ($book->owners)
                                    @foreach ($book->owners as $owner)
                                        <div class="reviews-actions" id="{{ 'user-' . $owner->id }}">
                                            @if (!empty($owner->office->name))
                                                <a class="search-img" href="{{ route('user', $owner->id) }}"
                                                   title="{{ $owner->name }} ( {{ $owner->office->name }} )">
                                                    @if ($owner->avatar != '')
                                                        <img class="user-search" src="{{ $owner->avatar }}" onerror="this.onerror=null;this.src='{{ asset("img/user/avatar/default.jpg") }}';">
                                                    @else
                                                        <img class="user-search" src="{{ asset(config('view.image_paths.user') . '1.png') }}">
                                                    @endif
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        @endforeach
    @else
        {{ trans('settings.home.not_found') }}
    @endif
</ul>
<div class="search-title">
    <div class="text-center">
        {{ $titles->appends(Request::all())->links() }}
    </div>
</div>
