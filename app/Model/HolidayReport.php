<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use App\Model\UserHasPermission;
use App\Model\Sendmail;
use App\Model\Ticket;
use App\Model\AttendanceHistory;
use Config;

class HolidayReport extends Model {

    protected $table = 'holiday_report';

    public function addHolidayReport($postData, $company_id)
    {
        // print_r($id);
        // print_r($postData);exit;
        // $empCount = AttendanceHistory::where('employee_id', '=', $id)
        //         ->count();
        // if ($empCount > 0) {
        //     $ticketCount = HolidayReport::where('employee_id', '=', $id)
        //                     ->where('department_id', '=', $postData['dept_id'])
        //                     ->count();
        //     if($ticketCount == 0){
                // echo "<pre>"; print_r($postData); exit();
                $ticketNumber = $this->getHolidayNumber();
                $objPayroll = new HolidayReport();
                $objPayroll->employee_id = $postData['emp_id']=='all'?0:$postData['emp_id'];
                $objPayroll->company_id = $company_id;
                $objPayroll->department_id = $postData['dept_id']=='all'?0:$postData['dept_id'];
                $objPayroll->holiday_report_number = $ticketNumber;
                $objPayroll->download_date = date('Y-m-d');
                $objPayroll->created_at = date('Y-m-d H:i:s');
                $objPayroll->updated_at = date('Y-m-d H:i:s');
                $objPayroll->save();    
                // $objPayroll = '';
            // }                
        // } 
        return ['holiday_report_number'=>$ticketNumber,'download_date'=>date('Y-m-d')];
    }

    public function getHolidayNumber()
    {
        $ticketCount =  DB::table('system_generate_no')->where('id',2)->first();
        $str = ltrim($ticketCount->generated_no, '0');
        $str = (empty($str)) ? 1 : $str;
        $tolalLength = 4;
        $forCount = $tolalLength - strlen($str);
        $num =  $str+1;
        $generateString = '';
        for ($i=1; $i <= $forCount; $i++) { 
            $generateString .= 0;
        }
        DB::table('system_generate_no')->where('id',2)
            ->update(['generated_no' => $generateString.$num]);
        return $generateString.$num;
    }
    
    public function getHolidayReportPdfDetail($postData, $id)
    {
        $result = AttendanceHistory::select('attendance_history.*','holiday_report.id as holiday_report_id','holiday_report.holiday_report_number','holiday_report.download_date','employee.id as emp_id', 'employee.name as empName', 'comapnies.company_name','leaves.start_date', 'leaves.end_date', 'leaves.type_of_req_id', 'department.department_name', 'time_change_requests.request_type', 'time_change_requests.from_date', 'time_change_requests.to_date','leaves.reason')
                ->join('holiday_report', 'holiday_report.employee_id', '=', 'attendance_history.employee_id')
                ->join('employee', 'employee.id', '=', 'attendance_history.employee_id')
                ->join('department','department.id', '=', 'employee.department' )
                ->leftjoin('comapnies', 'comapnies.id', '=', 'employee.company_id')
                ->leftjoin('tickets', 'tickets.assign_to', '=', 'attendance_history.employee_id')
                ->leftjoin('time_change_requests', 'attendance_history.time_change_request_id', '=', 'time_change_requests.id')
                ->leftjoin('leaves', 'attendance_history.leave_id', '=', 'leaves.id')
                ->where('attendance_history.employee_id', $id)
                ->groupBy('attendance_history.id')
                ->get()->toArray();
        return $result;
    }

    public function generateHolidayReport($request,$company_id)
    {
        // echo "<pre>"; print_r($request->toArray()); exit();
        $query_ticket = AttendanceHistory::select('attendance_history.*','employee.id as emp_id','employee.name as empName','comapnies.company_name','department.department_name','leaves.start_date', 'leaves.end_date', 'leaves.type_of_req_id', 'department.department_name', 'time_change_requests.request_type', 'time_change_requests.from_date', 'time_change_requests.to_date','leaves.reason')
                    ->join('employee', 'employee.id', '=', 'attendance_history.employee_id')
                    ->join('department','department.id', '=', 'employee.department' )
                    ->leftjoin('comapnies', 'comapnies.id', '=', 'employee.company_id')
                    ->leftjoin('time_change_requests', 'attendance_history.time_change_request_id', '=', 'time_change_requests.id')
                    ->leftjoin('leaves', 'attendance_history.leave_id', '=', 'leaves.id');

                    if($request->dept_id == 'all' && $request->emp_id == 'all')
                    {

                    }
                    elseif ($request->dept_id == 'all' && $request->emp_id != 'all') 
                    {
                        $query_ticket->where('attendance_history.employee_id',$request->emp_id);                        
                    }
                    elseif ($request->dept_id != 'all' && $request->emp_id == 'all')
                    {
                        $query_ticket->where('employee.department',$request->dept_id);
                    }
                    else
                    {
                        $query_ticket->where('attendance_history.employee_id',$request->emp_id)
                                ->where('employee.department',$request->dept_id);   
                    }

                    $holidays = $query_ticket->where('attendance_history.company_id',$company_id)
                    ->get()->toArray();

        // echo "<pre>"; print_r($holidays); exit();
        return $holidays;
    }

    public function getHolidaySystemData()
    {
        $result = HolidayReport::select('holiday_report.*')
                            ->leftjoin('employee', 'employee.id', '=', 'holiday_report.employee_id')
                            ->get()->toArray();
        return $result;
    }

    public function getAllEmployeeForHoliday($cId){
        $result = AttendanceHistory::where('company_id', $cId)->select(DB::raw('GROUP_CONCAT(DISTINCT attendance_history.employee_id SEPARATOR ",") AS empId'))->orderBy('attendance_history.employee_id')->get()->toArray();
        return $result;
    }
}
