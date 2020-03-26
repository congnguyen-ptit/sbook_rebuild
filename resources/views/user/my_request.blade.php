@extends('layout.app')

@section('header')
    @parent
    <!-- breadcrumbs-area-start -->
    <div class="breadcrumbs-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-menu">
                        <ul>
                            <li><a href="{{ asset('/') }}">{{ __('settings.default.home') }}</a></li>
                            <li><a href="#" class="active">{{ __('settings.req') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->
    <!-- entry-header-area-start -->
    <div class="entry-header-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header-title">
                        <h2>{{ __('settings.request.myReq') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->
@endsection

@section('content')
<!-- cart-main-area-start -->
<div class="cart-main-area mb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('layout.notification')
                <div class="table-content table-responsive">
                    @if ($books->total())
                    <table>
                        <thead>
                            <tr>
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
                            @foreach ($books as $book)
                                @if ($book->book)
                                <tr>
                                    <td class="product-thumbnail">
                                        @if ($book->book->medias->count() > 0)
                                            <img src="{{ asset(config('view.image_paths.book') . $book->book->medias[0]->path) }}" alt="man" />
                                        @else
                                            <img src="{{ asset(config('view.image_paths.book') . 'default.jpg') }}" alt="woman" />
                                        @endif
                                    </td>
                                    <td class="product-name">
                                        <a href="{{ route('books.show', $book->book->slug . '-' . $book->book_id) }}">{{ $book->book->title }}</a>
                                    </td>
                                    <td>
                                        {{ $book->days_to_read }} {{ __('settings.request.day') }}
                                    </td>
                                    <td>{{ $book->user->name }}</td>
                                    @if ($book->type != config('view.request.waiting'))
                                            <td>
                                                <p>{{ $book->updated_at ? $book->updated_at->format('d/m/y h:i:s') : '' }}</p>
                                                {{ setTimeDefault($book->updated_at) }}
                                            </td>
                                            <td>
                                                @if($book->type != config('view.request.abtExpire'))
                                                    {{ getDateReturn($book) }}
                                                @endif
                                            </td>
                                    @else
                                        <td></td>
                                        <td></td>
                                    @endif
                                    <td class="type">
                                        <label class="stt bg-{{ $book->type }}">
                                            {{ $book->type != config('view.request.returned') ? translate($book->type) : __('settings.book.returned') }}
                                        </label>
                                    </td>
                                    @if ($book->type == config('view.request.returned') || $book->type == config('view.request.cancel'))
                                        <td></td>
                                    @elseif ($book->type == config('view.request.reading') || $book->type == config('view.request.returning'))
                                        <td>
                                            {!! Form::open(['method' => 'patch', 'route' => ['my-request.update', $book->id], 'id' => $book->id]) !!}
                                                {!! Form::hidden('status', $book->type) !!}
                                                @if ($book->type == config('view.request.reading'))
                                                    {!! Form::button(__('settings.request.returned'),
                                                        ['class' => 'btn btn-return btn-sm notify-2',
                                                        'type' => 'submit', 'disabled' => 'disabled']) !!}
                                                @else
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
                     <div class="page-numbers">
                        {{ $books->links() }}
                    </div>
                    @else
                        <p class="nodata">{{ __('settings.request.nodata') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- cart-main-area-end -->
@endsection

@section('footer')
    @parent
@endsection
