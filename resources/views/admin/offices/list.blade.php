@extends('admin.layout.main')

@section('progress_bar')
    <li><a href="{{ route('admin.dashboard') }}">{{ trans('page.home') }}</a></li>
    <li class="active">{{ trans('admin.office.offices') }}</li>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <div class="col-md-6">
                <h6 class="panel-title text-semibold">{{ trans('admin.office.offices') }}</h6>
            </div>
        </div>

        <div class="panel-body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="officeList">
                <thead class="text-center">
                <tr>
                    <th title="{{ trans('admin.office.name') }}">
                        {{ trans('admin.office.name') }}
                        <span class="sort">
                            <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                        </span>
                    </th>
                    <th title="{{ trans('admin.office.address') }}">
                        {{ trans('admin.office.address') }}
                        <span class="sort">
                            <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                        </span>
                    </th>
                    <th title="{{ trans('admin.office.description') }}">
                        {{ trans('admin.office.description') }}
                        <span class="sort">
                            <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                        </span>
                    </th>
                    <th></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- modal detail --}}
    <div id="office_detail_model" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title"></h6>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="single-info">
                                <b>{{ trans('admin.office.name') }}:</b>
                                <div id="name"></div>
                            </div>
                            <div class="single-info">
                                <b>{{ trans('admin.office.address') }}:</b>
                                <span id="address"></span>
                            </div>
                            <div class="single-info">
                                <b>{{ trans('admin.office.description') }}:</b>
                                <span id="description"></span>
                            </div>
                        </div>
                    </div>

                    <div id="description"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ trans('admin.office.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal detail --}}
@endsection

@section('script')

{{ Html::script('assets/admin/js/offices.js') }}

@endsection
