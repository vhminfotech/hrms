@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            {{ csrf_field() }}
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>History of Downloaded Report</h5>
                </div>
                 <div class="ibox-content">
                       {{ Form::open( array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'ticketSystem' )) }}  
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Department</label>
                                <div class="col-sm-9">
                                    <select class="form-control dept_id"  id="dept_id" name="dept_id">
                                        <option value="" selected="selected">Select Department</option>
                                        <option value="all">All Departments</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="emparray" id="emparray" class="emparray">
                            <input type="hidden" name="downloadstatus" id="downloadstatus" class="downloadstatus">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Employees</label>
                                <div class="col-sm-9">
                                    <select class="form-control emp_id" id="emp_id" name="emp_id">
                                        <option value="">Select Employee</option>
                                        <option value="all">All Employee</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button class="btn btn-sm btn-primary downloadPdf" type="button">Download Pdf</button>
                                </div>
                            </div>
                    </div>
            </div>
        </div>
    </div>
     <div class="row">
        <div class="col-lg-12">
            {{ csrf_field() }}
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>History of Downloaded Report</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-company" >
                            <thead>
                                <tr>
                                    <th>Number of report</th>
                                    <th>Download Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($holidayArray as $row => $val)
                                <tr>
                                    <td> {{ $val['holiday_report_number'] }}</td>
                                    <td> {{ $val['download_date'] }}</td>
                                    <td class="center">
                                      
                                        <a href="#deleteModel" data-toggle="modal" data-id="{{ $val['id'] }}" class="link-black text-sm ticketDelete" data-original-title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
