@extends('layouts.app')
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Add New Advance Salary Request</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        
                    </div>
                </div>
                <div class="ibox-content">
                    {{ Form::open( array('method' => 'post', 'class' => 'form-horizontal','files' => true, 'id' => 'addNewRequest' )) }}
                    <div class="form-group" id="data_1" hidden>
                        <label class="col-sm-2 control-label">Employee Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="dep_id" placeholder="Employee Name" class="dep_id form-control" value="" id="dep_id" readonly>
                            <input type="text" name="cmp_id" placeholder="Employee Name" class="cmp_id form-control" id="cmp_id" value="" readonly>
                            <input type="text" name="emp_name" placeholder="" class="empname" id="empname" value="" readonly>
                            
                        </div>
                    </div>
                    <div class="form-group" id="data_1">
                        <label class="col-sm-2 control-label">Employee Name</label>
                        <div class="col-sm-9">
                            <select class="form-control empName" id="empName" name="empName">
                                        <option value="">Select Employee</option>
                                        @foreach($getAllEmpOfCompany as $emp)
                                            <option data-dept="{{ $emp->department }}" data-comp="{{ $emp->company_id }}" value="{{ $emp->id }}">{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                        </div>
                    </div>
                    
                    
                    <div class="form-group" id="data_1">
                        <label class="col-sm-2 control-label">Employee Id</label>
                        <div class="col-sm-9">
                            <input type="text" id="emp_id" name="emp_id" placeholder="Employee Id" class="emp_id form-control" value="" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group" id="data_1">
                        <label class="col-sm-2 control-label">Date Of Submit</label>
                        <div class="col-sm-9">
                            <div class="input-group from_date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="date_of_submit" id="date" value="" class="form-control from_date dateField" placeholder="Date Of Submit" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    
                  
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Comments</label>
                        <div class="col-sm-9"> 
                            {{ Form::textarea('comments', '', array('class' => 'form-control reason' ,'required')) }}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Upload File</label>
                        <div class="col-sm-9"> 
                            <input type="file" name="files" placeholder="Upload Files" class="form-control">
                        </div>
                    </div>
                    	
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-9">
                            <button class="btn btn-sm btn-primary" type="submit">Save</button>
                        </div>
                    </div>
                  
                  
                    {{ Form::close() }}
                </div>
            </div>
        </div>	
    </div>
</div>
<style>
    input.has-error {
        border-color: red;
    }
    .has-error .select2,.has-error .select2-selection{
        color: red !important;
        border-color: red !important;
    }

</style>
@endsection
