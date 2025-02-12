<!DOCTYPE html>
<html>
<head>
    <title>Task Assigned</title>
</head>
<body>
    <h1>Hello {{ $employee->first_name }} {{ $employee->last_name }}</h1>
    <p>You have been assigned to the task: <strong>{{ $task->description }}</strong>.</p>
    <p>Start Date: {{ $task->start_date }}</p>
    <p>End Date: {{ $task->end_date }}</p>
    <p>Please log in to the system to view more details.</p>
    <p>Thank you,</p>
    <p>Task Management System</p>
</body>
</html>
