<?php

namespace App\Models;

use App\Helpers\XMLHelper;

class TaskAssignment
{
    protected static $filePath = 'public/storage/app/task_assignments.xml';

    /**
     * Get all task assignments from the XML file.
     *
     * @return array
     */
    public static function all()
    {
        return XMLHelper::readXmlFile(self::$filePath, 'task_assignment');
    }

    /**
     * Find a task assignment by ID.
     *
     * @param int|string $id
     * @return array|null
     */
    public static function find($id)
    {
        return collect(self::all())->firstWhere('id', (string)$id);
    }

    /**
     * Create a new task assignment and save it to the XML file.
     *
     * @param array $data
     * @return array|null
     */
    public static function create(array $data)
    {
        // Ensure the directory exists
        $directory = dirname(self::$filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Auto-generate ID based on existing records
        if (!isset($data['id'])) {
            $existingRecords = self::all();
            $data['id'] = count($existingRecords) + 1;
        }

        // Fetch the `project_id` from the associated task
        $task = Task::find($data['task_id']);
        if (!$task) {
            throw new \Exception("Task not found");
        }
        $data['project_id'] = $task['project_id'];

        // Add the new task assignment record to the XML file
        XMLHelper::addRecord(self::$filePath, $data, 'data', 'task_assignment');

        return self::find($data['id']);
    }

    /**
     * Update a task assignment by ID.
     *
     * @param int|string $id
     * @param array $data
     * @return bool
     */
    public static function update($id, array $data)
    {
        return XMLHelper::updateRecordById(self::$filePath, $id, $data, 'task_assignment');
    }

    /**
     * Delete a task assignment by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public static function deleteRecordById($filePath, $id, $nodeName)
    {
        \Log::info("Attempting to delete record with ID: $id from file: $filePath");
    
        $xml = simplexml_load_file($filePath);
        if (!$xml) {
            \Log::error("Failed to load XML file: $filePath");
            return false;
        }
    
        $recordFound = false;
        foreach ($xml->$nodeName as $key => $node) {
            if ((string)$node->id == $id) {
                \Log::info("Record with ID: $id found. Deleting...");
                unset($xml->$nodeName[$key]);
                $recordFound = true;
                break;
            }
        }
    
        if (!$recordFound) {
            \Log::error("Record with ID: $id not found in the XML file.");
            return false;
        }
    
        $result = $xml->asXML($filePath);
        if (!$result) {
            \Log::error("Failed to save changes to XML file: $filePath");
            return false;
        }
    
        \Log::info("Record with ID: $id successfully deleted from XML file.");
        return true;
    }
    
    

    /**
     * Get all task assignments associated with a specific project ID.
     *
     * @param int|string $projectId
     * @return array
     */
    public static function assignmentsForProject($projectId)
    {
        $tasks = Task::projectTasks($projectId);
        $taskIds = array_column($tasks, 'id');

        return array_filter(self::all(), fn($assignment) => in_array((int)$assignment['task_id'], $taskIds));
    }
}
