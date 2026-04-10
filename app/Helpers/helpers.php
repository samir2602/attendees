<?php

if (!function_exists('min_to_hour')) {
    /**
     * Format date to d-m-Y
     */
    function min_to_hour($data)
    {
        $minutes = $data;
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        $time = sprintf('%02d:%02d', $hours, $remainingMinutes);
        return $time." Hours";        
    }
}

if (!function_exists('day_pay')){
    function day_pay($date = null, $work_in = '00:00:00', $work_out = '00:00:00', $break_hours = ''){        
        $days_in_month = date('t', strtotime($date));
        $year = date('Y',strtotime($date));
        $month = date('m', strtotime($date));
        $firstDay = strtotime("$year-$month-01");
        $lastDay = strtotime(date("Y-m-t", $firstDay));
        $daily_work_hours = 8;
        $count = 0;
        for ($i = $firstDay; $i <= $lastDay; $i = strtotime("+1 day", $i)) {
            if (date("w", $i) == 0) {
                $count++;
            }
        }
        $working_days = $days_in_month - $count; // this month current working day
        $working_hours = $working_days * 8; // this month current working hours
        $daily_pay = 30000 / $working_days; // this month payable amount by day
        $hourly_pay = (30000 / $working_hours); // this month payable amount by hours
        $start = new DateTime($work_in);
        $end = new DateTime($work_out);
        $interval = $start->diff($end);
        $working_hours = $interval->format('%H:%I'); // total working hours of the day
        list($hours, $minutes) = explode(":", $working_hours);
        $total_working_minutes = ($hours * 60) + $minutes - $break_hours;  // total working minutes of the day        
        $day_pay = ($hourly_pay / 60) * $total_working_minutes; // total payable amount of the day        
        return number_format($day_pay, 2, '.', '');
    }
}