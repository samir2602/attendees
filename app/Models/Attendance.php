<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = [];

    public function workinghours()
    {        
        $minutes = $this->working_hours;
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        $time = sprintf('%02d:%02d', $hours, $remainingMinutes);
        return $time; // 02:15
    }

    public function breakhours()
    {        
        $minutes = $this->break_hours;
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        $time = sprintf('%02d:%02d', $hours, $remainingMinutes);
        return $time; // 02:15
    }
}
