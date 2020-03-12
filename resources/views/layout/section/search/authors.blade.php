<ul class="suggestions-list">
    @if (count($authors) > 0)
        @foreach ($authors as $author)
            <li class="result-entry" data-suggestion="Target 1" data-position="1"data-type="type" data-analytics-type="merchant">
                <a href="{{ route('books.show', $author->slug . '-' . $author->id) }}" class="result-link">
                    <div class="media">
                        <div class="left-media">
                            @if ($author->medias->count() > 0)
                                <img src="{{ asset(config('view.image_paths.book') . $author->medias[0]->path) }}" alt="item" class="search-avatar"/>
                            @else
                                <img src="{{ asset(config('view.image_paths.book') . 'default.jpg') }}" alt="woman" class="search-avatar"/>
                            @endif
                        </div>
                        <div class="search-media">
                            <h4 class="media-heading">{{ $author->title }}</h4>
                            <p>{{ $author->author }}</p>
                            <div class="user-item">
                                @if ($author->owners)
                                    @foreach ($author->owners as $owner)
                                        <div class="reviews-actions" id="{{ 'user-' . $owner->id }}">
                                            @if (!empty($owner->office->name))
                                                <a class="search-img" href="{{ route('user', $owner->id) }}"
                                                   title="{{ $owner->name }} ( {{ $owner->office->name }} )">
                                                    @if ($owner->avatar != '')
                                                        <img class="user-search" src="{{ $owner->avatar }}" onerror="this.onerror=null;this.src='http://edev.framgia.vn//assets/user_avatar_default-bc6c6c40940226d6cf0c35571663cd8d231a387d5ab1921239c2bd19653987b2.png';">
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
        {{ $authors->appends(Request::all())->links() }}
    </div>
</div>
