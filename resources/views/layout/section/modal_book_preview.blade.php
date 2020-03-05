<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
    <h4 class="modal-title text-left h4">
        {{ trans('settings.book.titleModalSame') }}
    </h4>
</div>
<div class="modal-body">
    <div class="row mb-30">
        <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="modal-tab">
                <div class="product-details-large tab-content">
                    <div class="tab-pane active" id="image-1">
                        <div class="text-center">
                            <img class="mh-15" src="{{ asset(config('view.image_paths.book') . ($book->medias->count() > 0 ? $book->medias[0]->path : 'default.jpg')) }}" alt="book"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="modal-pro-content">
                <h5>{{ $book->title }}</h5>
                <div class="single-info">{{ trans('settings.book.author') }}  {{ $book->author }}</div>
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
                <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}" class="btn btn-info pull-right">
                    {{ trans('settings.book.i_have_this_book') }}
                </a>
            </div>
        </div>
    </div>
</div>