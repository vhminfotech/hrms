<?php

namespace App\Http\Controllers\Employee;

use App\User;
use App\Model\Users;
use App\Model\Employee;
use App\Model\Company;
use App\Model\Department;
use App\Model\Award;
use App\Http\Controllers\Controller;
use Auth;
use Route;
use APP;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Model\Payroll;
use App\Model\Notification;
use App\Model\NotificationMaster;

class CompanyPayrollController extends Controller {

    public function __construct() {
         parent::__construct();
        $this->middleware('employee');
    }

    public function index(Request $request) {
        $data['detail'] = $this->loginUser;
        $data['header'] = array(
            'title' => 'Payroll Employee List',
            'breadcrumb' => array(
                'Home' => route("company-dashboard")));
       
        $data['departmentId'] = (empty($request->get('department'))) ? '' : $request->get('department');
        $data['employeeId'] = (empty($request->get('employee'))) ? '' : $request->get('employee');
        
        $EmpObj = new Employee;
        
        $userid = $this->loginUser->id;
        $companyId = Employee::select('company_id')->where('user_id', $userid)->get();
        $userID = Company::select('user_id')->where('id', $companyId[0]['company_id'])->get();
        $company_Id = $companyId[0]['company_id'];
        
        $data['allemployee'] = $EmpObj->getAllEmployeeofCompany($company_Id, $data['departmentId'] , $data['employeeId']);
        
        $objDepart = new Department();
        $data['department'] = $objDepart->getDepartmentCompany($company_Id);   

        $objEmployee = new Employee();
        $data['employee'] = $objEmployee->getEmployee($company_Id);
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('employee/companypayroll.js');
        $data['funinit'] = array('Payroll.init()');
        $data['css'] = array('');
        return view('employee.companypayroll.payroll-list', $data);
    }

    public function payrollEmpList($id)
    {
        $data['detail'] = $this->loginUser;
        $EmpObj = new Employee;
        $data['singleemployee'] = $EmpObj->getAllEmployee($id);
         $data['header'] = array(
            'title' => 'Payroll '. $data['singleemployee']['name'] .' List',
            'breadcrumb' => array(
                'Home' => route("company-dashboard")));
        $EmpObj = new Employee;

        $PayrollObj = new Payroll;
        $data['arrayPayroll'] = $PayrollObj->getPayroll($id);
        $data['empId'] = $id;
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('employee/companypayroll.js', 'ajaxfileupload.js', 'jquery.form.min.js');
        $data['funinit'] = array('Payroll.init()');
        $data['css'] = array('plugins/jasny/jasny-bootstrap.min.css');
        
        return view('employee.companypayroll.payroll-employee-list', $data);
    }

