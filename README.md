# Laravel Admin Work Hours Tracker

A comprehensive work hours tracking system built with Laravel for managing employee attendance and generating reports.

## Installation Steps

1. **Clone and Setup**

    ```bash
    composer create-project laravel/laravel work-hours-tracker
    cd work-hours-tracker
    ```

2. **Database Setup**

    - Create a MySQL database named `Employees`
    - Update `.env` file with your database credentials:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=Employees
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

3. **Install Dependencies & Migrate**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

4. **Start Development Server**

    ```bash
    php artisan serve
    ```

5. **Access the Application**
    - Visit: http://localhost:8000
    - Login with: admin@worktracker.com / password

## Features Implemented

### ✅ Admin Features

1. **Employee Account Management** - Register and manage employee accounts
2. **Attendance Recording** - Real-time check-in/check-out tracking
3. **Work History Monitoring** - Detailed attendance logs with automatic hour calculations
4. **Weekly Summary Dashboard** - Interactive reports with Chart.js visualizations

### ✅ Technical Requirements

-   **Laravel Framework** - MVC architecture with controllers, models, routes, views
-   **MySQL Database** - Proper relational database design
-   **Bootstrap CSS** - Responsive, mobile-friendly design
-   **JavaScript Interactivity** - Form validation, alerts, and dynamic charts
-   **Authentication** - Secure admin login system

## Database Schema

-   `admins` - System administrators
-   `employees` - Employee records with authentication
-   `attendances` - Daily check-in/check-out records with calculated hours

## Usage

### Admin Dashboard

-   View real-time statistics and recent activity
-   Quick check-in/check-out for employees
-   Access to all management features

### Employee Management

-   Add new employees with secure authentication
-   Manage employee departments and status
-   View employee attendance history

### Attendance Tracking

-   Real-time attendance monitoring
-   Automatic hour calculations
-   Comprehensive attendance history

### Weekly Reports

-   Visual charts showing weekly performance
-   Top performer rankings
-   Exportable summary reports

## Security Features

-   Password hashing with bcrypt
-   CSRF protection on all forms
-   Input validation and sanitization
-   Secure authentication guards

This is a functional prototype suitable for small to medium businesses needing basic workforce management capabilities.

```

This complete Laravel implementation includes all required features:
- Employee account management with secure authentication
- Real-time attendance recording (check-in/check-out)
- Comprehensive work history monitoring with automatic hour calculations
- Interactive weekly dashboard with Chart.js visualizations
- Responsive Bootstrap design with modern UI/UX
- Form validation and JavaScript interactivity
- MySQL database with proper relationships and sample data

The system is ready to deploy and use for workforce management!
```
