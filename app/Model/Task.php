<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Config;
use Illuminate\Support\Facades\DB;
class Task extends Model {

    protected $fillable = ['employee_id', 'task_status', 'complete_progress', 'emp_updated_file'];

    public function addTask($request, $companyId) {
        $objTask = new Task();
        $objTask->company_id = $companyId;
        $objTask->department_id = $request->department;
        $objTask->employee_id = $request->employee;
        $objTask->assign_date = date('Y-m-d', strtotime($request->assign_date));
        $objTask->deadline_date = date('Y-m-d', strtotime($request->deadline_date));
        $objTask->task = $request->task;
        $objTask->priority = $request->priority;
        $objTask->about_task = $request->about_task;
        $objTask->location = $request->location;
        
        if ($request->file()) {
            $image = $request->file('file');
            $file = 'tasks' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/tasks/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $image->move($destinationPath, $file);
            $objTask->file = $file;
        }

        $objTask->created_at = date('Y-m-d H:i:s');
        $objTask->updated_at = date('Y-m-d H:i:s');
        $objTask->save();

        if ($objTask) {
            return TRUE;
        } else {
            return false;
        }
    }

    public function getTaskList($request, $companyId) {
        
        $requestData = $_REQUEST;
        $data = $request->input('data');

        if ($data['priority'] != NULL ) {
            $priority = $data['priority'];
        } else {
            $priority = "";
        }

        /* Don't remove this code as it's in-progress */
         if($data['status'] != NULL || $data['status'] == 0) {
          $status = $data['status'];
          } else {
          $status = "";
          } 
          
          
        $columns = array(
            // datatable column index  => database column name
            0 => 'tasks.id',
            1 => 'tasks.task',
            2 => 'tasks.employee_id',
            3 => 'tasks.priority',
            4 => 'tasks.about_task',
            5 => 'tasks.location',
        );
        $query = Task::join('employee as emp', 'tasks.employee_id', '=', 'emp.id')
                ->where('tasks.company_id', $companyId);
        if ($priority) {
            $query->where('tasks.priority', "=", $priority);
        }
        
        /* Don't remove this code as it's in-progress */
        if($status){
            $query->where('tasks.task_status', "=", $status);
        }

        if (!empty($requestData['search']['value'])) {
            $searchVal = $requestData['search']['value'];
            $query->where(function($query) use ($columns, $searchVal, $requestData) {
                $flag = 0;
                foreach ($columns as $key => $value) {
                    $searchVal = $requestData['search']['value'];
                    if ($requestData['columns'][$key]['searchable'] == 'true') {
                        if ($flag == 0) {
                            $query->where($value, 'like', '%' . $searchVal . '%');
                            $flag = $flag + 1;
                        } else {
                            $query->orWhere($value, 'like', '%' . $searchVal . '%');
                        }
                    }
                }
            });
        }

        $temp = $query->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir']);

        $totalData = count($temp->get());
        $totalFiltered = count($temp->get());

        $resultArr = $query->skip($requestData['start'])
                ->take($requestData['length'])
                ->select('tasks.id','tasks.location', 'tasks.assign_date', 'tasks.deadline_date', 'tasks.task_status', 'tasks.file', 'tasks.task', 'tasks.priority','tasks.complete_progress', 'tasks.about_task', 'tasks.emp_updated_file','tasks.task_status', 'emp.name as emp_name')
                ->get();
        // print_r($resultArr);exit();
        $data = array();
       
        $task_status = Config::get('constants.task_status');
        foreach ($resultArr as $key => $row) {
            $actionHtml = '<a href="#taskDetailsModel" data-toggle="modal" data-id="'.$row['id'].'" title="Details" class="link-black text-sm taskDetails" data-toggle="tooltip" data-original-title="Show"><i class="fa fa-eye"></i></a>'.
                          '<a href="edit-task/'.$row['id'].'" class="link-black text-sm" data-id="'.$row['id'].'" data-toggle="tooltip" data-original-title="Edit Details">&nbsp;&nbsp;<i class="fa fa-edit"></i></a>'.
                          '<a href="task-comments/'.$row['id'].'" class="link-black text-sm" data-id="'.$row['id'].'" data-toggle="tooltip" data-original-title="View Details"> <i class="fa fa-comments"></i></a>';
            
            $nestedData = array();
            $nestedData[] = $row["task"];
            $nestedData[] = $row["emp_name"];
            $nestedData[] = $row["priority"];
            $nestedData[] = $row["task_status"] ? $task_status[$row["task_status"]] : 'New';
            $nestedData[] = $row["complete_progress"].'%';
            $nestedData[] = $row["about_task"];
            $nestedData[] = $row["location"];
            $nestedData[] = $row["file"] ? '<a target="_blank" href="'. url($row["file"]) .'">View File</a>' : 'N.A.';
            $nestedData[] = $actionHtml;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return $json_data;
    }

    public function getEmpTaskList($empId) {
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'tasks.id',
            1 => 'tasks.task',
            2 => 'tasks.employee_id',
            3 => 'tasks.priority',
            4 => 'tasks.about_task',
            4 => 'tasks.location',
        );
        $query = Task::where('tasks.employee_id', $empId);

        if (!empty($requestData['search']['value'])) {
            $searchVal = $requestData['search']['value'];
            $query->where(function($query) use ($columns, $searchVal, $requestData) {
                $flag = 0;
                foreach ($columns as $key => $value) {
                    $searchVal = $requestData['search']['value'];
                    if ($requestData['columns'][$key]['searchable'] == 'true') {
                        if ($flag == 0) {
                            $query->where($value, 'like', '%' . $searchVal . '%');
                            $flag = $flag + 1;
                        } else {
                            $query->orWhere($value, 'like', '%' . $searchVal . '%');
                        }
                    }
                }
            });
        }

        $temp = $query->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir']);

        $totalData = count($temp->get());
        $totalFiltered = count($temp->get());

        $resultArr = $query->skip($requestData['start'])
                ->take($requestData['length'])
                ->select('tasks.assign_date','tasks.location', 'tasks.deadline_date', 'tasks.task', 'tasks.priority', 'tasks.about_task', 'tasks.id')
                ->get();

        $data = array();
       
        foreach ($resultArr as $key => $row) {
          
            
            $actionHtml = '<a href="#taskDetailsModel" class="link-black text-sm taskDetailsModel" data-toggle="modal" data-id="' . $row['id'] . '"  title="View Details" data-toggle="tooltip" data-original-title="View Details">&nbsp;&nbsp;<i class="fa fa-eye"></i></a>'.
                          '<a href="#updateTaskModel" class="link-black text-sm updateTaskModel" data-toggle="modal" data-id="' . $row['id'] . '"  title="Update" data-toggle="tooltip" data-original-title="Update">&nbsp;&nbsp;<i class="fa fa-edit"></i></a>'.
                          '<a href="task-emp-comments/'.$row['id'].'" class="link-black text-sm" data-id="'.$row['id'].'" data-toggle="tooltip" data-original-title="View Details">&nbsp;&nbsp;<i class="fa fa-comments"></i></a>';
            
            $nestedData = array();
            $nestedData[] = $row["task"];
            $nestedData[] = date('m/d/Y', strtotime($row["assign_date"]));
            $nestedData[] = date('m/d/Y', strtotime($row["deadline_date"]));
            $nestedData[] = $row["priority"];
            $nestedData[] = $row["about_task"];
            $nestedData[] = $row["location"];
            $nestedData[] = $actionHtml;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return $json_data;
    }

    public function getEmpviewTaskDetail($taskId,$Empid) {
        $result = Task::select('task', 'file', 'about_task','location' ,'complete_progress','emp_updated_file', 'file', 'task_status','id')->where('employee_id', $Empid)->where('id', $taskId)->first();
        return $result;
    }

    public function updateTaskDetailEmp($request, $empid) {
         print_r($request->input());
         die();
        if($request->input('changeType') == 'shareLocation'){
            $objTask = Task::firstOrNew(array('employee_id' => $empid,'id'=>$request->input('task_id')));
            $objTask->location = $request->location;
            $objTask->save();
            if ($objTask) {
                return TRUE;
            } else {
                return false;
            }
        }
        if($request->input('changeType') == 'setStatus'){
            $objTask = Task::firstOrNew(array('employee_id' => $empid,'id'=>$request->input('task_id')));
            $objTask->task_status = $request->task_status;
            $objTask->save();
            if ($objTask) {
                return TRUE;
            } else {
                return false;
            }
        }
        if($request->input('changeType') == 'uploadMedia'){
            if($request->file()){
                $name = '';
                $image = $request->file('emp_updated_file');
                $name = 'emp_tasks_'.time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/tasks/');
                $image->move($destinationPath, $name);
               
                $objTask = Task::firstOrNew(array('employee_id' => $empid,'id'=>$request->input('task_id')));
                $objTask->emp_updated_file = $name;
                $objTask->save();
                if ($objTask) {
                    return TRUE;
                }else {
                    return false;
                }
            }else{
                return TRUE;
            }
        }
    }

    public function getTaskNotComplitedList() {
        $dates=date('Y-m-d');
        $result = Task::select('task','company_id','id')->where('deadline_date', $dates)->where('task_status','!=', '2')->get()->toArray();
        return $result;
    }
    
    public function newTask($companyid){
        $result = Task::where('task_status','0')
                    ->where('company_id',$companyid)
                    ->count();
        return $result;
    }
    
    public function progressTask($companyid){
        $result = Task::where('task_status','1')
                        ->where('company_id',$companyid)
                        ->count();
        return $result;
    }
    
    public function penddingTask($companyid){
        $result = Task::where('task_status','2')
                        ->where('company_id',$companyid)
                        ->count();
        return $result;
    }
    
    public function completeTask($companyid){
        $result = Task::where('task_status','3')
                        ->where('company_id',$companyid)
                        ->count();
        return $result;
    }
    
    public function taskDetails($id){
         $result = Task::select("tasks.*",'emp.name','emp.father_name','dep.department_name','dep.manager_name')
                ->join('employee as emp', 'tasks.employee_id', '=', 'emp.id')
                ->join('department as dep', 'tasks.department_id', '=', 'dep.id')
                ->where('tasks.id',$id)
                ->get();
          return $result;
    }
    
    public function editTaskDetails($id){
        $result = Task::select("tasks.*",'emp.name','emp.father_name','dep.department_name','dep.manager_name')
                ->join('employee as emp', 'tasks.employee_id', '=', 'emp.id')
                ->join('department as dep', 'tasks.department_id', '=', 'dep.id')
                ->where('tasks.id',$id)
                ->get();
          return $result;
    }
    
    public function editTask($request,$id){
        $name = '';
        
        
        $objTask = Task::find($id);
        if($request->file()){
            $image = $request->file('file');
            $name = 'emp_tasks_'.time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/tasks/');
            $image->move($destinationPath, $name);   
            $objTask->file =$name;
        }
        $objTask->department_id = $request->input('department');
        $objTask->employee_id = $request->input('employee');
        $objTask->assign_date = date('Y-m-d', strtotime($request->input('assign_date')));
        $objTask->deadline_date = date('Y-m-d', strtotime($request->input('deadline_date')));
        $objTask->task = $request->input('task');
        $objTask->priority = $request->input('priority');
        $objTask->about_task = $request->input('about_task');
        $objTask->location = $request->input('location');
        $objTask->save();
        if ($objTask) {
            return TRUE;
        } else {
            return false;
        }
    }
    
    public function deleteImage($request){
        
        $result = DB::table('tasks')
                    ->where('id',$request['id'])
                    ->update(['file' => null,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
        if($result){
            if(file_exists(public_path('/uploads/tasks/'.$request['image']))){
                unlink(public_path('/uploads/tasks/'.$request['image']));
                return true;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    
    public function updatetaskByemployee($request,$empId){
//        print_r($request->file());
//        exit;
//        
        $objTask = Task::find($request->input('task_id'));
        if($request->file()){
            $image = $request->file('emp_updated_file');
            $name = 'emp_tasks_'.time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/tasks/');
            $image->move($destinationPath, $name);   
            $objTask->file =$name;
        }
        $objTask->complete_progress = $request->input('complete_progress');
        $objTask->task_status = $request->input('task_status');
        $objTask->location = $request->input('location');
        $objTask->save();
        if ($objTask) {
            return TRUE;
        } else {
            return false;
        }
    }
}

