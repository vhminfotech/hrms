@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Add Leave</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        
                    </div>
                </div>
                <div class="ibox-content">
                    {{ Form::open( array('method' => 'post', 'class' => 'form-horizontal','files' => true, 'id' => 'addLeave' )) }}
              
                    <div class="form-group" hidden>
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="empid" value="{{ $emp_id }}" class="form-control">
                                    <input type="text" name="company_id" value="{{ $company_id }}" class="form-control">
                                    
                                </div>
                    </div>
                    <input type="hidden" name="editId" value="{{ isset($leaveEdit) && !empty($leaveEdit['id']) ? $leaveEdit['id'] : '' }}">
                    <div class="form-group" id="data_1">
                        <label class="col-sm-2 control-label">Start Date</label>
                        <div class="col-sm-9"> 
                                    <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="start_date" value="{{ isset($leaveEdit) && !empty($leaveEdit['start_date']) ? date('d-m-Y',strtotime( $leaveEdit['start_date'])) : '' }}" id="startDate" placeholder="start date" class="form-control startDate dateField" autocomplete="off">
                                </div>
                        </div>
                    </div>

   
                    <div class="form-group" id="data_1">
                        <label class="col-sm-2 control-label">End Date</label>
                        <div class="col-sm-9"> 
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="end_date" value="{{ isset($leaveEdit) && !empty($leaveEdit['end_date']) ?  date('d-m-Y',strtotime( $leaveEdit['end_date']))  : '' }}" id="endDate" placeholder="Select End Date " class="form-control endDate dateField" autocomplete="off">
                            </div>
                        </div>
                    </div>
                  
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Type of Request</label>
                        <div class="col-sm-9">
                            <select class="c-select form-control typeRequest" id="typeRequest" name="typeRequest">
                                <option value="">Select Type of Request</option>
                                @if(isset($leaveEdit) && !empty($leaveEdit['type_of_req_id']))
                                    
                                        @for($i = 0 ; $i < count($type_of_request_new) ; $i++)
                                            <option value="{{ $type_of_request_new[$i]['id'] }}" {{ ( $type_of_request_new[$i]['id'] == $leaveEdit['type_of_req_id'] ? 'selected="selected"' : '') }}  >{{ $type_of_request_new[$i]['leave_cat_name'] }}</option>
                                        @endfor
                                @else
                                    @for($i = 0 ; $i < count($type_of_request_new) ; $i++)
                                     <option value="{{ $type_of_request_new[$i]['id'] }}">{{ $type_of_request_new[$i]['leave_cat_name'] }}</option>
                                @endfor
                                @endif
                                
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group requestNameTextBox " style="display: none;">
                                <label class="col-sm-2 control-label">Request Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="request_name" id="request_name" placeholder="Enter Request Name" class="form-control request_name">
                                </div>
                            </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Reason</label>
                        <div class="col-sm-9"> 
                            {{ Form::textarea('reason', isset($leaveEdit) && !empty($leaveEdit['reason']) ? $leaveEdit['reason'] : '', array('class' => 'form-control reason')) }}
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