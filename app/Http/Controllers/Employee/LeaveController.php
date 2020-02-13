<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Leave;
use App\Model\Employee;
use App\Model\Company;
use App\Model\Notification;
use App\Model\NotificationMaster;
use App\Model\TypeOfRequest;
use App\Model\AttendanceHistory;
use App\Model\LeaveCategory;
use Config;
class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        $session = $request->session()->all();
        $logindata = $session['logindata'][0];

        $objTypeOfRequest = new TypeOfRequest();
        $data['type_of_request']= $objTypeOfRequest->getTypeOfRequestV2($logindata['id']);

        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('employee/leave.js');
        $data['funinit'] = array('Leave.init()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Leave',
            'breadcrumb' => array(
                'Home' => route("employee-dashboard"),
                'Leave' => 'Leave'));
        
        return view('employee.leave.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leaveadd(Request $request)
    {
        $session = $request->session()->all();
        $logindata = $session['logindata'][0];
        $objEmployee=new Employee();
        $empdetails=$objEmployee->getEmploydetails($logindata['id']);
        $data['company_id']=$empdetails[0]->company_id;
        $data['emp_id']=$empdetails[0]->emp_id;
        if ($request->isMethod('post')) {
            $objLeave = new Leave();
            $ret = $objLeave->addnewleave($request);
            if ($ret) {

                $objCompany = new Company();
                $u_id=$objCompany->getUseridById($empdetails[0]->company_id);
             
                $notificationMasterId=13;
                $objNotificationMaster = new NotificationMaster();
                $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatus($u_id,$notificationMasterId);
                
                if($NotificationUserStatus==1)
                {
                    $leaveRequestName=$empdetails[0]->name." new leave request.";
                    $objNotification = new Notification();
                    $route_url="notification-list";
                    $ret = $objNotification->addNotification($u_id,$leaveRequestName,$route_url,$notificationMasterId);
                }

                $return['status'] = 'success';
                $return['message'] = 'Leave added successfully.';
                $return['redirect'] = route('employee-leave');
            } else {
                $return['status'] = 'error';
                $return['message'] = 'something will be wrong.';
            }
            echo json_encode($return);
            exit;
        }

        $session = $request->session()->all();
        // $data['type_of_request']=Config::get('constants.type_of_request');

        $objTypeOfRequest = new TypeOfRequest();
        $data['type_of_request']= $objTypeOfRequest->getTypeOfRequestV2($data['emp_id']);
        
        $objLeaveCategory = new LeaveCategory;
        $data['type_of_request_new'] = $objLeaveCategory->getTypeOfRequest($logindata['id']);

        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('employee/leave.js', 'ajaxfileupload.js', 'jquery.form.min.js');
        $data['funinit'] = array('Leave.init()');
        $data['css'] = array('plugins/jasny/jasny-bootstrap.min.css');
        $data['header'] = array(
            'title' => 'Add Leave',
            'breadcrumb' => array(
                'Home' => route("employee-dashboard"),
                'Leave' => route("employee-leave"),
                'Add Leave'=>'Add Leave'));
        return view('employee.leave.add', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leaveedit(Request $request,$id)
    {
        $session = $request->session()->all();
        $logindata = $session['logindata'][0];
        $objEmployee=new Employee();
        $empdetails=$objEmployee->getEmploydetails($logindata['id']);
        $data['company_id']=$empdetails[0]->company_id;
        $data['emp_id']=$empdetails[0]->emp_id;
        $data['dep_id']=$empdetails[0]->dep_id;
        if ($request->isMethod('post')) {

            $objLeave = new Leave();
            $ret = $objLeave->editleave($request);
            if ($ret) {
                $objCompany = new Company();
                $u_id=$objCompany->getUseridById($empdetails[0]->company_id);
               
                
                $notificationMasterId=14;
                $objNotificationMaster = new NotificationMaster();
                $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatus($u_id,$notificationMasterId);
                
                if($NotificationUserStatus==1)
                {
                    //notification add
                    $leaveRequestName=$empdetails[0]->name." leave change request.";
                    $objNotification = new Notification();
                    $route_url="notification-list";
                    $ret = $objNotification->addNotification($u_id,$leaveRequestName,$route_url,$notificationMasterId);
                }

                $return['status'] = 'success';
                $return['message'] = 'Leave updated successfully.';
                $return['redirect'] = route('employee-leave');
            } else {
                $return['status'] = 'error';
                $return['message'] = 'something will be wrong.';
            }
            echo json_encode($return);
            exit;
        }
        $data['leaveEdit'] = Leave::find($id);
       // $data['type_of_request']=Config::get('constants.type_of_request');
 $objLeaveCategory = new LeaveCategory;
        $data['type_of_request_new'] = $objLeaveCategory->getTypeOfRequest($logindata['id']);

        $objTypeOfRequest = new TypeOfRequest();
        $data['type_of_request']= $objTypeOfRequest->getTypeOfRequestV2($data['emp_id']);

        $session = $request->session()->all();
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('employee/leave.js', 'ajaxfileupload.js', 'jquery.form.min.js');
        $data['funinit'] = array('Leave.init()');
        $data['css'] = array('plugins/jasny/jasny-bootstrap.min.css');
        $data['header'] = array(
            'title' => 'Edit Leave',
            'breadcrumb' => array(
                'Home' => route("employee-dashboard"),
                'Leave' => route("employee-leave"),
                'Edit Leave'=>'Edit Leave'));
        return view('employee.leave.add', $data);
    }
    
     public function deleteLeave($postData) {
        if ($postData) {
            $deleteAttendanceHistory = AttendanceHistory::where('leave_id', $postData['id'])->delete();
            $result = Leave::where('id', $postData['id'])->delete();
            if ($result) {
                $return['status'] = 'success';
                $return['message'] = 'Leave delete successfully.';
                $return['jscode'] = "setTimeout(function(){
                        $('#deleteModel').modal('hide');
                        $('#dataTables-leave').DataTable().ajax.reload();
                    },1000)";
            } else {
                $return['status'] = 'error';
                $return['message'] = 'something will be wrong.';
            }
            echo json_encode($return);
            exit;
        }
    }

    public function ajaxAction(Request $request) {
        $action = $request->input('action');
        switch ($action) {
            case 'getdatatable':
                $userID = $this->loginUser;
                $objEmploye=new Employee();
                $employeid=$objEmploye->getUserid($userID->id);
                
                $objLeave = new Leave();
                $demoList = $objLeave->getLeaveDatatable($employeid);
                echo json_encode($demoList);
                break;
            case 'deleteLeave':
                $result = $this->deleteLeave($request->input('data'));
                break;
        }
    }

   
}
