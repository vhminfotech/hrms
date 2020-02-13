@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Edit Annoucement</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    {{ Form::open( array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'addAnnouncement' )) }}
                    <div class="form-group">
                        <input type="hidden" name="edit_id" id="edit_id" value="{{$announcement_detail['id']}}">
                        <label class="col-lg-1 control-label">Title</label>
                        <div class="col-lg-5">
                            {{ Form::text('title', $announcement_detail['title'], array('placeholder'=>'Title', 'class' => 'form-control' ,'required')) }}
                        </div>
                        <div class="col-lg-1">
                            <a data-toggle="tooltip" class="tooltipLink" data-original-title="Title for Annoucement">
                                <span class="glyphicon glyphicon-info-sign"></span>
                            </a>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group" id="data_1">
                                <label class="col-sm-2 control-label">Date</label>
                                <div class="col-sm-9">
                                    <div class="input-group ">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="start_date" id="start_date"  class="form-control start_date" placeholder="Start date"  value="{{ date('d-m-Y',strtotime($announcement_detail['date'])) }}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-1 control-label">Status</label>
                        <div class="col-lg-5">
                            {{ Form::select('status', $status ,$announcement_detail['status'], array('class' => 'form-control status', 'id' => 'status')) }}
                        </div>
                        <div class="col-lg-1">
                            <a data-toggle="tooltip" class="tooltipLink" data-original-title="Select Status">
                                <span class="glyphicon glyphicon-info-sign"></span>
                            </a>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group" id="data_2">
                                <label class="col-sm-2 control-label">Time</label>
                                <div class="col-sm-9">
                                    
                                    <div class="input-group" id="datetimepicker">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input id="time" class="form-control time" data-time-format="H:i:s" type="text" value="{{ $announcement_detail['time'] }}" name="time" />
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group" id="data_2">
                                <label class="col-sm-2 control-label">Expiry Date</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="expiry_date" id="expiry_date"  class="form-control expiry_date" placeholder="Expiry Date" value="{{ $announcement_detail['expiry_date'] ? $announcement_detail['expiry_date'] : 'N.A.' }}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">

                        <label class="col-lg-1 control-label">Content</label>
                        <div class="col-lg-5">
                            {{ Form::textarea('content', $announcement_detail['content'], array('placeholder'=>'Content', 'rows' => 4,'class' => 'form-control' ,'required')) }}
                        </div>
                        <div class="col-lg-1">
                            <a data-toggle="tooltip" class="tooltipLink" data-original-title="Content for Annoucement">
                                <span class="glyphicon glyphicon-info-sign"></span>
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <div class="col-lg-8 col-lg-offset-2">
                                <button class="btn btn-sm btn-primary submitbtn" type="submit">Edit Annoucement</button>
                                <button type="button" class="btn btn-sm btn-info">Reset</button>
                                <button type="button" class="btn btn-sm btn-danger">Cancel</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>  

    </div>
</div>

@endsection