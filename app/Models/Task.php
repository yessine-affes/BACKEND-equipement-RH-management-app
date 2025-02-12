<?php

namespace App\Models;

use App\Helpers\XMLHelper;

class Task
{
    protected static $filePath = 'public/storage/app/tasks.xml';

    /**
     * Get all tasks from the XML file.
     *
     * @return array
     */
    public static function all()
    {
        return XMLHelper::readXmlFile(self::$filePath, 'task'); // Use 'task' as nodeName
    }

    /**
     * Find a task by ID.
     *
     * @param int|string $id
     * @return array|null
     */
    public static function find($id)
    {
        return collect(self::all())->firstWhere('id', (string)$id);
    }

    /**
     * Create a new task and save it to the XML file.
     *
     * @param array $data
     * @return array|null
     */
    public static function create(array $data)
    {
        // Ensure the directory exists
        $directory = dirname(self::$filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // Create directory with appropriate permissions
        }

        // Auto-generate ID based on existing records
        if (!isset($data['id'])) {
            $existingRecords = self::all();
            $data['id'] = count($existingRecords) + 1;  // Auto-increment ID
        }

        // Add the new task record to the XML file
        XMLHelper::addRecord(self::$filePath, $data, 'data', 'task');

        return self::find($data['id']);  // Return the newly created task
    }

    /**
     * Update a task by ID.
     *
     * @param int|string $id
     * @param array $data
     * @return bool
     */
    public static function update($id, array $data)
    {
        return XMLHelper::updateRecordById(self::$filePath, $id, $data, 'task');
    }

    /**
     * Delete a task by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public static function delete($id)
    {
        return XMLHelper::deleteRecordById(self::$filePath, $id, 'task');
    }

    /**
     * Get all tasks associated with a specific project.
     *
     * @param int|string $projectId
     * @return array
     */
    public static function projectTasks($projectId)
    {
        return array_filter(self::all(), fn($task) => isset($task['project_id']) && intval($task['project_id']) === intval($projectId));
    }

    /**
     * Get all assignments associated with a specific task.
     *
     * @param int|string $taskId
     * @return array
     */
    public static function taskAssignments($taskId)
    {
        return array_filter(TaskAssignment::all(), fn($assignment) => isset($assignment['task_id']) && intval($assignment['task_id']) === intval($taskId));
    }
}