<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use App\Model\UserHasPermission;
use App\Model\Sendmail;
use App\Model\Users;
use App\Model\Attendance;
use PDF;
use Config;
use File;

class Attendance extends Model
{
    protected $fillable = ['date', 'user_id', 'department_id', 'emp_id', 'attendance', 'reason', 'created_at', 'updated_at'];
    protected $table = 'attendance';

    public function saveAttendance($request){
       
        $date = $request->input('date');
        $department_id = $request->input('department_id');
        // Array
        $empid = $request->input('emp_id');
        $attendance = $request->input('attendance');
        $reason = $request->input('reason');
        $user_id = $request->input('user_id');
        
        for($i=0;$i< count($user_id) ; $i++){
        // $user = User::firstOrNew(array('name' => Input::get('name')));
        // $user->foo = Input::get('foo');
        // $user->save();
             
            $saveattendance = Attendance::firstOrNew(array('date' => date('Y-m-d',  strtotime($date)),'user_id' => $user_id[$i],'emp_id' => $empid[$i]));
            //Save new OR update(if exist) attendance record
            $saveattendance->date = date('Y-m-d', strtotime($date));
            // $saveattendance->department_id = $department_id;
            $saveattendance->user_id = $user_id[$i];
            $saveattendance->emp_id = $empid[$i];
            $saveattendance->attendance = $attendance[$i];
            $saveattendance->reason = $reason[$i];
            $saveattendance->save();
        }
        return true;
    }
}
