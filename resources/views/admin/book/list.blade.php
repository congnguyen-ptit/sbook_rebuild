@extends('admin.layout.main')

@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title m-subheader__title--separator">{{ __('admin.book.book') }}</h3>
                </div>
                <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                    <a href="{{ route('book.create') }}"
                       class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                            <span>
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                <span>{{ __('admin.addNew') }}</span>
                            </span>
                    </a>
                    <div class="m-separator m-separator--dashed d-xl-none"></div>
                </div>
            </div>
        </div>

        <div class="m-content">
            @include('admin.layout.notification')
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                {{ __('admin.list') }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
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
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                        <thead class="text-center">
                            <tr>
                                <th title="{{ __('admin.book.title') }}">
                                    {{ __('admin.book.title') }}
                                    <span class="sort">
                                        <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                                    </span>
                                </th>
                                <th title="{{ __('admin.book.author') }}">
                                    {{ __('admin.book.author') }}
                                    <span class="sort">
                                        <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                                    </span>
                                </th>
                                <th title="{{ __('admin.book.publishDate') }}">
                                    {{ __('admin.book.publishDate') }}
                                    <span class="sort">
                                        <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                                    </span>
                                </th>
                                <th title="{{ __('admin.book.totalPage') }}">
                                    {{ __('admin.book.totalPage') }}
                                    <span class="sort">
                                        <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                                    </span>
                                </th>
                                <th title="{{ __('admin.book.avgStar') }}">
                                    {{ __('admin.book.avgStar') }}
                                    <span class="sort">
                                        <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                                    </span>
                                </th>
                                <th title="{{ __('admin.book.view') }}">
                                    {{ __('admin.book.view') }}
                                    <span class="sort">
                                        <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                                    </span>

                                </th>
                                <th title="{{ __('admin.action') }}">
                                    {{ __('admin.action') }}
                                    <span class="sort">
                                        <i class="fa fa-long-arrow-alt-up"></i><i class="fa fa-long-arrow-alt-down"></i>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

{{ Html::script('assets/admin/js/sweetalert2.js') }}
{{ Html::script('admin_asset/assets/vendors/custom/datatables/datatables.bundle.js') }}
{{ Html::script('assets/admin/js/ajax-server-side.js') }}

@endsection
