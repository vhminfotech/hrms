@extends('layouts.app')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
            {{ csrf_field() }}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Manage Attendance History</h5>
                    </div>
                    <div class="ibox-content">
                        {{ Form::open( array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'attendanceHistory' )) }}

                        <div class="form-group">
                            <label class="col-sm-2 control-label">From Date:</label>
                            <div class="col-sm-9">
                                <div class="input-group from_date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="from_date" id="from_date" placeholder="From Date" class="form-control from_date dateField" autocomplete="off" value="{{ $fromdate }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">To Date:</label>
                            <div class="col-sm-9">
                                <div class="input-group to_date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="to_date" id="to_date" placeholder="To Date" class="form-control to_date dateField" autocomplete="off" value="{{ $toDate }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Employees By Department </label>
                            <div class="col-sm-9">
                                <select class="form-control department_id" name="department_id" id="department_id">
                                    <option value="" selected="">Select Employees Of A Department</option>
                                    <option value="all" selected="">All Department</option>
                                    @foreach($departmentList as $dept)
                                        <option value="{{ $dept->id }}" {{ ( $department_id == $dept->id ? 'selected="selected"' : '') }}>{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-sm btn-primary getAttedanceHisory" type="button">Manage</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

    <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            {{ csrf_field() }}
            <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <!-- <h5>Manage Attendance History</h5> -->
                        <div class="ibox-tools">
                             <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="attendanceHistoryList">
                                <thead>
                                    <tr>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Name Of Employee</th>
                                        <th>Department Name</th>
                                        <th>Type Of Leave</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="historyDetailsModel" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="type">Type: </h3>
                            <!-- <p class="m-t-none m-b">Details:</p> -->
                            <b>Employee Name: </b><span class="m-t-none m-b empName"></span><br/>
                            <div class="leaveDiv">
                                <b>Leave Reason: </b><span class="m-t-none m-b leaveReason"></span><br/>
                            </div>
                            <div class="dateOfSubmitDiv">
                                <b>Date Of Submit: </b><span class="m-t-none m-b dateOfSubmit"></span><br/>
                            </div>
                            <div class="totalHoursDiv">
                                <b>Total Hours: </b><span class="m-t-none m-b totalHours"></span><br/>
                            </div>
                            <div class="requestDescriptionDiv">
                                <b>Request Description: </b><span class="m-t-none m-b requestDescription"></span><br/>
                            </div>
                            <div class="statusDiv">
                                <b>Status: </b><span class="m-t-none m-b status"></span><br/>
                            </div>
                            
                            <form role="form">
                                <div>
                                    <button class="btn btn-sm btn-primary pull-right m-l" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
