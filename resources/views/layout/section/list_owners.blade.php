@if (count($book->owners) > 0)
    <div class="owner-avatar" id="show-owner">
        @foreach ($book->owners as $key => $owner)
            @if($key < config('view.taking_numb.showOwner'))
                <div class="owner" id="{{ 'user-' . $owner->id }}">
                    <a href="{{ route('user', $owner->id) }}" title="{{ $owner->name ? $owner->name : '' }}({{
                                                                    $owner->office ? $owner->office->name : '' }})">
                        <img src="{{ $owner->avatar ? $owner->avatar : asset(config('view.image_paths.user') . '1.png') }}" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};" class="owner-avatar-icon">
                    </a>
                </div>
            @else
                <div class="owner owner-show" id="count-owner">
                    <a href="#" id="list-owner" title="{{ trans('settings.modal.more') }}" class="owner-more" data-toggle="tooltip">
                        <span>+</span>
                        <span id="span-count" data-count="{{ $book->owners->count() - config('view.taking_numb.showOwner') }}">{{ $book->owners->count() - config('view.taking_numb.showOwner') }}</span>
                    </a>
                </div>
                @break
            @endif
        @endforeach
    </div>
    <div class="modal fade" id="modal-list-owner">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title">{{ trans('settings.book.owners') }}</h4>
                </div>
                <div class="panel">
                    <div class="panel-body scroll-h-3">
                        <ul class="list-group">
                            @foreach ($book->owners as $owner)
                                <li class="row border-bottom">
                                    <div class="col-md-4">
                                        <div id="{{ 'user-' . $owner->id }}">
                                            <a href="{{ route('user', $owner->id) }}" title="{{ $owner->name ? $owner->name : '' }}({{
                                                                    $owner->office ? $owner->office->name : '' }})">
                                                <img src="{{ $owner->avatar ? $owner->avatar : asset(config('view.image_paths.user') . '1.png') }}" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};" class="img-list">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <b>{{ $owner->name }}</b>
                                        <span class="owner-office">{{ $owner->office ? $owner->office->address : 'N/A' }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <span class="text-danger no_onwer">{{ trans('settings.modal.no_owners') }}</span>
@endif
