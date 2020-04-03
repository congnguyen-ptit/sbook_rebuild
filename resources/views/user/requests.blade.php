<div class="table-content table-responsive">
    @if ($books->total())
    <table>
        <thead>
            <tr>
                <th><b>#</b></th>
                <th class="product-thumbnail"><b>{{ __('settings.request.image') }}</b></th>
                <th class="product-name"><b>{{ __('settings.request.title') }}</b></th>
                <th class="product-remove"><b>{{ __('settings.request.dayRead') }}</b></th>
                <th class="product-remove"><b>{{ __('settings.request.user') }}</b></th>
                <th class="product-name"><b>{{ __('settings.request.date') }}</b></th>
                <th class="product-name"><b>{{ __('settings.request.timeRemain') }}</b></th>
                <th class="product-price"><b>{{ __('settings.request.status') }}</b></th>
                <th class="product-remove"><b>{{ __('settings.request.action') }}</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $index => $book)
                @if ($book->book)
                <tr>
                    <td>{{ currentIndex($books->currentPage(), config('view.paginate.book_request'), $index) }}</td>
                    <td class="product-thumbnail">
                        @if ($book->book->medias->count() > 0)
                            <img src="{{ asset(config('view.image_paths.book') . $book->book->medias[0]->path) }}" alt="man" />
                        @else
                            <img src="{{ asset(config('view.image_paths.book') . 'default.jpg') }}" alt="woman" />
                        @endif
                    </td>
                    <td class="product-name">
                        <a href="{{ route('books.show', $book->book->slug . '-' . $book->book_id) }}" class="hover-text">{{ $book->book->title }}</a>
                    </td>
                    <td>
                        {{ $book->days_to_read }} {{ __('settings.request.day') }}
                    </td>
                    <td><a href="{{ route('user', $book->user->id) }}" class="hover-text">{{ $book->user->name }}</a></td>
                    @if ($book->type != config('view.request.waiting'))
                            <td>
                                <p>{{ $book->updated_at ? $book->updated_at->format('d/m/Y') : '' }}</p>
                                {{ setTimeDefault($book->updated_at) }}
                            </td>
                            <td>
                                @if($book->type != config('view.request.abtExpire'))
                                    {{ getDateReturn($book) }}
                                    <br>
                                    @if(whenBorrowingExpired($book) && $book->type == config('view.request.reading'))
                                        {{ whenBorrowingExpired($book) }}
                                    @elseif(whenExpired($book) && $book->type == config('view.request.returned'))
                                        {{ whenExpired($book) }}
                                    @endif
                                @endif
                            </td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                    <td class="type">
                        @if(whenBorrowingExpired($book) && $book->type == config('view.request.reading'))
                            <label class="stt bg-danger">
                                @lang('settings.book.expired')
                            </label>
                        @else
                            <label class="stt bg-{{ $book->type }}">
                                {{ $book->type != config('view.request.returned') ? translate($book->type) : __('settings.book.returned') }}
                            </label>
                        @endif
                    </td>
                    @if ($book->type == config('view.request.returned') || $book->type == config('view.request.cancel'))
                        <td></td>
                    @elseif ($book->type == config('view.request.reading') || $book->type == config('view.request.returning'))
                        <td>
                            {!! Form::open(['method' => 'patch', 'route' => ['my-request.update', $book->id], 'id' => $book->id]) !!}
                                {!! Form::hidden('status', $book->type) !!}
                                @if ($book->type !== config('view.request.reading'))
                                    {!! Form::button(__('settings.request.accept'),
                                        ['class' => 'btn btn-return btn-sm notify-2',
                                        'type' => 'submit']) !!}
                                @endif
                            {!! Form::close() !!}
                        </td>
                    @elseif ($book->type == config('view.request.abtExpire'))
                        <td>
                            {!! Form::open(['method' => 'post', 'route' => ['handle-expire'], 'id' => $book->id]) !!}
                                {!! Form::hidden('status', $book->type) !!}
                                {!! Form::hidden('id', $book->id) !!}
                                {!! Form::hidden('type', __('settings.request.approve')) !!}
                                {!! Form::button(__('settings.request.approve'), ['class' => 'btn btn-info btn-sm approve notify-2 ' , 'type' => 'submit']) !!}
                            {!! Form::close() !!}
                            {!! Form::open(['method' => 'post', 'route' => ['handle-expire'], 'id' => $book->id]) !!}
                                {!! Form::hidden('status', $book->type) !!}
                                {!! Form::hidden('id', $book->id) !!}
                                {!! Form::hidden('type', __('settings.request.dismiss')) !!}
                                {!! Form::button(__('settings.request.dismiss'), ['class' => 'btn btn-danger btn-sm approve notify-2 ' , 'type' => 'submit']) !!}
                            {!! Form::close() !!}
                        </td>
                    @else
                        <td class="product-remove">
                            {!! Form::open(['method' => 'patch', 'route' => ['my-request.update', $book->id], 'id' => $book->id]) !!}
                                {!! Form::hidden('status', $book->type) !!}
                                {!! Form::button(__('settings.request.approve'), ['class' => 'btn btn-info btn-sm approve notify-2 ' . (in_array($book->book_id, $bookStatus) ? 'disabled' : ''), 'type' => 'submit']) !!}
                            {!! Form::close() !!}
                            {!! Form::open(['method' => 'patch', 'route' => ['my-request.update', $book->id], 'id' => $book->id]) !!}
                                {!! Form::hidden('status', 'dismiss') !!}
                                {!! Form::button(__('settings.request.dismiss'), ['class' => 'btn btn-dismiss btn-sm notify-2', 'type' => 'submit']) !!}
                            {!! Form::close() !!}
                        </td>
                    @endif
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <div>
        <p class="pull-left">
            @if($books->count() >0)
                {{ __('settings.showing')  }}
                {{ ($books->currentpage() - 1) * $books->perpage() + 1 }} {{ __('settings.to') }} {{ ($books->currentpage() - 1) * $books->perpage() + $books->count() }} {{ __('settings.of') }} {{ $books->total() }} {{ __('settings.items') }}
            @endif
        </p>
    </div>
     <div class="page-numbers">
        {{ $books->appends(request()->all())->links() }}
    </div>
    @else
        <p class="nodata">{{ __('settings.request.nodata') }}</p>
    @endif
</div>
