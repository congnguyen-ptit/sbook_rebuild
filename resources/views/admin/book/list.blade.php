@extends('admin.layout.main')

@section('progress_bar')
    <li><a href="{{ route('admin.dashboard') }}">{{ trans('page.home') }}</a></li>
    <li class="active">{{ trans('admin.book.books') }}</li>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <div class="col-md-6">
                <h6 class="panel-title text-semibold">{{ trans('admin.book.books') }}</h6>
            </div>
            <div class="col-md-6 text-right mb-15">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#book_create_model"><i class="fas fa-plus"></i> {{ trans('admin.book.book_detail.add') }}</button>
            </div>
            <div class="col-md-12">
                @include('admin.layout.notification')
            </div>
        </div>

        @include('admin.layout.notification')

        <div class="panel-body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="bookList">
                <thead class="text-center">
                <tr>
                    <th title="{{ trans('admin.book.title') }}">
                        {{ trans('admin.book.title') }}
                        <span class="sort">
                            <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                        </span>
                    </th>
                    <th title="{{ trans('admin.book.author') }}">
                        {{ trans('admin.book.author') }}
                        <span class="sort">
                            <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                        </span>
                    </th>
                    <th title="{{ trans('admin.book.avgStar') }}">
                        {{ trans('admin.book.avgStar') }}
                        <span class="sort">
                            <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                        </span>
                    </th>
                    <th title="{{ trans('admin.book.view') }}">
                        {{ trans('admin.book.view') }}
                        <span class="sort">
                            <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                        </span>

                    </th>
                    <th title="{{ trans('admin.action') }}">
                        {{ trans('admin.action') }}
                        <span class="sort">
                            <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                        </span>
                    </th>
                </tr>
                </thead>
            </table>

            {{-- modal create --}}
            <div id="book_create_model" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header bg-primary">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h6 class="modal-title">{{ trans('admin.book.book_detail.add') }}</h6>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{{ trans('admin.book.title') }}</label>
                                        <div class="col-lg-10">
                                            <input name="title" type="text" class="form-control mb-10" placeholder="{{ trans('admin.book.placeHolder.title') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.categories') }}</label>
                                        <div class="col-lg-10">
                                            <select class="form-control mb-10" name="categories[]" multiple>
                                                @foreach($categories as $cate)
                                                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.authors') }}</label>
                                        <div class="col-lg-10">
                                            <input name="author" type="text" class="form-control mb-10" placeholder="{{ trans('admin.book.placeHolder.author') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">{{ trans('admin.book.book_detail.new_images') }}</label>
                                        <div class="col-lg-10">
                                            <input type="file" name="newImages[]" class="file-input" multiple="multiple" data-show-upload="false" data-show-caption="true" data-show-preview="true" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.description') }}</label>
                                        <div class="col-lg-10">
                                            <textarea name="description" rows="5" cols="5" class="form-control mt-10" placeholder="{{ trans('admin.book.placeHolder.description') }}"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div id="description"></div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">{{ trans('admin.book.book_detail.close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ trans('admin.book.book_detail.submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- end modal create --}}

            {{-- modal detail --}}
            <div id="book_detail_model" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h6 class="modal-title"></h6>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <img id="book_img" class="book-image-detail" src="" alt="">
                                </div>
                                <div class="col-md-6">
                                    <div class="single-info">
                                        <b>{{ trans('admin.book.book_detail.categories') }}:</b>
                                        <div id="listCategoies"></div>
                                    </div>
                                    <div class="single-info">
                                        <b>{{ trans('admin.book.book_detail.authors') }}:</b>
                                        <span id="author"></span>
                                    </div>
                                    <div class="single-info">
                                        <b>{{ trans('admin.book.book_detail.sku') }}:</b>
                                        <span id="sku"></span>
                                    </div>
                                    <div class="single-info">
                                        <b>{{ trans('admin.book.book_detail.avg_star') }}:</b>
                                        <span id="avg_star"></span>
                                    </div>
                                    <div class="single-info">
                                        <b>{{ trans('admin.book.book_detail.view') }}:</b>
                                        <span id="view"></span>
                                    </div>
                                </div>
                            </div>

                            <div id="description"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">{{ trans('admin.book.book_detail.close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end modal detail --}}

            {{-- modal update --}}
            <div id="book_update_model" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <div class="modal-header bg-primary">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h6 class="modal-title"></h6>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{{ trans('admin.book.title') }}</label>
                                        <div class="col-lg-10">
                                            <input id="book_name" name="title" type="text" class="form-control mb-10" placeholder="{{ trans('admin.book.placeHolder.title') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.categories') }}</label>
                                        <div class="col-lg-10">
                                            <select class="form-control mb-10" name="categories[]" multiple>
                                                @foreach($categories as $cate)
                                                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.authors') }}</label>
                                        <div class="col-lg-10">
                                            <input id="author" name="author" type="text" class="form-control mb-10" placeholder="{{ trans('admin.book.placeHolder.author') }}">
                                        </div>
                                    </div>

                                    <div class="form-group" id="listImageBookDiv">
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
                                                <tbody id="listBookImages"></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">{{ trans('admin.book.book_detail.new_images') }}</label>
                                        <div class="col-lg-10">
                                            <input type="file" name="newImages[]" class="file-input" multiple="multiple" data-show-upload="false" data-show-caption="true" data-show-preview="true" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{{ trans('admin.book.book_detail.description') }}</label>
                                        <div class="col-lg-10">
                                            <textarea id="description" name="description" rows="5" cols="5" class="form-control mt-10" placeholder="{{ trans('admin.book.placeHolder.description') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">{{ trans('admin.book.book_detail.close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ trans('admin.book.book_detail.submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- end modal update --}}
        </div>
    </div>
@endsection

@section('script')

{{ Html::script('assets/admin/js/books.js') }}

@endsection
