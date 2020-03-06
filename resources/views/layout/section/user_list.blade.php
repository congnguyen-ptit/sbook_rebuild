@if (isset($data) && count($data))
        <div class="book-status">
            <div class="row">
                @foreach ($data as $user)
                    <div class="col-sm-6 col-md-4">
                        <div class="d-flex exhibition-item user">
                            <a href="{{ route('user', $user->id) }}" class="a-follow">
                                <img src="{{ ($user->avatar) ? $user->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                            </a>
                            <div class="user-info overflow-hidden">
                                <a href="{{ route('user', $user->id) }}" class="link"><b>{{ $user->name }}</b></a>
                                <div class="subscribe">
                                    @if (in_array($user->id, $followingIds))
                                        <button data-id="{{ $user->id }}" class="btn btn-follow following">{{ trans('settings.profile.following') }}</button>
                                    @elseif (Auth::id() == $user->id)
                                    @else
                                        <button data-id="{{ $user->id }}" class="btn btn-follow  follow">{{ trans('settings.profile.follow') }}</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    <div class="text-right">
        <div class="page-number">
            <ul class="pagination">
                {{ $data->appends(Request::all())->links() }}
            </ul>
        </div>
    </div>
@else
<div class="alert alert-info">
    {{ trans('settings.book.no_user') }}
</div>
@endif
<div class="pagination-area"></div>