    public function add(Request $request,$id) {

        if($request->ajax()){
            
            $payrollobj = new Payroll();
            $ret = $payrollobj->addnewpayroll($request,$id);

            if ($ret == 'Exists') {
                $return['status'] = 'error';
                $return['message'] = 'Payroll Already Exists.';
                // $return['redirect'] = route('payroll-add',array('id'=> $id));
            } elseif ($ret == 'Added') {

                $objCompany = new Company();
                $company_id=$objCompany->getUseridById($request->input('companyId'));

                $notificationMasterId=2;
                $objNotificationMaster = new NotificationMaster();
                $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatus($company_id,$notificationMasterId);
                
                if($NotificationUserStatus==1)
                {

                    //notification add
                    $objNotification = new Notification();
                    $objEmployee = new Employee();
                    $empName=$objEmployee->getAllEmployee($id);
                    $payrollName=$empName['name']." is a new information in payroll.";
                    
                    $u_id=$objEmployee->getUseridById($id);
                    $route_url="payroll-employee";
                    $ret = $objNotification->addNotification($u_id,$payrollName,$route_url,$notificationMasterId);
                }
                
                $return['status'] = 'success';
                $return['message'] = 'Payroll added successfully.';
                $return['redirect'] = route('employee-payroll-add',array('id'=> $id));
            } else {
                $return['status'] = 'error';
                $return['message'] = 'something will be wrong.';
            }
            echo json_encode($return);
            exit;
        }

        $data['monthis'] = Config::get('constants.months');
        $data['employee'] = Employee::find($id);
        $data['detail'] = $this->loginUser;
        $data['header'] = array(
            'title' => 'Create new payroll',
            'breadcrumb' => array(
                'Home' => route("company-dashboard")));
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');

        $data['js'] = array('employee/companypayroll.js', 'ajaxfileupload.js', 'jquery.form.min.js');
        $data['funinit'] = array('Payroll.init()');
        $data['css'] = array('plugins/jasny/jasny-bootstrap.min.css');

        return view('employee.companypayroll.payroll-add', $data);
    } 
    public function edit(Request $request,$id)
    {
        $payrollObj = new Payroll;
        $arrayPayroll = $payrollObj->getPayrollV2($id);
        $data['decodeJsonOfAllowance'] = json_decode($arrayPayroll[0]['extra_allowance']);
        $data['arrayPayroll'] = $arrayPayroll[0];
        $data['decodeJsonOfDeduction'] = json_decode($arrayPayroll[0]['extra_deduction']);
        $data['arrayPayroll'] = $arrayPayroll[0];
        $data['employee'] = Employee::find($data['arrayPayroll']['employee_id']);
        if($request->ajax()){
        // if ($request->isMethod('post')) {
            $payrollobj = new Payroll();
            $ret = $payrollobj->editPayroll($request,$id);
            if ($ret == 'Exists' &&  $ret != true) {
                $return['status'] = 'error';
                $return['message'] = 'Payroll Already Exists.';
                // $return['redirect'] = route('payroll-add',array('id'=> $id));
            }elseif ($ret) {

                //notification add

               
            //    $company_id=$request->input('companyId');

               $objCompany = new Company();
                $company_id=$objCompany->getUseridById($request->input('companyId'));

                $notificationMasterId=2;
                $objNotificationMaster = new NotificationMaster();
                $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatus($company_id,$notificationMasterId);
                
                if($NotificationUserStatus==1)
                {
                    $objNotification = new Notification();
                    $objEmployee = new Employee();
                    $empName=$objEmployee->getAllEmployee($request->input('empId'));
                    $payrollName=$empName['name']." is a update information in payroll.";
                
                    $u_id=$objEmployee->getUseridById($request->input('empId'));
                    $route_url="payroll-employee";
                    $ret = $objNotification->addNotification($u_id,$payrollName,$route_url,$notificationMasterId);
                }

                $return['status'] = 'success';
                $return['message'] = 'Payroll updated successfully.';
                $return['redirect'] = route('employee-payroll-emp-detail',array('id'=> $request->input('empId')));
            } else {
                $return['status'] = 'error';
                $return['message'] = 'something will be wrong.';
            }
            echo json_encode($return);
            exit;
        }
        $data['monthis'] = Config::get('constants.months');
        $data['detail'] = $this->loginUser;
        $data['header'] = array(
            'title' => 'Create new payroll',
            'breadcrumb' => array(
                'Home' => route("company-dashboard")));
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');

        $data['js'] = array('company/payroll.js', 'ajaxfileupload.js', 'jquery.form.min.js');
        $data['funinit'] = array('Payroll.init()');
        $data['css'] = array('plugins/jasny/jasny-bootstrap.min.css');

        return view('company.payroll.payroll-add', $data);
    }

    public function ajaxAction(Request $request) 
    {
        $action = $request->input('action');
        switch ($action) {
            
            case 'deletePayroll':
                $result = $this->deletePayroll($request->input('data'));
                break; 
            case 'getBankDetails':
                $postData = $request->input('data');
                $empDetail = Employee::find($postData['id']);
                echo json_encode($empDetail);exit;
                break;
            case 'saveBankDetails':
                $objEmployee = new Employee();
                $result = $objEmployee->editBankDetails($request->input('data'));
                if ($result) {
                        $return['status'] = 'success';
                        $return['message'] = 'Bank Details updated successfully.';
                        $return['jscode'] = "setTimeout(function(){
                                $('#updateBankModel').modal('hide');
                            },1000)";
                } else {
                    $return['status'] = 'error';
                    $return['message'] = 'Something will be wrong.';
                }
                echo json_encode($return);
                exit;
                break;
        }
    }

    public function deletePayroll($postData) 
    {
        if ($postData) 
        {
            $result = Payroll::where('id', $postData['id'])->delete();
            if ($result) {
                $return['status'] = 'success';
                $return['message'] = 'Record deleted successfully.';
                $return['jscode'] = "setTimeout(function(){
                        $('#deleteModel').modal('hide');
                        $('#DepartmentDatatables').DataTable().ajax.reload();
                    },1000)";
            } else {
                $return['status'] = 'error';
                $return['message'] = 'Something will be wrong.';
            }
            echo json_encode($return);
            exit;
        }
    }

    public function payrollCheckAward(Request $request)
    {
        $count = 0;
        if($request->months != '' && $request->year != '' && $request->empid != '')
        {
            $from_date = $request->year.'-'.$request->months.'-1';
            $to_date = $request->year.'-'.$request->months.'-31';
            $awards = Award::where('employee_id',$request->empid)->whereBetween('date',[$from_date,$to_date])->get()->toArray();
            
            if($awards && !empty($awards))
            {
                
                foreach ($awards as $key => $value) {
                    $count = $count + $value['award'];
                }
            }
        }
        return $count;
    }

}
