@extends('admin.layout.main')

@section('progress_bar')
    <li><a href="{{ route('admin.dashboard') }}">{{ trans('page.home') }}</a></li>
    <li><a href="{{ route('admin.books.index') }}">{{ trans('admin.book.books') }}</a></li>
    <li class="active">{{ $book->title }}</li>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <div class="col-md-6">
                <h6 class="panel-title text-semibold">{{ $book->title }}</h6>
            </div>
        </div>

        @include('admin.layout.notification')

        <div class="panel-body" id="book_update_model">
            <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="control-label col-lg-2">{{ trans('admin.book.title') }}</label>
                    <div class="col-lg-10">
                        <input name="title" type="text" class="form-control mb-10" value="{{ $book->title }}" placeholder="{{ trans('admin.book.placeHolder.title') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.categories') }}</label>
                    <div class="col-lg-10">
                        <select class="form-control mb-10" name="categories[]" multiple>
                            @foreach ($categories as $cate)
                                <option value="{{ $cate->id }}"
                                    @foreach ($book->categories as $chosenCate)
                                        @if ($chosenCate->id == $cate->id) selected @endif
                                    @endforeach>
                                    {{ $cate->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.authors') }}</label>
                    <div class="col-lg-10">
                        <input name="author" type="text" class="form-control mb-10" value="{{ $book->author }}" placeholder="{{ trans('admin.book.placeHolder.author') }}">
                    </div>
                </div>

                @if ($book->medias && count($book->medias))
                    <div class="form-group">
                        <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.list_images') }}</label>
                        <div class="col-lg-10">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ trans('admin.book.book_detail.main_image') }}</th>
                                    <th>{{ trans('admin.book.book_detail.select_image') }}</th>
                                    <th>{{ trans('admin.book.book_detail.image') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($book->medias as $image)
                                        <tr>
                                            <td><input type="radio" name="main_image" value="{{ $image->id }}" @if ($image->priority == \App\Eloquent\Media::TYPE_PRIORITY_MAIN) checked @endif></td>
                                            <td><input type="checkbox" name="old_image[]" checked value="{{ $image->id }}"></td>
                                            <td><img class="w-20" src="{{ '/' . config('view.image_paths.book') . $image->path }}" alt="{{ $image->path }}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label class="col-lg-2 control-label">{{ trans('admin.book.book_detail.new_images') }}:</label>
                    <div class="col-lg-10">
                        <input type="file" name="newImages[]" class="file-input" multiple="multiple" data-show-upload="false" data-show-caption="true" data-show-preview="true" accept="image/*">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.description') }}</label>
                    <div class="col-lg-10">
                        <textarea name="description" rows="5" cols="5" class="form-control mt-10" placeholder="{{ trans('admin.book.placeHolder.description') }}">{{ $book->description }}</textarea>
                    </div>
                </div>
                <div class="text-right">
                    <button type="reset" class="btn btn-gray mt-15">{{ trans('admin.book.book_detail.reset') }}</button>
                    <button type="submit" class="btn btn-primary mt-15">{{ trans('admin.book.book_detail.submit') }} <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')

    {{ Html::script('assets/admin/js/books.js') }}

@endsection
