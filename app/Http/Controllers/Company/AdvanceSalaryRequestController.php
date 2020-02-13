<?php

namespace App\Http\Controllers\Company;

use App\User;
use App\Model\Users;
use App\Model\Employee;
use App\Model\Company;
use App\Http\Controllers\Controller;
use Auth;
use Route;
use APP;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Model\Payroll;
use App\Model\Advancesalary;
use App\Model\Notification;
use App\Model\NotificationMaster;
use Response;
use Config;
use Excel;
use Session;
use App\Model\UserNotificationType;
use App\Model\SendSMS;
class AdvanceSalaryRequestController extends Controller {

    public function __construct() {
         $this->middleware('company');
    }
    
    public function requestList(Request $request){
        $requestType = $request->input('requestType');
        $id = Auth()->guard('company')->user()['id'];
        $companyId = Company::select('id')->where('user_id', $id)->first();
        $objAdvanceSalary=new Advancesalary();
        
        $data['allRequestCount'] = $objAdvanceSalary->allRequestCount($companyId['id']);
        $data['allNewRequestCount'] = $objAdvanceSalary->allNewRequestCount($companyId['id']);
        $data['allApprovedRequestCount'] = $objAdvanceSalary->allApprovedRequestCount($companyId['id']);
        $data['allRejectedRequestCount'] = $objAdvanceSalary->allRejectedRequestCount($companyId['id']);
     
        $session = $request->session()->all();
        $items = Session::get('notificationdata');
        $userID = $session['logindata'][0]['id'];
        
        $objNotification = new Notification();
        $items=$objNotification->SessionNotificationCount($userID);        
        Session::put('notificationdata', $items);

        $data['detail'] = $this->loginUser;
        $data['header'] = array(
            'title' => 'Advance Salary Request List',
            'breadcrumb' => array(
            'Home' => route("company-dashboard"),
            'Advance Salary Request' => 'Advance Salary Request'));
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/advancesalaryrequest.js');
        
        if($requestType == 'newRequest'){
            $data['requestType'] = "New Request";
            $data['funinit'] = array('Advancesalaryrequest.init()','Advancesalaryrequest.new()');
        }
        
        if($requestType == 'aprrovedRequest'){
            $data['requestType'] = "Appoved Request";
            $data['funinit'] = array('Advancesalaryrequest.init()','Advancesalaryrequest.approved()');
        }
        
        if($requestType == 'rejectedRequest'){
            $data['requestType'] = "Rejected Request";
            $data['funinit'] = array('Advancesalaryrequest.init()','Advancesalaryrequest.rejected()');
        }
        
        if($requestType == '' || $requestType == NULL){
            $data['requestType'] = "All Request";
            $data['funinit'] = array('Advancesalaryrequest.init()','Advancesalaryrequest.all()');
        }
        
        $data['css'] = array('');
        return view('company.advancesalaryrquest.request-list', $data);
    }
    
    public function newRequest(Request $request){
        $session = $request->session()->all();
        $logindata = $session['logindata'][0];

        $companyId = Company::select('id')->where('user_id', $logindata['id'])->first();
        $data['getAllEmpOfCompany'] = Employee::where('company_id', $companyId->id)->get();

        if ($request->isMethod('post')) {
            $objNewSalaryRequest= new Advancesalary();
            $result=$objNewSalaryRequest->addSalaryRequest($request);
            if ($result) {
                $return['status'] = 'success';
                $return['message'] = 'New Advance Salary  Request created successfully.';
                $return['redirect'] = route('campany-advance-salary-request');
            } else {
                $return['status'] = 'error';
                $return['message'] = 'Something goes to wrong';
            }
            echo json_encode($return);
            exit;
        }
        $data['header'] = array(
            'title' => 'New Advance Salary Request List',
            'breadcrumb' => array(
                'Home' => route("employee-dashboard"),
                'Advance Salary Request list' => route('campany-advance-salary-request'),
                'New Advance Salary Request' => 'New Advance Salary Request'));
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/advancesalaryrequest.js','ajaxfileupload.js', 'jquery.form.min.js');
        $data['funinit'] = array('Advancesalaryrequest.init()');
        $data['css'] = array('');

        return view('company.advancesalaryrquest.new-request-list', $data);
    }

