@extends('admin.layout.main')

@section('content')
    <div class="row">
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="text-center">{{ __('admin.user.user') }}</h3>
                </div>
            </div>
        </div>

        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">{{ __('admin.user.listUser') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Search Form -->
                    <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                        <div class="row align-items-center">
                            <div class="col-xl-8 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover table-checkable" id="list-user">
                        <thead>
                        <tr>
                            <th class="text-center" title="{{ trans('settings.admin.default.name') }}" >
                                {{ trans('settings.admin.default.name') }}
                            </th>
                            <th class="text-center" title="{{ trans('settings.admin.user.phone') }}">
                                {{ trans('settings.admin.user.phone') }}
                            </th>
                            <th class="text-center" title="{{ trans('settings.admin.user.position') }}">
                                {{ trans('settings.admin.user.position') }}
                            </th>
                            <th class="text-center w-12" title="{{ trans('settings.admin.user.roles') }}">
                                {{ trans('settings.admin.user.roles') }}
                            </th>
                            <th class="text-center" title="{{ trans('settings.admin.user.office') }}">
                                {{ trans('settings.admin.user.workspace') }}
                            </th>
                            <th class="text-center" title="{{ trans('settings.admin.user.reputation') }}">
                                {{ trans('settings.admin.user.reputation') }}
                            </th>
                            <th class="text-center" title="{{ trans('settings.admin.user.employee_code') }}">
                                {{ trans('settings.admin.user.employee_code') }}
                            </th>
                            <th class="text-center" title="{{ trans('settings.admin.default.action') }}">
                                {{ trans('settings.admin.default.action') }}
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <button type="button" id="btn-modal" class="btn btn-primary btn-lg hide" data-toggle="modal" data-target="#modelId"></button>
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelTitleId"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" id="detail-user"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('admin.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
{{ Html::script('assets/admin/js/user.js') }}
@endsection
