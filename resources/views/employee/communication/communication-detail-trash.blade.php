@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-content mailbox-content">
                    <div class="file-manager">
                        <a class="btn btn-block btn-primary compose-mail" href="{{route('emp-compose')}}">Compose Mail</a>
                        <div class="space-25"></div>
                        <h5>Folders</h5>
                        <ul class="folder-list m-b-md" style="padding: 0">
                            <li><a href="{{ route('emp-communication') }}"> <i class="fa fa-inbox "></i> Inbox <span class="label label-warning pull-right">{{$unread}}</span> </a></li>
                            <!-- <li><a href="mailbox.html"> <i class="fa fa-envelope-o"></i> Send Mail</a></li>
                            <li><a href="mailbox.html"> <i class="fa fa-certificate"></i> Important</a></li>
                            <li><a href="mailbox.html"> <i class="fa fa-file-text-o"></i> Drafts <span class="label label-danger pull-right">2</span></a></li> -->
                            <li><a href="{{ route('emp-trash') }}"> <i class="fa fa-trash-o"></i> Trash</a></li>
                            <li><a href="{{ route('emp-send-mail') }}"> <i class="fa fa-reply"></i> Send</a></li>
                        </ul>
                       
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <div class="col-lg-9 animated fadeInRight">
            <div class="mail-box-header">
                
                <h2>
                    View Message
                </h2>
                <div class="mail-tools tooltip-demo m-t-md">
                    <h3>
                        <span class="font-normal">Subject: </span>{{ $empMailDetail->subject ? $empMailDetail->subject : 'N.A.' }}
                    </h3>
                    <h5>
                        <span class="pull-right font-normal">{{ date('Y-m-d H:i A', strtotime($empMailDetail->created_at)) }}</span>
                        <span class="font-normal">From: </span>{{ !empty($empMailDetail->employeeEmail) ? $empMailDetail->employeeEmail : $empMailDetail->companyEmail }}
                    </h5>
                </div>
            </div>
            <div class="mail-box">
                <div class="mail-body">
                    <p>{!! html_entity_decode($empMailDetail->message) !!}</p>
                </div>

                @if($empMailDetail->file)
                    <div class="mail-attachment">
                        <p>
                            <span><i class="fa fa-paperclip"></i> Attachments </span>
                            <!-- <a href="#">Download all</a>|<a href="#">View all images</a> -->
                        </p>
                        <div class="attachment">
                            <div class="file-box">
                                <div class="file">
                                    <a href="{{ url('/employee/empdownload-attachment/'.str_replace('/uploads/communication/', '', $empMailDetail->file)) }}">
                                        <span class="corner"></span>
                                        <div class="icon">
                                            <i class="fa fa-file"></i>
                                        </div>
                                        <div class="file-name">
                                            {{ str_replace('/uploads/communication/', '', $empMailDetail->file) }}
                                            <br/>
                                            <small>Added: {{ date('M d, Y', strtotime($empMailDetail->created_at)) }}</small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                @endif
<!--                <div class="mail-body text-right tooltip-demo">
                        <a class="btn btn-sm btn-white" href="{{url('')}}/employee/emp-compose?communication_id={{$empMailDetail->id}}"><i class="fa fa-reply"></i> Reply</a>
                        <a class="btn btn-sm btn-white" href="{{url('')}}/employee/emp-forward/{{$empMailDetail->id}}"><i class="fa fa-arrow-right"></i> Forward</a>
                        <button title="" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Print" class="btn btn-sm btn-white"><i class="fa fa-print"></i> Print</button>
                        <button title="" data-placement="top" data-toggle="tooltip" data-original-title="Trash" class="btn btn-sm btn-white"><i class="fa fa-trash-o"></i> Remove</button>
                </div>-->
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@endsection
