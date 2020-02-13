@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            {{ csrf_field() }}
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Payroll Setting List</h5>
                     <div class="ibox-tools">
                            <a href="#updateBankModel" data-toggle="modal" class="btn btn-primary dim" ><i class="fa fa-plus"> Add</i></a>
                        </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <div class="ibox-tools col-sm-12">
                             <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                
                            </div>
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="payrollSetting">
                            <thead>
                                <tr>
                                    <th>Grade</th>
                                    <th>Basic Salary</th>
                                    <th>Salary Payment Date</th>
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



<div id="updateBankModel" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Bank Details</h4>
            </div>
            {{ Form::open( array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'addGrade' )) }}
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Grade</label>
                            <div class="col-sm-9">
                                <input type="text" name="grade"  class="form-control">
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Basic Salary</label>
                            <div class="col-sm-9">
                                <input type="text" name="basic_salary"  class="form-control">
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-sm-2 control-label">Salary Payment Date</label>
                            <div class="col-sm-9">
                                @php
                                    $data=['01/Month','02/Month','03/Month','04/Month','05/Month','06/Month','07/Month','08/Month','09/Month','10/Month','11/Month','12/Month','13/Month','14/Month','15/Month','16/Month','17/Month','18/Month','19/Month','20/Month','21/Month','22/Month','23/Month','24/Month','25/Month','26/Month','27/Month','28/Month','29/Month','30/Month','31/Month',];
                                @endphp
                                   
                                    <select class="form-control" name="payment_date" >
                                        <option value="">Select Salary Payment Date</option>
                                         @for($i = 0; $i < count($data) ; $i++)
                                            <option value="{{ $data[$i]}}">{{ $data[$i]}}</option>
                                         @endfor
                                    </select> 
                                    
                            </div>
                        </div>
                     </div>
                 </div>
             </div>
              <div class="modal-footer">
            <div class="col-lg-offset-1 col-lg-11">
                &nbsp;&nbsp;&nbsp; 
                <button class="btn btn-sm btn-defaut pull-right m-l" data-dismiss="modal">Close</button>
                <button class="submitbtn btn btn-sm btn-primary pull-right updatetask" type="submit">Save</button>
            </div>
        </div>
         {{ Form::close() }}
    </div>
</div>
</div>


@endsection
