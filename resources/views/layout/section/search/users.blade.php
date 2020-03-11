<div class="suggestion">
    <ul class="suggestions-list">
        @if (count($users) > 0)
            @foreach ($users as $user)
                <li class="result-entry" data-suggestion="Target 1" data-position="1" data-type="type" data-analytics-type="merchant">
                    <a href="{{ route('user', $user->id) }}" class="result-link">
                        <div class="media">
                            <div>
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="item" class="media-oject mg-thumbnail avatar-icon" onerror="this.onerror=null;this.src='http://edev.framgia.vn//assets/user_avatar_default-bc6c6c40940226d6cf0c35571663cd8d231a387d5ab1921239c2bd19653987b2.png';"/>
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
    </ul>
    <div class="search-title">
        <div class="text-center">
            {{ $users->appends(Request::all())->links() }}
        </div>
    </div>
</div>
