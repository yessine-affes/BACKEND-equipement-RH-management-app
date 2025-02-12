<!DOCTYPE html>
<html>
<head>
    <title>Task Removed</title>
</head>
<body>
<h1>Hello {{ $employee['first_name'] }}</h1>
<p>You have been removed from the task: <strong>{{ $task['description'] }}</strong>.</p>
<p>Start Date: {{ $task['start_date'] }}</p>
<p>End Date: {{ $task['end_date'] }}</p>
<p>If you have any questions, please contact the project manager.</p>

</body>
</html>
