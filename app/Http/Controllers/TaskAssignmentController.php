<?php

namespace App\Http\Controllers;

use App\Models\TaskAssignment;
use App\Models\Task;
use App\Models\Employee;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssigned;
use App\Mail\TaskRemoved;
class TaskAssignmentController extends Controller
{
    /**
     * Get all task assignments or filter by project ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
public function index(Request $request)
{
    try {
        // Fetch all task assignments
        $taskAssignments = TaskAssignment::all();

        // Check if 'id' is provided in the query parameters
        if ($request->has('id')) {
            $id = $request->query('id');

            // Filter task assignments by 'id'
            $filteredAssignment = array_filter(
                $taskAssignments,
                fn($assignment) => (int)$assignment['id'] === (int)$id
            );

            // If no task assignment matches the ID
            if (empty($filteredAssignment)) {
                return response()->json(['message' => 'Task assignment not found'], 404);
            }

            return response()->json(array_values($filteredAssignment), 200);
        }

        // Check if 'task_id' is provided in the query parameters
        if ($request->has('task_id')) {
            $taskId = $request->query('task_id');

            // Filter task assignments by 'task_id'
            $filteredAssignments = array_filter(
                $taskAssignments,
                fn($assignment) => (int)$assignment['task_id'] === (int)$taskId
            );

            // If no task assignments match the task_id
            if (empty($filteredAssignments)) {
                return response()->json(['message' => 'No task assignments found for the specified task ID'], 404);
            }

            return response()->json(array_values($filteredAssignments), 200);
        }

        // If no filters are provided, return all task assignments
        if (empty($taskAssignments)) {
            return response()->json(['message' => 'No task assignments available'], 404);
        }

        return response()->json($taskAssignments, 200);
    } catch (\Exception $e) {
        \Log::error('Error fetching task assignments: ' . $e->getMessage());
        return response()->json(['message' => 'Error fetching task assignments'], 500);
    }
}

    
    
    

    /**
     * Display the specified task assignment by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $taskAssignment = TaskAssignment::find($id);

            if ($taskAssignment) {
                return response()->json($taskAssignment);
            }

            return response()->json(['error' => 'Task assignment not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error fetching task assignment: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching task assignment'], 500);
        }
    }

    /**
     * Store a new task assignment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
{
    try {
        // Validate the request data
        $validatedData = $request->validate([
            'task_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'equipment_id' => 'nullable|integer',
            'assigned_date' => 'required|date',
            'completion_date' => 'nullable|date|after_or_equal:assigned_date',
            'status' => 'required|string|max:50',
        ]);

        // Find the task assignment to update
        $taskAssignment = TaskAssignment::find($id);
        if (!$taskAssignment) {
            return response()->json(['error' => 'Task assignment not found'], 404);
        }

        // Find the related task
        $task = Task::find($validatedData['task_id']);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        // Find the related employee
        $employee = Employee::find($validatedData['employee_id']);
        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        // Update the task assignment data
        $validatedData['project_id'] = $task['project_id']; // Attach the project ID from the task
        $taskAssignment = TaskAssignment::update($id, $validatedData);

        return response()->json($taskAssignment, 200); // Return updated assignment
    } catch (\Exception $e) {
        \Log::error('Error updating task assignment: ' . $e->getMessage());
        return response()->json(['message' => 'Error updating task assignment'], 500);
    }
}

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'task_id' => 'required|integer',
                'employee_id' => 'required|integer',
                'equipment_id' => 'nullable|integer',
                'assigned_date' => 'required|date',
                'completion_date' => 'nullable|date|after_or_equal:assigned_date',
                'status' => 'required|string|max:50',
            ]);

            $task = Task::find($validatedData['task_id']);
            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }

            $employee = Employee::find($validatedData['employee_id']);
            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            $validatedData['project_id'] = $task['project_id'];

            $taskAssignment = TaskAssignment::create($validatedData);

            // Send Task Assigned Email
            Mail::to($employee['email'])->send(new TaskAssigned($task, $employee));

            return response()->json($taskAssignment, 201);
        } catch (\Exception $e) {
            \Log::error('Error creating task assignment: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating task assignment'], 500);
        }
    }

public function destroy($id)
{
    try {
        // Locate the task assignment
        $taskAssignment = TaskAssignment::find($id);

        if (!$taskAssignment) {
            return response()->json(['error' => 'Task assignment not found'], 404);
        }

        // Use deleteRecordById to delete the record
        $filePath = 'public/storage/app/task_assignments.xml'; // Adjust path as needed
        $nodeName = 'task_assignment';

        if (TaskAssignment::deleteRecordById($filePath, $id, $nodeName)) {
            \Log::info("Task assignment with ID $id successfully deleted.");
            return response()->json(['message' => 'Task assignment deleted successfully'], 200);
        }

        return response()->json(['error' => 'Failed to delete task assignment'], 500);
    } catch (\Exception $e) {
        \Log::error('Error deleting task assignment: ' . $e->getMessage());
        return response()->json(['message' => 'Error deleting task assignment'], 500);
    }
}
    
    
    
    
    
}
