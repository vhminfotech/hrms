<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Chat;
use App\Model\Users;
use Illuminate\Support\Facades\Auth;
use App\Model\Company;
use App\Model\Employee;
use App\Model\Notification;
use App\Model\NotificationMaster;



class ChatController extends Controller{
    
    public function index(Request $request){
        $userData = Auth::guard('employee')->user();
        $data['userid'] = $userData->id;
        
        if(isset($_COOKIE['employee_chatuserid'])){
            $data['chat'] = "yes";
            $objChatHistory =  new Chat();
            $data['chatdetails'] = $objChatHistory->gethistroy($userData->id,$_COOKIE['employee_chatuserid']);
        }else{
            $data['chat'] = "no";
            $data['chatdetails'] = '';
        }
        
        $data['header'] = array(
            'title' => 'Chat',
            'breadcrumb' => array(
                'Home' => route("employee-dashboard"),
                'Chat view' => 'Chat view'));
                $data['funinit'] = array('Chat.init()');
        $data['js'] = array('employee/chat.js');
        return view('employee.chat.chat',$data);
    }
    
    public function indexNew(Request $request,$userId){
        $objUsers= new Users();
        $data['reciverdetails'] = $objUsers->getreciverdetails($userId);
        
        $userData = Auth::guard('employee')->user();
        $data['userid'] = $userData->id;
        
        if(isset($_COOKIE['employee_chatuserid'])){
            $data['chat'] = "no";
            $data['chatdetails'] = '';
        }else{
            $data['chat'] = "no";
            $data['chatdetails'] = '';
        }
        
        $data['header'] = array(
            'title' => 'Chat',
            'breadcrumb' => array(
                'Home' => route("employee-dashboard"),
                'Chat view' => 'Chat view'));
        
        $data['funinit'] = array('Chat.initdefultopen('.$userId.')');
        $data['js'] = array('employee/chat.js');
        
        return view('employee.chat.chatnew',$data);
    }
    
    public function ajaxAction(Request $request){
        
        $action = $request->input('action');
        switch($action){
            case 'fetch_user':
                $userData = Auth::guard('employee')->user();
                $getAuthEmployeeId = Employee::where('email', $userData->email)->first();
                $logeduserId = $getAuthEmployeeId->user_id;
                $chatObj = new chat();
                $user_fetch = $chatObj->fetch_user_new($logeduserId);
                return $user_fetch;
                break;
            case 'search_user_list':
                $userData = Auth::guard('employee')->user();
                $getAuthEmployeeId = Employee::where('email', $userData->email)->first();
                $logeduserId = $getAuthEmployeeId->user_id;
                $chatObj = new chat();
                $user_fetch = $chatObj->search_user_list_emp($logeduserId,$request->input('search_name'));
                return $user_fetch;
                break;
            case 'update_last_activity':
                $updateActivity = new chat();
                $last_active = $updateActivity->update_last_activity();
                return json_encode($last_active);
                break;
            case 'setuserid':
                setcookie("employee_chatuserid", $request->input('to_user_id'), time() + (86400 * 30),  "/","");
                setcookie("employee_chatusername", $request->input('to_user_name'), time() + (86400 * 30),  "/","");
                
            case 'autorefresh':
                if(isset($_COOKIE['employee_chatuserid'])){
                     $userData = Auth::guard('employee')->user();
                    $sendid = $userData->id;
                    $reciverid = $_COOKIE['employee_chatuserid'];
                    $objChatHistory =  new Chat();
                    $data['chatdetails'] = $objChatHistory->gethistroy($sendid,$reciverid);
                    $data['reciverid'] = $reciverid;
                    return json_encode($data);
                    break;
                }else{
                    return "false";
                    break;
                }
                
            case 'insert_chat':
                $userData = Auth::guard('employee')->user();
                $getAuthEmployeeId = Employee::where('email', $userData->email)->first();
                $logeduserId = $getAuthEmployeeId->user_id;
                // $logedCompanyId = $getAuthEmployeeId->company_id;
                $objCompany = new Company();
                 $logedCompanyId=$objCompany->getUseridById($getAuthEmployeeId->company_id);
               
                $insertChat = new chat();
                $insertData = $insertChat->insert_chat($logeduserId,$request);

                $notificationMasterId=21;
                $objNotificationMaster = new NotificationMaster();
                $NotificationUserStatus=$objNotificationMaster->checkNotificationUserStatus($logedCompanyId,$notificationMasterId);
                
                if($NotificationUserStatus==1)
                {   
                    $userData = Auth::guard('employee')->user();
                    $getAuthEmployeeId = Employee::where('email', $userData->email)->first();
                    $logeduserId = $getAuthEmployeeId->user_id;
                    $objUser =  new Users();
                    $result = $objUser->getUserType($request->input('to_user_id'));
                    if($result == "ADMIN"){
                        $route_url='admin-chat' ;
                    }

                    if($result == "EMPLOYEE"){
                        $route_url=strtolower($result)."/employee-chatnew/".$logeduserId ;
                    }

                    if($result == "COMPANY"){
                        $route_url=strtolower($result)."/chatnew/".$logeduserId ;
                    }
                    //notification add
                    $objNotification = new Notification();
                    $objEmployee = new Employee();
                    $chatMessage="Chat in  New Message.";
                    
                    
                    $ret = $objNotification->addNotification($request->input('to_user_id'),$chatMessage,$route_url,$notificationMasterId);
                }

                $user_fetch = $insertChat->fetchUserLastMessage($logeduserId,$request->input('to_user_id'));
                return $user_fetch;
                // return json_encode($insertData);
                break;
            case 'user-message-list':
                $userData = Auth::guard('employee')->user();
                $getAuthEmployeeId = Employee::where('email', $userData->email)->first();
                $logeduserId = $getAuthEmployeeId->user_id;
                $chatObj = new chat();
                $user_fetch = $chatObj->fetchUserMessageList($logeduserId,$request->input('to_user_id'));
                return $user_fetch;
                break;
        }
        
    }
    
}

