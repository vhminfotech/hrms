<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Training;
use App\Model\Company;
use App\Model\Department;
use App\Model\TraningEmployeeDepartment;
use App\Model\Notification;
use App\Model\NotificationMaster;
use App\User;
use App\Model\UserNotificationType;
use App\Model\SendSMS;
use App\Model\Users;
use App\Model\Employee;
use Auth;
use Route;

class TrainingController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('company');
    }

    public function index(Request $request) {
        $session = $request->session()->all();
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/training.js');
        $data['funinit'] = array('Training.init()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Company',
            'breadcrumb' => array(
                'Home' => route("admin-dashboard"),
                'Training' => 'Training'));
        return view('company.training.training-list', $data);
    }

    public function addTraining(Request $request) {
        $session = $request->session()->all();
        $userId = $this->loginUser->id;
        $companyId = Company::select('id')->where('user_id', $userId)->first();

        if ($request->isMethod('post')) {
//           print_r($request->input());
//           die();
            $objCompany = new Training();
            $ret = $objCompany->addTraining($request, $companyId->id);

            if ($ret) {

                $empId = $request->input('employeeid');
                $notificationMasterId = 7;
                $objNotificationMaster = new NotificationMaster();
//                $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatus($userId,$notificationMasterId);
                $NotificationUserStatus = $objNotificationMaster->checkNotificationUserStatusNew($userId, $notificationMasterId);

                if ($NotificationUserStatus->status == 1) {
                    foreach ($empId as $key => $value) {

                        $objUserNotificationType = new UserNotificationType();
                        $result = $objUserNotificationType->checkMessage($NotificationUserStatus->id);

                        if ($result[0]['status'] == 1) {
//                            SMS  Notification
                            $notificationMasterId = 7;
                            $msg = "Company has added a new training.";
                            $objSendSms = new SendSMS();
                            $sendSMS = $objSendSms->sendSmsNotificaation($notificationMasterId, $value, $msg);
                        }

                        if ($result[1]['status'] == 1) {
//                            EMAIL Notification
                            $notificationMasterId = 7;
                            $msg = "Company has added a new training.";
                            $objSendEmail = new Users();
                            $sendEmail = $objSendEmail->sendEmailNotification($notificationMasterId, $value, $msg);
                        }

                        if ($result[2]['status'] == 1) {
//                            chat Notification
                        }

                        if ($result[3]['status'] == 1) {
                            //notification add
                            $objNotification = new Notification();
                            $trainingName = "Company has added a new training.";
                            $objEmployee = new Employee();
                            $u_id = $objEmployee->getUseridById($empId[$key]);
                            $route_url = "employee-training";
                            $ret = $objNotification->addNotification($u_id, $trainingName, $route_url, $notificationMasterId);
                        }
                    }
                }

                $return['status'] = 'success';
                $return['message'] = 'Training created successfully.';
                $return['jscode'] = '$(".submitbtn:visible").attr("disabled","disabled");';
                $return['redirect'] = route('training');
            } else {
                $return['status'] = 'error';
                $return['message'] = 'Somethin went wrong while creating new training!';
                $return['jscode'] = '$(".submitbtn").removeAttr("disabled");';
            }
            echo json_encode($return);
            exit;
        }

        $objdepartment = new Department();
        $objDesignation = new Employee();
        $data['department'] = $objdepartment->getDepartment();
        $data['employee'] = $objDesignation->getEmployee($companyId->id);
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/training.js', 'jquery.form.min.js');
        $data['funinit'] = array('Training.add()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Training Add',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Training List' => route("training"),
                'Add Training' => 'Training'));

        return view('company.training.training-add', $data);
    }

    public function ajaxAction(Request $request) {
        $action = $request->input('action');
        switch ($action) {
            case 'getdatatable':
                $objTraining = new Training();
                $userid = $this->loginUser->id;
                $companyId = Company::select('id')->where('user_id', $userid)->first();
                $demoList = $objTraining->getTrainingDatatable($request, $companyId->id);
                echo json_encode($demoList);
                break;
            case 'deleteTraining':
                $result = $this->deleteTraining($request->input('data'));
                break;
            case 'getDepartmentTrainingList':
                $result = $this->getDepartmentTrainingList($request->input('department_id'));
                break;
        }
    }

    public function deleteTraining($postData) {
        if ($postData) {
            // $findTraining = Training::where('id', $postData['id'])->first();
            // if($findTraining) {
            //     $deleteUser = Users::where('id', $findEmp->user_id)->delete();
            // }
            $result = Training::where('id', $postData['id'])->delete();
            $result = TraningEmployeeDepartment::where('training_id', $postData['id'])->delete();

            if ($result) {
                $return['status'] = 'success';
                $return['message'] = 'Training delete successfully.';
                $return['jscode'] = "setTimeout(function(){
                        $('#deleteModel').modal('hide');
                        $('#trainingTable').DataTable().ajax.reload();
                    },1000)";
            } else {
                $return['status'] = 'error';
                $return['message'] = 'something will be wrong.';
            }
            echo json_encode($return);
            exit;
        }
    }

}
