@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Add Plan Management</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    {{ Form::open( array('method' => 'post', 'class' => 'form-horizontal','files' => true, 'id' => 'addPlan' )) }}

                    <div class="col-lg-12">
                        <div class="col-lg-6">
                            <div class="form-group"><label class="col-lg-2 control-label">Code</label>
                                <div class="col-lg-9">
                                    {{ Form::text('code', null, array('class' => 'form-control code' ,'required')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group"><label class="col-lg-2 control-label">Title</label>
                                <div class="col-lg-9">
                                    {{ Form::text('title', null, array('class' => 'form-control title' ,'required')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="col-lg-6">
                            <div class="form-group"><label class="col-lg-2 control-label">Charge</label>
                                <div class="col-lg-9">
                                    {{ Form::text('charge', null, array('class' => 'form-control charge' ,'required')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group"><label class="col-lg-2 control-label">Duration</label>
                                <div class="col-lg-9">
                                    {{ Form::select('duration', $duration, null, array('class' => 'form-control m-b', 'id' => 'duration','required')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="col-lg-6">
                            <div class="form-group" id="data_1">
                                <label class="col-sm-2 control-label">Expiration</label>
                                <div class="col-sm-9"> 
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="expiry_at" value="{{ isset($leaveEdit) && !empty($leaveEdit['expiry_at']) ? date('d-m-Y',strtotime( $leaveEdit['start_date'])) : '' }}" id="expiry_at" placeholder="expiry date" class="form-control expiry_at dateField" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12">
                        <div class="col-lg-8">
                            <h3><b>Plane Feature</b></h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        @if(!empty($plan_features))
                            @foreach($plan_features as $pf)
                                <div class="col-lg-3">
                                    <label class="checkbox-inline"> 
                                        <input type="hidden" name="plan_feature_name[]" value="{{$pf->name}}">
                                        <input type="checkbox"  id="plan_feature" name="{{$pf->name}}" value="true">
                                        {{$pf->display_name}}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="col-lg-11"></div>
                        <div class="col-lg-1">
                            <button class="btn btn-sm btn-primary" type="submit">Save</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>	
    </div>
</div>

@endsection