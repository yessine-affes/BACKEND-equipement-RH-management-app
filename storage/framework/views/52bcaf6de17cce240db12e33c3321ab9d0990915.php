<!DOCTYPE html>
<html>
<head>
    <title>Task Assigned</title>
</head>
<body>
    <h1>Hello <?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?></h1>
    <p>You have been assigned to the task: <strong><?php echo e($task->description); ?></strong>.</p>
    <p>Start Date: <?php echo e($task->start_date); ?></p>
    <p>End Date: <?php echo e($task->end_date); ?></p>
    <p>Please log in to the system to view more details.</p>
    <p>Thank you,</p>
    <p>Task Management System</p>
</body>
</html>
<?php /**PATH C:\Users\ERP-Project\Desktop\backend\resources\views/emails/task_assigned.blade.php ENDPATH**/ ?>