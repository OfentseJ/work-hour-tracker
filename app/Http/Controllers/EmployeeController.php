<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $employee = Auth::guard('employee')->user();
        
        // Get today's attendance
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        // Get this week's stats
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklyStats = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->whereNotNull('hours_worked')
            ->get();

        $totalWeeklyHours = $weeklyStats->sum('hours_worked');
        $daysWorkedThisWeek = $weeklyStats->count();

        // Get recent attendance (last 10 days)
        $recentAttendance = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return view('employee.dashboard', compact(
            'employee',
            'todayAttendance', 
            'totalWeeklyHours',
            'daysWorkedThisWeek',
            'recentAttendance'
        ));
    }

    public function checkIn(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $today = today();

        // Check if already checked in today
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($attendance && $attendance->check_in) {
            return back()->with('error', 'You have already checked in today!');
        }

        // Create or update attendance record
        $attendance = Attendance::firstOrCreate([
            'employee_id' => $employee->id,
            'date' => $today
        ]);

        $attendance->update(['check_in' => now()]);

        return back()->with('success', 'Successfully checked in at ' . now()->format('H:i A'));
    }

    public function checkOut(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return back()->with('error', 'You must check in first!');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'You have already checked out today!');
        }

        $attendance->update(['check_out' => now()]);
        $attendance->calculateHours();

        return back()->with('success', 'Successfully checked out at ' . now()->format('H:i A') . '. Total hours: ' . number_format($attendance->hours_worked, 2));
    }

    public function attendance()
    {
        $employee = Auth::guard('employee')->user();
        
        $attendances = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('employee.attendance', compact('attendances', 'employee'));
    }

    public function profile()
    {
        $employee = Auth::guard('employee')->user();
        return view('employee.profile', compact('employee'));
    }
}