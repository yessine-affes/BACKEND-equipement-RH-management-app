<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Get all tasks.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // Check if the request has a 'project_id' query parameter
            if ($request->has('project_id')) {
                $projectId = $request->query('project_id'); // Get 'project_id' value
                
                // Use the custom projectTasks method to filter tasks by 'project_id'
                $tasks = Task::projectTasks($projectId);
    
                // If no tasks are found for the specified 'project_id'
                if (empty($tasks)) {
                    return response()->json(['message' => 'No tasks found for the specified project'], 404);
                }
    
                return response()->json($tasks, 200); // Return filtered tasks
            }
    
            // If no 'project_id' is provided, fetch all tasks
            $tasks = Task::all();
    
            // Check if there are no tasks at all
            if (empty($tasks)) {
                return response()->json(['message' => 'No tasks available'], 404);
            }
    
            return response()->json($tasks, 200); // Return all tasks
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error fetching tasks: ' . $e->getMessage());
    
            // Return a 500 error response
            return response()->json(['message' => 'Error fetching tasks'], 500);
        }
    }
    
    
    
    
    

    /**
     * Store a new task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'description' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'due_date' => 'nullable|date|after_or_equal:start_date',
                'project_id' => 'required|integer',
                'status' => 'required|string|max:50',
            ]);

            // Create a new task in the XML file
            $task = Task::create($validatedData);

            return response()->json($task, 201);  // Return the created task with a 201 status code

        } catch (\Exception $e) {
            \Log::error('Error creating task: '.$e->getMessage());
            return response()->json(['message' => 'Error creating task'], 500);
        }
    }

    /**
     * Display the specified task.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find a task by ID from the XML file
        $task = Task::find($id);

        if ($task) {
            return response()->json($task);  // Return the specific task as JSON
        }

        return response()->json(['error' => 'Task not found'], 404);
    }

    /**
     * Update the specified task.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find a task by ID from the XML file
        if (!Task::find($id)) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'description' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'due_date' => 'nullable|date|after_or_equal:start_date',
                'project_id' => 'required|integer',
                'status' => 'required|string|max:50',
            ]);

            // Update the task in the XML file with validated data
            Task::update($id, $validatedData);

            return response()->json(Task::find($id));  // Return the updated task as JSON

        } catch (\Exception $e) {
            \Log::error('Error updating task: '.$e->getMessage());
            return response()->json(['message' => 'Error updating task'], 500);
        }
    }

    /**
     * Remove the specified task from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTasksByProject(Request $request)
    {
        // Get the project_id from the query parameters
        $projectId = $request->query('project_id');
    
        if (!$projectId) {
            return response()->json(['error' => 'project_id is required'], 400);
        }
    
        // Find tasks by project_id
        $tasks = Task::projectTasks($projectId);
    
        if (empty($tasks)) {
            return response()->json(['error' => 'No tasks found for the specified project_id'], 404);
        }
    
        // Delete tasks one by one
        foreach ($tasks as $task) {
            Task::delete($task['id']);
        }
    
        return response()->json(['message' => 'Tasks deleted successfully for project_id: ' . $projectId], 200);
    }
    
    /**
     * Remove the specified task by ID from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Find the task by ID
            $task = Task::find($id);

            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }

            // Delete the task
            Task::delete($id);

            return response()->json(['message' => 'Task deleted successfully'], 200);

        } catch (\Exception $e) {
            \Log::error('Error deleting task: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting task'], 500);
        }
    }
    
}