<!DOCTYPE html>
<html>
<head>
    <title>Task Removed</title>
</head>
<body>
<h1>Hello <?php echo e($employee['first_name']); ?></h1>
<p>You have been removed from the task: <strong><?php echo e($task['description']); ?></strong>.</p>
<p>Start Date: <?php echo e($task['start_date']); ?></p>
<p>End Date: <?php echo e($task['end_date']); ?></p>
<p>If you have any questions, please contact the project manager.</p>

</body>
</html>
<?php /**PATH C:\Users\ERP-Project\Desktop\backend\resources\views/emails/task_removed.blade.php ENDPATH**/ ?>