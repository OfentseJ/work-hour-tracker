<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $todayAttendance = Attendance::whereDate('date', today())->count();
        
        $recentAttendances = Attendance::with('employee')
            ->whereDate('date', today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('totalEmployees', 'activeEmployees', 'todayAttendance', 'recentAttendances'));
    }

    public function employees()
    {
        $employees = Employee::withCount('attendances')->paginate(10);
        return view('admin.employees', compact('employees'));
    }

    public function createEmployee()
    {
        return view('admin.create-employee');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'employee_id' => 'required|string|max:255|unique:employees',
            'department' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.employees')->with('success', 'Employee created successfully!');
    }

    public function attendance()
    {
        $attendances = Attendance::with('employee')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.attendance', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id'
        ]);

        $today = today();
        $attendance = Attendance::firstOrCreate([
            'employee_id' => $request->employee_id,
            'date' => $today
        ]);

        if ($attendance->check_in) {
            return back()->with('error', 'Employee already checked in today!');
        }

        $attendance->update(['check_in' => now()]);
        
        return back()->with('success', 'Employee checked in successfully!');
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id'
        ]);

        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return back()->with('error', 'Employee must check in first!');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Employee already checked out today!');
        }

        $attendance->update(['check_out' => now()]);
        $attendance->calculateHours();

        return back()->with('success', 'Employee checked out successfully!');
    }

    public function weeklyReport()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyData = Attendance::with('employee')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->whereNotNull('hours_worked')
            ->get()
            ->groupBy('employee.name')
            ->map(function ($attendances) {
                return [
                    'total_hours' => $attendances->sum('hours_worked'),
                    'days_worked' => $attendances->count(),
                    'avg_hours' => $attendances->avg('hours_worked')
                ];
            });

        $chartData = [
            'labels' => $weeklyData->keys()->toArray(),
            'hours' => $weeklyData->pluck('total_hours')->toArray(),
        ];

        return view('admin.weekly-report', compact('weeklyData', 'chartData', 'startOfWeek', 'endOfWeek'));
    }
}