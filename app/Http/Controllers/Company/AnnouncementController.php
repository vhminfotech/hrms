<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Announcement;
use App\Model\Company;
use App\Model\NonWorkingDate;
use App\Model\Employee;
use App\Model\Notification;
use App\Model\NotificationMaster;
use App\Model\Users;
use App\Model\SendSMS;
use App\Model\UserNotificationType;
use Auth;
use Config;

class AnnouncementController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('company');
    }

    public function index() {
        
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/announcement.js', 'jquery.form.min.js', 'jquery.timepicker.js');
        $data['funinit'] = array('Announcement.init()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Announcement List',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Announcemnet' => 'Announcement'));

        return view('company.announcement.announcement-list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anounment_add(Request $request) {

        if ($request->isMethod('post')) {
//            print_r($request->input('assign_to'));exit;
            $objAnnoucement = new Announcement();
            $userData = Auth::guard('company')->user();
            
            $getAuthCompanyId = Company::where('email', $userData->email)->first();
            $logedcompanyId = $getAuthCompanyId->id;
            
            $objNonWorkingDate = new NonWorkingDate();
            $resultNonWorkingDate = $objNonWorkingDate->getCompanyNonWorkingDateArrayList($logedcompanyId);
           
            if(in_array(date('Y-m-d',strtotime($request->input('expiry_date'))), $resultNonWorkingDate)) {
                $return['status'] = 'error';
                $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");';
                $return['message'] = $request->input('expiry_date'). ' is Non Working Date';
            }else{
                $result = $objAnnoucement->addAnnouncementData($request, $logedcompanyId);
                if ($result) {
                    $notificationMasterId=10;
                    $objNotificationMaster = new NotificationMaster();
//                    $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatus($userData->id,$notificationMasterId);
                    $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatusNew($userData->id,$notificationMasterId);
                    
                    if($NotificationUserStatus->status==1)
                    {   
                        $employeeList = Employee::select("id")
                                       ->where('company_id',$logedcompanyId)
                                       ->get();
//                        print_r($employeeList);
//                        die();
                        foreach($employeeList as $value){
                            $employeeId=$value->id;
                            $objUserNotificationType = new UserNotificationType();
                            $result = $objUserNotificationType->checkMessage($NotificationUserStatus->id);

                            if($result[0]['status'] == 1){
    //                            SMS  Notification
                                $notificationMasterId=1;
                                $msg= "You have a new Announcement.";
                                $objSendSms = new SendSMS();
                                $sendSMS = $objSendSms->sendSmsNotificaation($notificationMasterId,$employeeId,$msg);
                            }

                            if($result[1]['status'] == 1){
    //                            EMAIL Notification
                                $notificationMasterId=1;
                                $msg= "You have a new Announcement.";
                                $objSendEmail = new Users();
                                $sendEmail = $objSendEmail->sendEmailNotification($notificationMasterId,$employeeId,$msg);


                            }

                            if($result[2]['status'] == 1){
    //                            chat Notification
                            }

                            if($result[3]['status'] == 1){
                                $objNotification = new Notification();
                                $ticketName=$request->input('subject')." is a new Announcement.";
                                $objEmployee = new Employee();
//                                $u_id=$objEmployee->getUseridById($request->input('assign_to'));
                                $route_url="employee-notification-list";
                                $ret = $objNotification->addNotification($employeeId,$ticketName,$route_url,$notificationMasterId);
                            }
                        }
                        //notification add
                        
                    }
                    $return['status'] = 'success';
                    $return['message'] = 'Annoucement Add Successfully.';
                    $return['jscode'] = '$(".submitbtn:visible").attr("disabled","disabled");';
                    $return['redirect'] = route('announcement');
                }else{
                    $return['status'] = 'error';
                    $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");';
                    $return['message'] = 'Something went wrong!';
                }
            }
            echo json_encode($return);
            exit;
        }

        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/announcement.js', 'jquery.form.min.js', 'jquery.timepicker.js');
        $data['funinit'] = array('Announcement.add()');
        $data['css'] = array('plugins/jasny/jasny-bootstrap.min.css', 'jquery.timepicker.css');
        $data['status'] = Config::get('constants.status');
        $data['header'] = array(
            'title' => 'Announcement List',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Announcement list'=> route("announcement"),
                'Announcemnet' => 'Announcement-add'));

        return view('company.announcement.announcement-add', $data);
    }

    public function ajaxAction(Request $request) {
        $action = $request->input('action');
        switch ($action) {
            case 'getdatatable':
                $userID = $this->loginUser->id;
                $companyId = Company::select('id')->where('user_id', $userID)->first();
                $announmntObj = new Announcement;
                $AnnounmntList = $announmntObj->getAnnouncementList($request, $companyId->id);
                echo json_encode($AnnounmntList);
                break;
            case'deleteAnnouncement':
                $result = $this->deleteAnnouncement($request->input('data'));
                break;
        }
    }

    public function deleteAnnouncement($postData) {
        if ($postData) {
            $findAnnounmnt = Announcement::where('id', $postData['id'])->first();
            $result = $findAnnounmnt->delete();

            if ($result) {
                $return['status'] = 'success';
                $return['message'] = 'Record deleted successfully.';
                $return['jscode'] = "setTimeout(function(){
                        $('#deleteModel').modal('hide');
                        $('#AnnouncementDatatables').DataTable().ajax.reload();
                    },1000)";
            } else {
                $return['status'] = 'error';
                $return['message'] = 'Something will be wrong.';
            }
            echo json_encode($return);
            exit;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anounment_edit(Request $request,$id) {
        if ($request->isMethod('post')) {
            $objAnnouncement = new Announcement();
            $ret = $objAnnouncement->editAnnoucement($request);

            if ($ret) {
                $return['status'] = 'success';
                $return['message'] = 'Record Edited successfully.';
                $return['redirect'] = route('announcement');
            } else {
                $return['status'] = 'error';
                $return['message'] = 'Please add any one designation!';
            }

            echo json_encode($return);
            exit;
        }
        $data['announcement_detail'] = Announcement::where('id', $id)->first();
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/announcement.js', 'jquery.form.min.js', 'jquery.timepicker.js');
        $data['funinit'] = array('Announcement.add()');
        $data['css'] = array('plugins/jasny/jasny-bootstrap.min.css', 'jquery.timepicker.css');
        $data['status'] = Config::get('constants.status');
        $data['header'] = array(
            'title' => 'Announcement List',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Announcemnet' => 'Announcement-add'));

        return view('company.announcement.announcement-edit', $data);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
