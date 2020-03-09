<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
    <h4 class="modal-title text-left h4">{{ $book->title }}</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="modal-tab">
                <div class="product-details-large tab-content">
                    <div class="tab-pane active" id="image-1">
                        <div class="text-center">
                            <img src="{{ asset(config('view.image_paths.book') . ($book->medias->count() > 0 ? $book->medias[0]->path : 'default.jpg')) }}" alt="book" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="modal-pro-content">
                <div class="modal-info">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="single-info">
                                <b>{{ __('settings.modal.share_by') }}</b>
                                <div class="owner-avatar">
                                    @if (count($book->owners) > 0)
                                        @foreach ($book->owners as $owner)
                                            <div class="owner" id="{{ 'user-' . $owner->id }}">
                                                <a href="{{ route('user', $owner->id) }}" title="{{ $owner->name ? $owner->name : '' }} ({{ $owner->office ? $owner->office->name : '' }})">
                                                    <img src="{{ $owner->avatar ? $owner->avatar : asset(config('view.image_paths.user') . '1.png') }}" class="owner-avatar-icon" onerror="this.onerror=null;this.src={{ config('view.links.avatar') }};">
                                                </a>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-danger">{{ __('settings.modal.no_owners') }}</span>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <div class="single-info">
                                <b>{{ __('settings.modal.rating') }}</b>
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
                            <div class="single-info">
                                <b>{{ __('settings.modal.count_review') }}</b>
                                <span>
                                    {{ $book->reviews->count() ?? 0 }}
                                </span>
                            </div>
                            <div class="single-info">
                                <b>{{ __('settings.modal.view') }}</b>
                                <span>{{ $book->count_viewed ? $book->count_viewed : '0' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="single-info">
                                <b>{{ __('settings.modal.category') }}</b>
                                @foreach ($book->categories as $cate)
                                    <li>
                                        <a href="{{ route('book.category', $cate->slug . '-' . $cate->id) }}" class="text-info">
                                            {{ $book->categories->count() > 0 ? $cate->name : __('settings.modal.no_category') }}
                                        </a>
                                    </li>
                                @endforeach
                            </div>
                            <div class="single-info">
                                <b>{{ __('settings.modal.author') }}</b>
                                <span>{{ $book->author ? $book->author : __('settings.modal.no_author') }}</span>
                            </div>
                            <div class="single-info">
                                <b>{{ __('settings.modal.sku') }}</b>
                                <span>{{ $book->sku ? $book->sku : __('settings.modal.no_sku') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="modal-description">
                <p>{!! strip_tags($book->description) !!}</p>
            </div>
            <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}" class="btn btn-info show-more">
                {{ trans('settings.book.show_more') }}
            </a>
        </div>
    </div>
</div>