<?php

namespace App\Http\Controllers\Company;

use App\User;
use App\Model\Users;
use App\Model\AdminRole;
use App\Model\Department;
use App\Model\Designation;
use App\Model\AdminUserHasPermission;
use App\Model\Company;
use App\Model\Employee;
use App\Http\Controllers\Controller;
use Auth;
use Route;
use APP;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('company');
    }

    public function index(Request $request){
        $session = $request->session()->all();
        $userid = $this->loginUser->id;
        $companyId = Company::select('id')->where('user_id', $userid)->first();
        
        $objRole = new AdminRole();
        $data['roleArray'] = $objRole->getAdminRoleByCompany($companyId->id);
        $data['ArrDepartment'] =  array('1' => 'test','2' => 'test2' );
        $data['role'] =  array('1' => 'Customer','2' => 'Agent');

        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/role.js');
        $data['funinit'] = array('Role.init()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Role',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                
                'Role' => 'Role'));
        return view('company.role.list', $data);
    }
    
    public function add(Request $request){
        $objRoleMaster = new AdminRole();
        $data['masterPermission'] = $objRoleMaster->getCompanyMasterPermisson($request); 
        $userid = $this->loginUser->id;
        $companyId = Company::select('id')->where('user_id', $userid)->first();
        $data['companyId']=$companyId['id'];
        
        $objEmployeelist = new Employee();
        $data['employeeList'] =  $objEmployeelist->getEmployeeListNew($companyId['id']);
       
        
        if($request->isMethod('post')){
            
            $objAdminRole=new AdminRole();
            $result=$objAdminRole->createEmployeeRole($request);
            if($result == 'userExits'){
                $return['status'] = 'error';
                $return['message'] = 'Email already exists. Or You already asign role to this user.';
            }elseif($result == "added"){
                $return['status'] = 'success';
                $return['message'] = 'Role created successfully.';
                $return['redirect'] = route('company-role-list');
            }else{
                $return['status'] = 'error';
                $return['message'] = "Something goes to wrong.";
            }
            echo json_encode($return);
            exit;
        }

        $objdepartment = new Department();
        $data['ArrDepartment'] =  array('1' => 'test','2' => 'test2' );
        $data['role'] =  array('1' => 'Customer','2' => 'Agent');

        $data['status'] = Config::get('constants.status');
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/role.js');
        $data['funinit'] = array('Role.add()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Role',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Role List' => route("company-role-list"),
                'Add Role' => 'company-add-role'));
        return view('company.role.add', $data);
    }
    
    public function edit(Request $request,$id=null){
        $session = $request->session()->all();
        $data['roleArray'] = AdminRole::find($id);
        if($request->isMethod('post')){
            $objEmail=new AdminRole();
            $result=$objEmail->editCompanyRole($request);
            if($result > 1){
                $return['status'] = 'error';
                $return['message'] = 'Email already exists.';
                // $return['redirect'] = route('role-list');
            }elseif($result){
                $return['status'] = 'success';
                $return['message'] = 'Role Updated successfully.';
                $return['redirect'] = route('company-role-list');
            }else{
                $return['status'] = 'error';
                $return['message'] = "Something goes to wrong.";
            }
            echo json_encode($return);
            exit;
        }

        $objdepartment = new Department();
        $data['ArrDepartment'] =  array('1' => 'test','2' => 'test2' );
        
        $adminR = new AdminUserHasPermission();
        $userPermission = $adminR->getPermissionNew($id);
        $permission = array();
        for($i=0; $i<count($userPermission); $i++){
            $permission[$i] = $userPermission[$i]->permission_id;
        }
        
        $data['userPermission'] = $permission;        
        $objRoleMaster = new AdminRole();
        $data['masterPermission'] = $objRoleMaster->getCompanyMasterPermisson($request); 

        $data['status'] = Config::get('constants.status');
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/role.js');
        $data['funinit'] = array('Role.edit()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Role',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Role List' => route("company-role-list"),
                'Edit Role' => 'company-edit-role'));
        return view('company.role.edit', $data);
    }

    public function ajaxAction(Request $request) {
        $action = $request->input('action');

        $session = $request->session()->all();
        $userid = $this->loginUser->id;
        $companyId = Company::select('id')->where('user_id', $userid)->first();

        switch ($action) {
            case 'getAdminRoleData':
                $objDemo = new AdminRole();
                $demoList = $objDemo->getAdminRoleData($request,$companyId->id);
                echo json_encode($demoList);
                break;
            case 'deleteRole':
                $result = $this->deleteRole($request->input('data'));
                break;
            case 'employeeType':
                $objRoleMaster = new AdminRole();
                $data['masterPermission'] = $objRoleMaster->getCompanyMasterPermisson($request); 
                if($request->input('val') == 'newEmployee'){
                    $objdepartment = new Department();
                    $objDesignation = new Designation();
                    $data['ArrDepartment'] = $objdepartment->getDepartment();
                    $data['ArrDesignation'] = $objDesignation->getDesignation();
                    $data['testarray'] = Config::get('constants.testarray');
                    $data['statusArray'] = Config::get('constants.statusArray');
                    $data['genderArray'] = Config::get('constants.genderArray');
                    $data['martialArray'] = Config::get('constants.martialArray');
                    
                    $data['nationalityArray']=DB::table('country')
                                            ->select('country_name','id')
                                            ->get()->toarray();
                    
                    $userid = $this->loginUser->id;
                    $companyId = Company::select('id')->where('user_id', $userid)->first();
                    $data['companyId']=$companyId['id'];
                    $data['status'] = Config::get('constants.status');
                    $result = view('company.role.newEmployee',$data);
                    echo $result;
                    break;
                }else{
                    $userid = $this->loginUser->id;
                    $companyId = Company::select('id')->where('user_id', $userid)->first();
                    $data['companyId']=$companyId['id'];

                    $objEmployeelist = new Employee();
                    $data['employeeList'] =  $objEmployeelist->getEmployeeList($companyId['id']);
                    
                    $result = view('company.role.existingEmployee',$data);
                    echo $result;
                    break; 
                }
                
        }
    }

    public function deleteRole($postData) {
        if ($postData) {
            $result = AdminRole::where('id', $postData['id'])->delete();
            $result = AdminUserHasPermission::where('admin_role_id', $postData['id'])->delete();
            if ($result) {
                $return['status'] = 'success';
                $return['message'] = 'Record delete successfully.';
                $return['jscode'] = "setTimeout(function(){
                        $('#deleteModel').modal('hide');
                        location.reload();
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