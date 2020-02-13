<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Model\Users;
use App\Model\AdminRole;
use App\Model\Department;
use App\Model\AdminUserHasPermission;
use App\Http\Controllers\Controller;
use Auth;
use Route;
use APP;
use Config;
use Illuminate\Http\Request;

class RoleController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('admin');
    }

    public function index(Request $request){
        $session = $request->session()->all();

        $objRole = new AdminRole();
        $data['roleArray'] = $objRole->getAdminRole();
        $data['ArrDepartment'] =  array('1' => 'test','2' => 'test2' );
        $data['role'] =  array('1' => 'Customer','2' => 'Agent');

        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('admin/role.js');
        $data['funinit'] = array('Role.init()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Role',
            'breadcrumb' => array(
                'Home' => route("admin-dashboard"),
                'Role' => 'Role'));
        return view('admin.role.list', $data);
    }
    
    public function add(Request $request){
        $session = $request->session()->all();
        $objRoleMaster = new AdminRole();
        $data['masterPermission'] = $objRoleMaster->getAdminMasterPermisson($request); 

        if($request->isMethod('post')){
//             print_r($request->input());exit;
            $objEmail=new AdminRole();
            $result=$objEmail->createAdminRole($request);
            if($result > 1){
                $return['status'] = 'error';
                $return['message'] = 'Email already exists.';
                // $return['redirect'] = route('role-list');
            }elseif($result){
                $return['status'] = 'success';
                $return['message'] = 'Role created successfully.';
                $return['redirect'] = route('role-list');
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
        $data['js'] = array('admin/role.js');
        $data['funinit'] = array('Role.add()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Role',
            'breadcrumb' => array(
                'Home' => route("admin-dashboard"),
                'Add Role' => 'add-role'));
        return view('admin.role.add', $data);
    }
    
    public function edit(Request $request,$id=null){
        $session = $request->session()->all();
        $data['roleArray'] = AdminRole::find($id);
        $objAdminUserHasPermission = new AdminUserHasPermission();
        $data['permissionList']=$objAdminUserHasPermission->permissionList($id);
//        print_r($data['permissionList']);exit;
        if($request->isMethod('post')){
//            print_r($request->input());exit;
            $objEmail=new AdminRole();
            $result=$objEmail->editAdminRole($request);
            if($result > 1){
                $return['status'] = 'error';
                $return['message'] = 'Email already exists.';
                // $return['redirect'] = route('role-list');
            }elseif($result){
                $return['status'] = 'success';
                $return['message'] = 'Role Updated successfully.';
                $return['redirect'] = route('role-list');
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
        $objRoleMaster = new AdminRole();
        $data['masterPermission'] = $objRoleMaster->getAdminMasterPermisson($request); 

        $data['status'] = Config::get('constants.status');
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('admin/role.js');
        $data['funinit'] = array('Role.edit()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Role',
            'breadcrumb' => array(
                'Home' => route("admin-dashboard"),
                'Edit Role' => 'edit-role'));
        return view('admin.role.edit', $data);
    }

    public function ajaxAction(Request $request) {
        $action = $request->input('action');
        switch ($action) {
            case 'getAdminRoleData':
                $objDemo = new AdminRole();
                $demoList = $objDemo->getAdminRoleData($request,NULL);
                echo json_encode($demoList);
                break;
            case 'deleteRole':
                
                $result = $this->deleteRole($request->input('data'));
                break;
        }
    }

    public function deleteRole($postData) {
        if ($postData) {
            
            $userDelete = User::where('id', $postData['user_id'])->delete();
            if($userDelete){
                $result = AdminRole::where('id', $postData['id'])->delete();
                if($result){
                    $permission = AdminUserHasPermission::where('admin_role_id', $postData['id'])->delete();
                    if ($permission) {
                        $return['status'] = 'success';
                        $return['message'] = 'Record delete successfully.';
                        $return['jscode'] = "setTimeout(function(){
                                $('#deleteModel').modal('hide');
                                location.reload();
                            },1000)";
                    }else{
                        $return['status'] = 'error';
                        $return['message'] = 'something will be wrong.';
                    }
                }else{
                    $return['status'] = 'error';
                    $return['message'] = 'something will be wrong.';
                }
            }else{
                $return['status'] = 'error';
                $return['message'] = 'something will be wrong.';
            }
            echo json_encode($return);
            exit;
        }
    }

}