    public function ajaxAction(Request $request){
        $action = $request->input('action');
        
        switch ($action) {
            
            case 'getdatatable':
                $id = Auth()->guard('company')->user()['id'];
                $companyId = Company::select('id')->where('user_id', $id)->first();
                $objAdvanceSalary=new Advancesalary();
                $datalist=$objAdvanceSalary->getCompanyAdvanceSalaryList($companyId['id'],"all");
                echo json_encode($datalist);
                break;
            
            case 'getdatatable-newRequestList':
                $id = Auth()->guard('company')->user()['id'];
                $companyId = Company::select('id')->where('user_id', $id)->first();
                $objAdvanceSalary=new Advancesalary();
                $datalist=$objAdvanceSalary->getCompanyAdvanceSalaryList($companyId['id'],"newRequest");
                echo json_encode($datalist);
                break;
            
            case 'getdatatable-approvedRequestList':
                $id = Auth()->guard('company')->user()['id'];
                $companyId = Company::select('id')->where('user_id', $id)->first();
                $objAdvanceSalary=new Advancesalary();
                $datalist=$objAdvanceSalary->getCompanyAdvanceSalaryList($companyId['id'],'appovedRequest');
                echo json_encode($datalist);
                break;
            
            case 'getdatatable-rejectedRequestList':
                $id = Auth()->guard('company')->user()['id'];
                $companyId = Company::select('id')->where('user_id', $id)->first();
                $objAdvanceSalary=new Advancesalary();
                $datalist=$objAdvanceSalary->getCompanyAdvanceSalaryList($companyId['id'],"rejectedRequest");
                echo json_encode($datalist);
                break;
            
            case 'approveRequest':
                    
                    $id=$request->input('data')['id'];
                    $objAdvancesalary = new Advancesalary();
                    $employeeId=$objAdvancesalary->getUseridByAdvanceSalaryIdNew($id);
                    
                    $objAdvancesalary=new Advancesalary();
                    $approveRequest=$objAdvancesalary->approveRequest($id);
                    if ($approveRequest) {

                        $session = $request->session()->all();
                        $logindata = $session['logindata'][0];

                        $userId = $logindata['id'];

                        $notificationMasterId=6;
                        $objNotificationMaster = new NotificationMaster();
//                        $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatus($userId,$notificationMasterId);
                        $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatusNew($userId,$notificationMasterId);
                    
                        if($NotificationUserStatus->status==1)
                        {   
                            $objUserNotificationType = new UserNotificationType();
                            $result = $objUserNotificationType->checkMessage($NotificationUserStatus->id);

                            if($result[0]['status'] == 1){
    //                            SMS  Notification
                               $notificationMasterId=6;
                                $msg= "Company advance selery request approved.";
                                $objSendSms = new SendSMS();
                                $sendSMS = $objSendSms->sendSmsNotificaation($notificationMasterId,$employeeId,$msg);
                            }

                            if($result[1]['status'] == 1){
    //                            EMAIL Notification
                                $notificationMasterId=6;
                                $msg= "Company advance selery request approved.";
                                $objSendEmail = new Users();
                                $sendEmail = $objSendEmail->sendEmailNotification($notificationMasterId,$employeeId,$msg);


                            }

                            if($result[2]['status'] == 1){
    //                            chat Notification
                            }

                            if($result[3]['status'] == 1){
    //                            Systeam Notification 
                              $seleryRequestName="Company advance selery request approved.";
                            $u_id=$objAdvancesalary->getUseridByAdvanceSalaryId($id);
                            $objNotification = new Notification();
                            $route_url="advance-salary-request";
                            $ret = $objNotification->addNotification($u_id,$seleryRequestName,$route_url,$notificationMasterId);
                            }
                            //notification add                        
                            
                        }

                        $return['status'] = 'success';
                        $return['message'] = 'Advance salary request approved';
                        $return['jscode'] = '$(".yesapprove:visible").attr("disabled","disabled");';
                        $return['redirect'] = route('campany-advance-salary-request');
                    } else {
                        $return['status'] = 'error';
                        $return['jscode'] ='$(".yesapprove").removeAttr("disabled");';
                        $return['message'] = 'Something goes to wrong';
                    }
                    echo json_encode($return);
                    exit;
                    break;
                    
            case 'disapproveRequest':
                    $id=$request->input('data')['id'];
                    $objAdvancesalary = new Advancesalary();
                    $employeeId=$objAdvancesalary->getUseridByAdvanceSalaryIdNew($id);
                    
                    $objAdvancesalary=new Advancesalary();
                    $disapproveRequest=$objAdvancesalary->disapproveRequest($id);
                    if ($disapproveRequest) {
                        $session = $request->session()->all();
                        $logindata = $session['logindata'][0];

                        $userId = $logindata['id'];

                        $notificationMasterId=6;
                        $objNotificationMaster = new NotificationMaster();
//                        $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatus($userId,$notificationMasterId);
                        $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatusNew($userId,$notificationMasterId);
                    
                        if($NotificationUserStatus->status==1)
                        {   
                            $objUserNotificationType = new UserNotificationType();
                            $result = $objUserNotificationType->checkMessage($NotificationUserStatus->id);

                            if($result[0]['status'] == 1){
    //                            SMS  Notification
                                $notificationMasterId=6;
                                $msg= "Company Advance selery request rejected.";
                                $objSendSms = new SendSMS();
                                $sendSMS = $objSendSms->sendSmsNotificaation($notificationMasterId,$employeeId,$msg);
                            }

                            if($result[1]['status'] == 1){
    //                            EMAIL Notification
                                $notificationMasterId=6;
                                $msg= "Company Advance selery request rejected.";
                                $objSendEmail = new Users();
                                $sendEmail = $objSendEmail->sendEmailNotification($notificationMasterId,$employeeId,$msg);


                            }

                            if($result[2]['status'] == 1){
    //                            chat Notification
                            }

                            if($result[3]['status'] == 1){
                            //notification add                        
                            $seleryRequestName="Company Advance selery request rejected.";
                            $u_id=$objAdvancesalary->getUseridByAdvanceSalaryId($id);
                            $objNotification = new Notification();
                            $route_url="advance-salary-request";
                            $ret = $objNotification->addNotification($u_id,$seleryRequestName,$route_url,$notificationMasterId);
                            }
                        }

                        $return['status'] = 'success';
                        $return['message'] = 'Advance salary request rejected';
                        $return['redirect'] = route('campany-advance-salary-request');
                    } else {
                        $return['status'] = 'error';
                        $return['message'] = 'Something goes to wrong';
                    }
                    echo json_encode($return);
                    exit;
                    break;

            case 'changeSalaryStatus':
                    $objAdvancesalary = new Advancesalary();
                    $disapproveRequest = $objAdvancesalary->changeAdvanceSalaryStatus($request->input('data'));
                    if ($disapproveRequest) {

                        //notification add  
                        $postData=$request->input('data');
                        $status = $postData['status']; 
                        $employeeArr = $postData['arrEmp'];
                        foreach ($employeeArr as $key => $value) {                      
                            $seleryRequestName="Company selery request ".$status."ed.";
                            $u_id=$objAdvancesalary->getUseridByAdvanceSalaryId($value);
                            $objNotification = new Notification();
                            $route_url="advance-salary-request";
                            $ret = $objNotification->addNotification($u_id,$seleryRequestName, $route_url);
                        }
                        
                        $return['status'] = 'success';
                        $return['message'] = 'Status Changed successfully.';
                        $return['redirect'] = route('campany-advance-salary-request');
                    } else {
                        $return['status'] = 'error';
                        $return['message'] = 'Something goes to wrong';
                    }
                    echo json_encode($return);
                    exit;
                    break;
        }
    }

