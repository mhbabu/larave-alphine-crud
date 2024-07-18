@extends('layouts.modal')
@section('title')
    <h5><i class="fa fa-plus-square"></i> Create Product</h5>
@endsection
@section('content')
    {!! html()->form('POST', route('products.store'))->class('form-horizontal')->id('dataForm')->open() !!}
    <div class="modal-body">
        <div class="col-md-4 my-2">
            {!! html()->label('Name')->class('form-label required')->for('name') !!}
            {!! html()->text('name')->class('form-control')->placeholder('Name')->attribute('maxlength', 191)->required()->autofocus() !!}
        </div>
        <div class="col-md-4 my-2">
            {!! html()->label('Leave Status')->class('form-label required')->for('leave_status') !!}
            {!! html()->select('leave_status')->options(['paid' => 'Paid', 'unpaid' => 'Unpaid'])->class('form-control')->attribute('maxlength', 191)->placeholder('Select One')->autofocus() !!}
        </div>
        <div class="col-md-4 my-2">
            {!! html()->label('Number Of Leave')->class('form-label required')->for('number_of_leave') !!}
            {!! html()->number('number_of_leave')->class('form-control')->attribute('maxlength', 191)->autofocus() !!}
        </div>
        <div class="col-md-4 my-2">
            {!! html()->label('Montly Limit')->class('form-label required')->for('montly_limit') !!}
            {!! html()->number('montly_limit')->class('form-control')->attribute('maxlength', 191)->autofocus() !!}
        </div>
        <div class="col-md-4 my-2">
            {!! html()->label('Status')->class('form-label required')->for('status') !!}
            {!! html()->select('status')->options([1 => 'Active', 0 => 'Inactive'])->class('form-control')->attribute('maxlength', 191)->required() !!}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times-circle"></i>
            Close</button>
        <button name="actionBtn" id="actionButton" type="submit" value="submit"
            class="actionButton btn btn-primary btn-sm float-right"><i class="fa fa-save"></i> Save </button>
    </div>
    {!! html()->form()->close() !!}
@endsection
