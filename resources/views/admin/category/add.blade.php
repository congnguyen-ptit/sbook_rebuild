@extends('admin.layout.main')

@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title m-subheader__title--separator">{{ __('admin.cate.newCate') }}</h3>
                </div>
            </div>
        </div>

        <div class="m-content">
            <div class="row">
                <div class="col-md-12">
                    @include('admin.layout.notification')
                    <div class="m-portlet m-portlet--tab">
                        {!! Form::open(['method' => 'POST', 'route' => ['category.store'], 'class' => 'm-form m-form--fit m-form--label-align-right']) !!}
                            <div class="m-portlet__body">
                                <div class="form-group m-form__group row">
                                    {!! Form::label('name', __('admin.cate.name'), ['class' => 'col-2 required']) !!}
                                    <div class="col-10">
                                        {!! Form::text('name', null, ['class' => 'form-control m-input', 'placeHolder' => __('admin.cate.placeHolder.title'), 'required']) !!}
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    {!! Form::label('description', __('admin.description'), ['class' => 'col-2 required']) !!}
                                    <div class="col-10">
                                        {!! Form::textarea('description', null, ['id' => 'mytextarea', 'required' => 'required']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <div class="row">
                                        <div class="col-12">
                                            {!! Form::submit(__('admin.submit'), ['class' => 'btn btn-success']) !!}
                                            {!! Form::reset(__('admin.reset'), ['class' => 'btn btn-secondary']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