    public function approvedRequestList(Request $request)
    {
        $data['monthis'] = Config::get('constants.months');
        $id = Auth()->guard('company')->user()['id'];
        $companyId = Company::select('id')->where('user_id', $id)->first();

        $data['get_year'] = $request->get('year');
        $data['get_month'] = $request->get('month');
        $data['month'] = $request->get('month');

        $objAdvanceSalary = new Advancesalary();
        $data['datalist'] = $objAdvanceSalary->getCompanyApprovedAdvanceSalaryListV2($companyId['id'],$data['get_year'],$data['month']);

        $data['detail'] = $this->loginUser;
        $data['header'] = array(
            'title' => 'Approved Salary Request List',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Approved Advance Salary Request' => 'Approved Advance Salary Request'));
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/advancesalaryrequest.js');
        $data['funinit'] = array('Advancesalaryrequest.initApprovedReqList()');
        $data['css'] = array('');

        return view('company.advancesalaryrquest.approved-request-list', $data);
    }

    public function approvedListAjaxaction(Request $request)
    {
        $action = $request->input('action');
        
        switch ($action) {
            case 'getdatatable':
                $id = Auth()->guard('company')->user()['id'];
                $companyId = Company::select('id')->where('user_id', $id)->first();
                $objAdvanceSalary = new Advancesalary();
                $datalist=$objAdvanceSalary->getCompanyApprovedAdvanceSalaryList($companyId['id']);
                echo json_encode($datalist);
                break;
            }
    }
    
    public function createApprovedPdf(Request $request){
        if($request->method('post')){
            $objAdvanceSalary=new Advancesalary();
            $data['advanceSalaryApprovedRequest']=$objAdvanceSalary->getDetails($request);
            $pdf = PDF::loadView('company.advancesalaryrquest.advance-salary-rquest-pdf', compact('data'));
            $pdfName='advance-salary'.time().'.pdf';
            $pdf->save(public_path('uploads/comapany/'.$pdfName));
            return $pdfName;
        }
    } 

    public function createApprovedExcel(Request $request){
        if($request->method('post')){
            $objAdvanceSalary=new Advancesalary();
            $advanceSalaryApprovedRequest = $objAdvanceSalary->getDetailsV2($request);
            // print_r($advanceSalaryApprovedRequest);exit;
           Excel::create('Approved Advance Salary Request-'.date('dmY'), function($excel) use ($advanceSalaryApprovedRequest){
                $headers = array('Name', 'Comment', 'Date of Submit', 'Department Name', 'Company Name','Phone');
                        $excel->sheet("Student_Offers_List", function($sheet) use ($headers, $advanceSalaryApprovedRequest) {
                        for ($i = 0; $i < count($advanceSalaryApprovedRequest); $i++) {
                            $sheet->prependRow($headers);
                            $sheet->fromArray(array(
                                array(
                                    $advanceSalaryApprovedRequest[$i]['name'],
                                    $advanceSalaryApprovedRequest[$i]['comments'],
                                    $advanceSalaryApprovedRequest[$i]['date_of_submit'],
                                    $advanceSalaryApprovedRequest[$i]['department_name'],
                                    $advanceSalaryApprovedRequest[$i]['company_name'],
                                    $advanceSalaryApprovedRequest[$i]['phone'],
                                )), null, 'A2', false, false);
                            }
                        });
                // $excel->sheet('Sheetname', function($sheet) {
                //     $sheet->fromArray(array(
                //         array('data1', 'data2'),
                //         array('data3', 'data4')
                //     ));
                // });
            })->export('xls');
        }
    }
    
    public function downloadApprovedPdf(Request $request){
        if($request->method('get')){
            $pdfName=$request->input('pdfname');
            $file=public_path('uploads/comapany/'.$request->input('pdfname'));
            return Response::download($file, $pdfName);
        }
    }
}
