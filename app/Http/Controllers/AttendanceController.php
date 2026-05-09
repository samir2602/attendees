<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use DateTime;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $month = (isset($_GET['month'])) ? $_GET['month'] : date('Y-m');
        $attendance = Attendance::where('date', 'like', "%$month%")->orderBy('date', 'asc')->get();
        return view('attendance.index', compact('attendance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request['leave_day']){
            $data = $request->except('leave_day');
            $data['work_in'] = '00:00';
            $data['work_out'] = '00:00';
            $data['break_hours'] = '0';
            $data['working_hours'] = '0';
            $data['over_time'] = '0';
            $data['under_time'] = '480';            
            $is_entry = Attendance::where('date', $request['date'])->first();
            if($is_entry){
                Attendance::where('date', $request['date'])->update($data);
            }else{                
                Attendance::create($data);
            }
            return redirect()->route('dashboard');
        }

        $data = $request->all();
        $days_in_current_month = date('t');
        $current_year = date('Y');
        $current_month = date('m');
        $firstDay = strtotime("$current_year-$current_month-01");
        $lastDay = strtotime(date("Y-m-t", $firstDay));
        $daily_work_hours = 8;
        $count = 0;
        for ($i = $firstDay; $i <= $lastDay; $i = strtotime("+1 day", $i)) {
            if (date("w", $i) == 0) {
                $count++;
            }
        }
        
        $working_days = $days_in_current_month - $count; // this month current working day
        $working_hours = $working_days * 8; // this month current working hours        
        $start = new DateTime($data['work_in']);
        $end = new DateTime($data['work_out']);
        $interval = $start->diff($end);
        $working_hours = $interval->format('%H:%I'); // total working hours of the day
        list($hours, $minutes) = explode(":", $working_hours);
        $total_working_minutes = ($hours * 60) + $minutes - $data['break_hours'];  // total working minutes of the day                

        $is_entry = Attendance::where('date', $data['date'])->first();
        if($is_entry){
            $update_data = $request->except('_token','day');            
            if($total_working_minutes > 480){
                $update_data['under_time'] = 0;
                $update_data['over_time'] = $total_working_minutes - 480;
            }else{
                $update_data['over_time'] = 0;
                $update_data['under_time'] = 480 - $total_working_minutes;
            }
            $update_data['working_hours'] = $total_working_minutes;
            Attendance::where('date', $data['date'])->update($update_data);
        }else{            
            if($total_working_minutes > 480){
                $data['under_time'] = 0;
                $data['over_time'] = $total_working_minutes - 480;
            }else{
                $data['over_time'] = 0;
                $data['under_time'] = 480 - $total_working_minutes;
            }
            $data['working_hours'] = $total_working_minutes;            
            Attendance::create($data);
        }
        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {        
        return view('attendance.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        if($request['leave_day']){
            $is_entry = Attendance::where('date', $request['date'])->first();
            $leave['work_in'] = '00:00';
            $leave['work_out'] = '00:00';
            $leave['break_hours'] = '00:00';
            $leave['working_hours'] = '00:00';
            $leave['over_time'] = '0';
            $leave['under_time'] = '480';            
            if($is_entry){
                Attendance::where('date', $request['date'])->update($leave);
                return redirect()->route('dashboard');
            }
        }

        $data = $request->all();
        $days_in_current_month = date('t');
        $current_year = date('Y');
        $current_month = date('m');
        $firstDay = strtotime("$current_year-$current_month-01");
        $lastDay = strtotime(date("Y-m-t", $firstDay));
        $daily_work_hours = 8;
        $count = 0;
        for ($i = $firstDay; $i <= $lastDay; $i = strtotime("+1 day", $i)) {
            if (date("w", $i) == 0) {
                $count++;
            }
        }
        
        $working_days = $days_in_current_month - $count; // this month current working day
        $working_hours = $working_days * 8; // this month current working hours        
        $start = new DateTime($data['work_in']);
        $end = new DateTime($data['work_out']);
        $interval = $start->diff($end);
        $working_hours = $interval->format('%H:%I'); // total working hours of the day
        list($hours, $minutes) = explode(":", $working_hours);
        $working_hours = ($hours - 1).':'.$minutes;        
        $total_working_minutes = ($hours * 60) + $minutes - $data['break_hours'];  // total working minutes of the day        

        if($total_working_minutes > 480){
            $data['under_time'] = 0;
            $data['over_time'] = $total_working_minutes - 480;
        }else{
            $data['over_time'] = 0;
            $data['under_time'] = 480 - $total_working_minutes;
        }
        $data['working_hours'] = $total_working_minutes;
        $attendance->update($data);
        
        return redirect()->route('attendance.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendance.index');
    }
}
