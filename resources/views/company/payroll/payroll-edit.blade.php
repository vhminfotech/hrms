@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		{{ Form::open( array('method' => 'post', 'class' => 'form-horizontal','files' => true, 'id' => 'editEmployee' )) }}
			<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Add form</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
						<div class="form-group">
							<label class="col-lg-3 control-label">Name</label>
							<div class="col-lg-9">
								{{ Form::text('name', $details->name , array('placeholder'=>'Name', 'class' => 'form-control first_name' ,'required')) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Father Name</label>
							<div class="col-lg-9">
								{{ Form::text('father_name', $details->father_name, array('placeholder'=>'Father Name', 'class' => 'form-control last_name' ,'required')) }}
							</div>
						</div>
						 	<div class="form-group">
						 		<label class="col-sm-3 control-label">Gender</label>
                                <div class="col-sm-9"> 
                                	{{ Form::select('gender', $genderArray , $details->gender, array('class' => 'form-control gender', 'id' => 'gender')) }}
                                </div>
                        </div>
                        <div class="form-group" id="data_1">
						 		<label class="col-sm-3 control-label">Date Of birth</label>
                                <div class="col-sm-9"> 
                                	<div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="date_of_birth" id="" placeholder="Select Date of joingng" class="form-control" value="{{  date('d-m-Y',strtotime($details->date_of_birth)) }}">
                                </div>
                                </div>
                            </div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Phone</label>
							<div class="col-lg-9">
								{{ Form::text('phone', $details->phone, array('placeholder'=>'Phone', 'class' => 'form-control last_name' ,'required')) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Local Address</label>
							<div class="col-lg-9">
								{{ Form::text('local_address', $details->local_address, array('placeholder'=>'Local Address', 'class' => 'form-control address' ,'required')) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Permanent Address</label>
							<div class="col-lg-9">
								{{ Form::text('permanent_address', $details->permanent_address, array('placeholder'=>'Permanent Address', 'class' => 'form-control permanent_address' ,'required')) }}
							</div>
						</div>
						
						<div class="form-group">
						 		<label class="col-sm-3 control-label">Nationality</label>
                                <div class="col-sm-9"> 
                                	{{ Form::select('nationality', $nationalityArray , $details->nationality , array('class' => 'form-control nationality', 'id' => 'nationality')) }}
                                </div>
                            </div>

							<div class="form-group">
						 		<label class="col-sm-3 control-label">Martial Status</label>
                                <div class="col-sm-9"> 
                                {{ Form::select('martial_status', $martialArray , $details->martial_status , array('class' => 'form-control martial_status', 'id' => 'martial_status')) }}
                                </div>
                            </div>
                            
                        <div class="form-group">
							<label class="col-lg-3 control-label">Photo</label>
								  <div class="col-sm-9"> 
                                <div class="fileinput fileinput-new input-group " data-provides="fileinput">
							    <div class="form-control" data-trigger="fileinput">
							        <i class="glyphicon glyphicon-file fileinput-exists"></i>
							    <span class="fileinput-filename"></span>
							    </div>
							    <span class="input-group-addon btn btn-default btn-file">
							        <span class="fileinput-new">Select file</span>
							        <span class="fileinput-exists">Change</span>
							        <input type="file" name="emp_pic"/>
							    </span>
							    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div> 
							</div>
						</div>
				</div>
			</div>
		</div>	
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Login Details</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					 
						<div class="form-group">
							<label class="col-lg-3 control-label">Email</label>
							<div class="col-lg-9">
								{{ Form::text('email', $details->email , array('placeholder'=>'Email','class' => 'form-control email' ,'readonly')) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Password</label>
							<div class="col-lg-9">
								{{ Form::password('newpassword',array('placeholder'=>'Password','class' => 'form-control newpassword ','id'=> 'newpassword')) }}
							</div>
						</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Company Details</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					 
						<div class="form-group">
							<label class="col-lg-3 control-label">Employee Id</label>
							<div class="col-lg-9">
								{{ Form::text('employee_id', $details->employee_id, array('placeholder'=>'Employee Id', 'class' => 'form-control employee_id' ,'required')) }}
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-lg-3 control-label">Department</label>
							<div class="col-lg-9">
								{{ Form::select('department', $ArrDepartment , $details->department, array('placeholder'=>'Depatment', 'class' => 'form-control department', 'id' => 'department')) }}
							</div>
						</div> -->
						<input type="hidden" name="oldpassword" value="{{  $details->password }}">
						<input type="hidden" name="editId" value="{{  $details->id }}">
						 	<div class="form-group">
						 		<label class="col-sm-3 control-label">Designation</label>
                                <div class="col-sm-9"> 
                                	{{ Form::select('designation', $ArrDesignation , $details->designation, array('placeholder'=>'Designatopn', 'class' => 'form-control designation', 'id' => 'designation')) }}
                                </div>
                       		</div>
						 	<div class="form-group" id="data_1">
						 		<label class="col-sm-3 control-label">Date Of joining</label>
                                <div class="col-sm-9"> 
                                	<div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="doj" id="" placeholder="Select Date of joingng" class="form-control" value="{{  date('d-m-Y',strtotime($details->date_of_joining)) }}">
                                </div>
                                </div>
                            </div>
						 	<div class="form-group">
						 		<label class="col-sm-3 control-label">Join Salary</label>
                                <div class="col-sm-9"> 
                                	{{ Form::select('join_salary', $testarray , $details->joining_salary, array('class' => 'form-control join_salary', 'id' => 'join_salary')) }}
                                </div>
                        </div> 
						 	<div class="form-group">
						 		<label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-9"> 
                                	{{ Form::select('status', $statusArray , $details->status, array('class' => 'form-control status', 'id' => 'status')) }}
                                </div>
                        </div>
                        
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Document</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					 
					  <div class="form-group">
							<label class="col-lg-3 control-label">Resume</label>
								<div class="fileinput fileinput-new input-group col-lg-9" data-provides="fileinput">
							    <div class="form-control" data-trigger="fileinput">
							        <i class="glyphicon glyphicon-file fileinput-exists"></i>
							    <span class="fileinput-filename"></span>
							    </div>
							    <span class="input-group-addon btn btn-default btn-file">
							        <span class="fileinput-new">Select file</span>
							        <span class="fileinput-exists">Change</span>
							        <input type="file" name="resume"/>
							    </span>
							    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div> 
						</div>
						  <div class="form-group">
							<label class="col-lg-3 control-label">Offer Letter</label>
								<div class="fileinput fileinput-new input-group col-lg-9" data-provides="fileinput">
							    <div class="form-control" data-trigger="fileinput">
							        <i class="glyphicon glyphicon-file fileinput-exists"></i>
							    <span class="fileinput-filename"></span>
							    </div>
							    <span class="input-group-addon btn btn-default btn-file">
							        <span class="fileinput-new">Select file</span>
							        <span class="fileinput-exists">Change</span>
							        <input type="file" name="offer_latter"/>
							    </span>
							    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div> 
						</div>
						  <div class="form-group">
							<label class="col-lg-3 control-label">Joining Letter</label>
								<div class="fileinput fileinput-new input-group col-lg-9" data-provides="fileinput">
							    <div class="form-control" data-trigger="fileinput">
							        <i class="glyphicon glyphicon-file fileinput-exists"></i>
							    <span class="fileinput-filename"></span>
							    </div>
							    <span class="input-group-addon btn btn-default btn-file">
							        <span class="fileinput-new">Select file</span>
							        <span class="fileinput-exists">Change</span>
							        <input type="file" name="join_letter"/>
							    </span>
							    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div> 
						</div>
						  <div class="form-group">
							<label class="col-lg-3 control-label">Contect & Agerement</label>
								<div class="fileinput fileinput-new input-group col-lg-9" data-provides="fileinput">
							    <div class="form-control" data-trigger="fileinput">
							        <i class="glyphicon glyphicon-file fileinput-exists"></i>
							    <span class="fileinput-filename"></span>
							    </div>
							    <span class="input-group-addon btn btn-default btn-file">
							        <span class="fileinput-new">Select file</span>
							        <span class="fileinput-exists">Change</span>
							        <input type="file" name="contect_agre"/>
							    </span>
							    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div> 
						</div>

						  <div class="form-group">
							<label class="col-lg-3 control-label">Other</label>
								<div class="fileinput fileinput-new input-group col-lg-9" data-provides="fileinput">
							    <div class="form-control" data-trigger="fileinput">
							        <i class="glyphicon glyphicon-file fileinput-exists"></i>
							    <span class="fileinput-filename"></span>
							    </div>
							    <span class="input-group-addon btn btn-default btn-file">
							        <span class="fileinput-new">Select file</span>
							        <span class="fileinput-exists">Change</span>
							        <input type="file" name="other"/>
							    </span>
							    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div> 
						</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Bank Details</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					 
						<div class="form-group">
							<label class="col-lg-3 control-label">Account Holder Name</label>
							<div class="col-lg-9">
								{{ Form::text('account_holder_name', $details->account_holder_name, array('class' => 'form-control account_holder_name' ,'required')) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Account Number</label>
							<div class="col-lg-9">
								{{ Form::text('account_number', $details->account_number, array('class' => 'form-control account_number' ,'required')) }}
							</div>
						</div>	
						<div class="form-group">
							<label class="col-lg-3 control-label">Bank name</label>
							<div class="col-lg-9">
								{{ Form::text('bank_name', $details->bank_name, array('class' => 'form-control bank_name' ,'required')) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Branch</label>
							<div class="col-lg-9">
								{{ Form::text('branch', $details->branch, array('class' => 'form-control branch' ,'required')) }}
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-3 col-lg-9">
								<button class="btn btn-sm btn-primary" type="submit">Save</button>
							</div>
						</div>
				</div>
			</div>
		</div>
	{{ Form::close() }}
	</div>
</div>

@endsection