<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Get all employees.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $employees = Employee::all();
            return response()->json($employees);
        } catch (\Exception $e) {
            \Log::error('Error fetching employees: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching employees. Please check the logs for more details.'], 500);
        }
    }

    /**
     * Store a new employee.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        $existingEmployees = Employee::all();
                        foreach ($existingEmployees as $employee) {
                            if ($employee['email'] === $value) {
                                $fail('The email address is already in use.');
                            }
                        }
                    },
                ],
                'admin_id' => 'required|integer',
                'availability' => 'required|boolean',
                'photo' => 'nullable|string',
                'speciality' => [
                    'required',
                    Rule::in(Employee::$specialities),
                ],
                'score' => 'nullable|integer|min:0|max:100',
            ]);

            // Create new employee
            $employee = Employee::create($validatedData);

            return response()->json($employee, 201);
        } catch (\Exception $e) {
            \Log::error('Error creating employee: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating employee. Please check the logs for details.'], 500);
        }
    }

    /**
     * Get a specific employee by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $employee = Employee::find($id);
            if ($employee) {
                return response()->json($employee);
            }
            return response()->json(['error' => 'Employee not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error fetching employee: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching employee details.'], 500);
        }
    }

    /**
     * Update an existing employee.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::find($id);
            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            // Validate input
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) use ($employee) {
                        $existingEmployees = Employee::all();
                        foreach ($existingEmployees as $e) {
                            if ($e['email'] === $value && $e['id'] !== $employee['id']) {
                                $fail('The email address is already in use by another employee.');
                            }
                        }
                    },
                ],
                'admin_id' => 'required|integer',
                'availability' => 'required|boolean',
                'photo' => 'nullable|string',
                'speciality' => [
                    'required',
                    Rule::in(Employee::$specialities),
                ],
                'score' => 'nullable|integer|min:0|max:100',
            ]);

            // Update employee
            $updated = Employee::update($id, $validatedData);
            if ($updated) {
                return response()->json(Employee::find($id));
            }
            return response()->json(['message' => 'Failed to update employee'], 500);
        } catch (\Exception $e) {
            \Log::error('Error updating employee: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating employee.'], 500);
        }
    }

    /**
     * Delete an employee.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $deleted = Employee::delete($id);
            if ($deleted) {
                return response()->json(['message' => 'Employee deleted successfully']);
            }
            return response()->json(['error' => 'Employee not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting employee: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting employee.'], 500);
        }
    }
}
