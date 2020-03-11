@if (count($result) > 0)
    <div class="suggestion-search">
        <ul class="suggestions-list">
            <div class="tabbable-panel">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs ">
                        @foreach ($result as $key => $value)
                            <li class="{{ $key == 'titles' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#{{ $key }}" class="tab-suggest">{{ trans("settings.home.$key") }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach ($result as $key => $value)
                            <div id="{{ $key }}" class="tab-pane fade in {{ $key == 'titles' ? 'active' : '' }}">
                                @if($key == 'users')
                                    @if (count($value) > 0)
                                        @foreach ($value as $user)
                                            <li class="result-entry" data-suggestion="Target 1" data-position="1" data-type="type" data-analytics-type="merchant">
                                                <a href="{{ route('user', $user->id) }}" class="result-link">
                                                    <div class="media">
                                                        <div>
                                                            @if ($user->avatar)
                                                                <img src="{{ $user->avatar }}" alt="item" class="media-oject mg-thumbnail avatar-icon" onerror="this.onerror=null;this.src='{{ asset("img/user/avatar/default.jpg") }}';"/>
                                                            @else
                                                                <img src="{{ asset(config('view.image_paths.user') . 'default.jpg') }}" alt="woman" class="media-oject mg-thumbnail avatar-icon"/>
                                                            @endif
                                                        </div>
                                                        <div class="search-name">
                                                            <h4 class="media-heading">{{ $user->name }}</h4>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        {{ trans('settings.home.not_found') }}
                                    @endif
                                @else
                                    @if (count($value) > 0)
                                        @foreach ($value as $book)
                                            <li class="result-entry" data-suggestion="Target 1" data-position="1" data-type="type" data-analytics-type="merchant">
                                                <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}" class="result-link" title="{{ $book->title }}">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            @if ($book->medias->count() > 0)
                                                                <img src="{{ asset(config('view.image_paths.book') . $book->medias[0]->path) }}" alt="item" class="media-search"/>
                                                            @else
                                                                <img src="{{ asset(config('view.image_paths.book') . 'default.jpg') }}" alt="woman" class="media-search"/>
                                                            @endif
                                                        </div>
                                                        <div class="media-body">
                                                            <h4 class="media-book">{{ $book->title }}</h4>
                                                            <p>{{ $book->author }}</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <div class="search_found"><h5>{{ trans('settings.home.not_found') }}</div>
                                    @endif
                                @endif
                                @if (count($value) >= config('view.random_numb.itemSearch'))
                                        <div class="line"></div>
                                        <div class="suggestion-entern"><a onclick="submitForms()">{{ trans('page.clickMore') }}</a></div>
                                    @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </ul>
    </div>
@endif
