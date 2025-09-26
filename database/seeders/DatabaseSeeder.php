<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin@worktracker.com',
            'password' => Hash::make('password'),
        ]);

        // Create sample employees
        $employees = [
            [
                'employee_id' => 'EMP001',
                'name' => 'John Doe',
                'email' => 'john@company.com',
                'department' => 'IT',
                'password' => Hash::make('password123'),
            ],
            [
                'employee_id' => 'EMP002', 
                'name' => 'Jane Smith',
                'email' => 'jane@company.com',
                'department' => 'HR',
                'password' => Hash::make('password123'),
            ],
            [
                'employee_id' => 'EMP003',
                'name' => 'Mike Johnson',
                'email' => 'mike@company.com', 
                'department' => 'Finance',
                'password' => Hash::make('password123'),
            ],
            [
                'employee_id' => 'EMP004',
                'name' => 'Sarah Wilson',
                'email' => 'sarah@company.com',
                'department' => 'Marketing',
                'password' => Hash::make('password123'),
            ],
            [
                'employee_id' => 'EMP005',
                'name' => 'David Brown',
                'email' => 'david@company.com',
                'department' => 'Operations',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($employees as $employeeData) {
            $employee = Employee::create($employeeData);
            
            // Create sample attendance records for the past 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                
                // Skip weekends for some variety
                if ($date->isWeekend() && rand(0, 1)) {
                    continue;
                }
                
                $checkIn = $date->copy()->setHour(rand(8, 9))->setMinute(rand(0, 59));
                $checkOut = $checkIn->copy()->addHours(rand(7, 9))->addMinutes(rand(0, 59));
                
                $attendance = Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                ]);
                
                $attendance->calculateHours();
            }
        }
    }
}