<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>S*Book</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" type="image/png" href="{{ config('view.links.icon_header') }}">

        @include('layout.style')

        {{ Html::script('assets/js/vendor/modernizr-2.8.3.min.js') }}
        @routes()

    </head>
    <body>
        @include('layout.header')
            @section('header')
                @show

        @yield('content')

        @include('layout.footer')
            @section('footer')
                @show
        <div class="modal fade" id="book-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="modal-content"></div>
            </div>
        </div>
        <div class="modal fade" id="modal-extend" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" id="extend-content">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modelTitleId">
                                <span class="fa fa-warning"></span>
                                {{ trans('settings.book.pickDate') }}
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row form-group form-extend">
                                <div class="col-md-4">
                                    {{ trans('settings.book.extendMore') }}
                                </div>
                                <div class="col-md-8">
                                    {!! Form::selectRange('days_to_read', 3, 30, null, ['class' => 'form-control day', 'id' => 'days_to_read']) !!}
                                    <span>{{ trans('settings.modal.days') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btn-extend">{{ trans('settings.modal.btn_submit') }}</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('settings.modal.btn_close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::check() && $bookAboutToExpire->count() ?? 0)
            <div class="modal fade" id="bookExpire" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modelTitleId">
                                <span class="fa fa-warning"></span>
                                {{ trans('settings.default.titleModalExtend') }}
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('settings.default.book') }}</th>
                                            <th>{{ trans('settings.default.dateBorrow') }}</th>
                                            <th>{{ trans('settings.default.daysBorrow') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookAboutToExpire as $book)
                                            <tr>
                                                <td scope="row"> {{ $book->title }}</td>
                                                <td class="text-center">{{ getday($book->pivot->updated_at, 0) }}</td>
                                                <td>{{ $book->pivot->days_to_read }}</td>
                                                <td>
                                                    <button class="btn btn-sm badge-expire bg-expire extend-{{ $book->pivot->id }}" id="extend" data-id="{{ $book->pivot->id }}" data-type="true">
                                                        {{ trans('settings.default.extend') }}
                                                    </button>
                                                    <button class="btn btn-sm badge-expire bg-danger extend-{{ $book->pivot->id }}" id="not-extend" data-id="{{ $book->pivot->id }}" data-type="false">
                                                        {{ trans('settings.default.notExtend') }}
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('settings.modal.btn_close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <script type='text/javascript'>
            window.translations = {!! $translations !!}
        </script>
        @include('layout.script')
            @section('script')
                @show
    </body>
</html>
