<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Department;
use App\Model\Employee;
use App\Model\Company;
use App\Model\Attendance;
use App\Model\AttendanceHistory;
use Auth;
use Route;

class AttendanceController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('company');
    }

    public function dailyAttendance(Request $request) {
        $data['date'] = "";
        $userid = $this->loginUser->id;
        $companyId = Company::select('id')->where('user_id', $userid)->get();
        if (!empty($request->get('departentId'))) {
            $data['departentId'] = $request->get('departentId');
            $dateformate = date('Y-m-d', strtotime($request->get('date')));
            $data['date'] = $request->get('date');
            if ($request->get('departentId') == 'all') {
                $data['departmentname'] = "All Department";
                $data['getEmployees'] = Employee::select('employee.name', 'employee.id', 'employee.user_id', 'attendance.reason', 'attendance.attendance')
                        ->join('department', 'employee.department', '=', 'department.id')
                        ->join('comapnies', 'department.company_id', '=', 'comapnies.id')
                        ->leftjoin('attendance', 'employee.user_id', '=', 'attendance.user_id')
                        ->where('comapnies.user_id', $userid)
                        ->where('attendance.date', $dateformate)
                        ->get();
                if (count($data['getEmployees']) == 0) {
                    $data['getEmployees'] = Employee::select('employee.name', 'employee.id', 'employee.user_id')
                            ->join('department', 'employee.department', '=', 'department.id')
                            ->join('comapnies', 'department.company_id', '=', 'comapnies.id')
                            ->leftjoin('attendance', 'employee.user_id', '=', 'attendance.user_id')
                            ->where('comapnies.user_id', $userid)
                            ->get();
                }
            } else {
                $departmentname = Department::select('id', 'department_name')->where('id', $data['departentId'])->first();

                $data['getEmployees'] = Employee::select('employee.name', 'employee.id', 'employee.user_id', 'attendance.reason', 'attendance.attendance')
                        ->join('department', 'employee.department', '=', 'department.id')
                        ->join('comapnies', 'department.company_id', '=', 'comapnies.id')
                        ->leftjoin('attendance', 'employee.user_id', '=', 'attendance.user_id')
                        ->where('comapnies.user_id', $userid)
                        ->where('employee.department', $departmentname->id)
                        ->where('attendance.date', $dateformate)
                        ->get();

                if (count($data['getEmployees']) == 0) {

                    /* Don't remove this query => It's working */
                    /* $data['getEmployees'] = Employee::select('employee.name','employee.id','employee.user_id')
                      ->groupBy('employee.id')
                      ->join('department', 'employee.department', '=', 'department.id')
                      ->join('comapnies', 'department.company_id', '=', 'comapnies.id')
                      ->join('attendance', 'employee.user_id', '=', 'attendance.user_id')
                      ->where('comapnies.user_id', $userid)
                      ->where('employee.department', $departmentname->id)
                      ->get(); */

                    $data['getEmployees'] = Employee::select('employee.name', 'employee.id', 'employee.user_id')
                            ->where('company_id', $companyId[0]['id'])
                            ->where('employee.department', $departmentname->id)
                            ->get();
                }
                // $data['getEmployees'] = Employee::where('department', $departmentname->id)->get();  
                $data['departmentname'] = $departmentname['department_name'];
            }
        }
        if ($request->isMethod('post')) {
            $objAttendance = new Attendance;
            $result = $objAttendance->saveAttendance($request);
            if ($result) {
                $return['status'] = 'success';
                $return['message'] = 'Attendance created successfully.';
                $return['redirect'] = route('daily-attendance');
            } else {
                $return['status'] = 'error';
                $return['message'] = 'Something goes to wrong';
            }
            echo json_encode($return);
            exit;
        }
        $data['detail'] = Department::where('company_id', $companyId[0]['id'])->get();
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/daily_attendance.js', 'jquery.form.min.js');
        $data['funinit'] = array('DailyAttendance.init()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Daily Attendance',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Daily Attendance' => 'Daily Attendance'));

        return view('company.attendance.daily-attendance', $data);
    }

    public function attendanceReport(Request $request) {
        $userId = $this->loginUser->id;
        $companyId = Company::select('id')->where('user_id', $userId)->first();
        $data['detail'] = Department::where('company_id', $companyId['id'])->get();
        $employeAttandanceData = array();
        if (!empty($request->get('departentId'))) {
            $data['departentId'] = $request->get('departentId');
            $data['get_year'] = $request->get('year');
            $data['get_month'] = $request->get('month');
            $data['month'] = $request->get('month');

            if ($data['get_month'] == 1) {
                $data['get_month'] = 'January';
            } elseif ($data['get_month'] == 2) {
                $data['get_month'] = 'February';
            } elseif ($data['get_month'] == 3) {
                $data['get_month'] = 'March';
            } elseif ($data['get_month'] == 4) {
                $data['get_month'] = 'April';
            } elseif ($data['get_month'] == 5) {
                $data['get_month'] = 'May';
            } elseif ($data['get_month'] == 6) {
                $data['get_month'] = 'June';
            } elseif ($data['get_month'] == 7) {
                $data['get_month'] = 'July';
            } elseif ($data['get_month'] == 8) {
                $data['get_month'] = 'August';
            } elseif ($data['get_month'] == 9) {
                $data['get_month'] = 'September';
            } elseif ($data['get_month'] == 10) {
                $data['get_month'] = 'October';
            } elseif ($data['get_month'] == 11) {
                $data['get_month'] = 'November';
            } elseif ($data['get_month'] == 12) {
                $data['get_month'] = 'December';
            }

            if ($request->get('departentId') == 'all') {
                $data['departmentname'] = "All Employees";
                /* $departmentName = Department::select('id', 'department_name')->where('id', $request->get('departentId'))->first(); */

                $data['getAttedanceReport'] = Attendance::select('employee.name', 'attendance.id', 'attendance.date', 'attendance.attendance')
                                ->join('employee', 'attendance.emp_id', '=', 'employee.id')
                                ->whereYear('attendance.date', '=', $request->year)
                                ->whereMonth('attendance.date', '=', $request->month)
                                // ->where('department_id', $departmentName->id)
                                ->get()->toArray();
                for ($i = 0; $i < count($data['getAttedanceReport']); $i++) {
                    $employeename = $data['getAttedanceReport'][$i]['name'];
                    $employeAttandanceData[$employeename][] = $data['getAttedanceReport'][$i];
                    $employeAttandanceData[$employeename]['date'][] = date('j', strtotime($data['getAttedanceReport'][$i]['date']));
                }
            } else {
                $departmentName = Department::select('id', 'department_name')->where('id', $request->get('departentId'))->first();
                $data['departmentname'] = $departmentName['department_name'];

                $data['getAttedanceReport'] = Attendance::select('employee.name', 'attendance.id', 'attendance.date', 'attendance.attendance')
                                ->join('employee', 'attendance.emp_id', '=', 'employee.id')
                                ->whereYear('attendance.date', '=', $request->year)
                                ->whereMonth('attendance.date', '=', $request->month)
                                ->where('employee.department', $departmentName->id)
                                ->get()->toArray();
                // dd($data['getAttedanceReport']);
                for ($i = 0; $i < count($data['getAttedanceReport']); $i++) {
                    $employeename = $data['getAttedanceReport'][$i]['name'];
                    $employeAttandanceData[$employeename][] = $data['getAttedanceReport'][$i];
                    $employeAttandanceData[$employeename]['date'][] = date('j', strtotime($data['getAttedanceReport'][$i]['date']));
                }
            }
        }




//        print_r($employeAttandanceData);exit;
        $data['employeAttandanceData'] = $employeAttandanceData;
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/attendance_report.js', 'jquery.form.min.js');
        $data['funinit'] = array('AttendanceReport.init()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Attendance Report',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Attendance Report' => 'Attendance Report'));

        return view('company.attendance.attendance-report', $data);
    }

    public function manageAttendanceHistory(Request $request) {
        $data['fromdate'] = "";
        $data['toDate'] = "";
        $data['department_id'] = "";

        if ($request->method('get')) {
            $data['fromdate'] = $request->input('from_date');
            $data['toDate'] = $request->input('to_date');
            $data['department_id'] = $request->input('department_id');
        }

        $userid = $this->loginUser->id;
        $companyId = Company::select('id')->where('user_id', $userid)->first();
        $data['departmentList'] = Department::where('company_id', $companyId['id'])->get();
        $data['pluginjs'] = array('jQuery/jquery.validate.min.js');
        $data['js'] = array('company/attendance_history.js', 'jquery.form.min.js');
        $data['funinit'] = array('AttendanceHistory.init()');
        $data['css'] = array('');
        $data['header'] = array(
            'title' => 'Manage Attendance History',
            'breadcrumb' => array(
                'Home' => route("company-dashboard"),
                'Manage Attendance History' => 'Manage Attendance History'));

        return view('company.attendance.manage-attendance-history', $data);
    }

    public function ajaxAction(Request $request) {

        $action = $request->input('action');
        switch ($action) {
            case 'getdatatable':
                $objAttendanceHistory = new AttendanceHistory();
                $historyList = $objAttendanceHistory->getDataTableForHistoy($request);
                echo json_encode($historyList);
                break;
            case 'getHistoryDetails':
                $result = $this->getHistoryDetails($request->input('data'));
                break;
        }
    }

    public function getHistoryDetails($postData) {
        $userId = $this->loginUser->id;
        $companyId = Company::select('id')->where('user_id', $userId)->first();
        $return = AttendanceHIstory::select('employee.name', 'leaves.start_date', 'leaves.end_date', 'leaves.type_of_req_id', 'time_change_requests.request_type', 'time_change_requests.from_date', 'time_change_requests.to_date', 'leaves.reason', 'time_change_requests.date_of_submit', 'time_change_requests.total_hours', 'time_change_requests.request_description', 'time_change_requests.status')
                ->join('employee', 'attendance_history.employee_id', '=', 'employee.id')
                // ->join('department', 'attendance_history.department_id', '=', 'department.id')
                ->leftjoin('time_change_requests', 'attendance_history.time_change_request_id', '=', 'time_change_requests.id')
                ->leftjoin('leaves', 'attendance_history.leave_id', '=', 'leaves.id')
                ->where('attendance_history.company_id', $companyId->id)
                ->where('attendance_history.id', $postData)
                ->first();
        echo json_encode($return);
        exit;
    }

}
