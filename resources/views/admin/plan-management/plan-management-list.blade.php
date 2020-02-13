@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            {{ csrf_field() }}
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>All Plan list</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('add-plan') }}" class="btn btn-primary dim" ><i class="fa fa-plus"> Add</i></a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-plan" id="plan-management-datatable">
                            <thead>
                                <tr>
                                    <th>Plan Code</th>
                                    <th>Title</th>
                                    <th>Charge</th>
                                    <th>Expiration</th>
                                    <th>Plan Duration</th>
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
@endsection
