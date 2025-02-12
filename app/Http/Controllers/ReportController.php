<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Employee;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Get all reports.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get all reports from the XML file
        $reports = Report::all();
        return response()->json($reports);  // Return all reports as JSON
    }

    /**
     * Store a new report.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'employee_id' => 'required|integer',
                'photo' => 'nullable|string',
            ]);

            // Check if employee_id exists
            if (!Employee::find($validatedData['employee_id'])) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            // Create a new report in the XML file
            $report = Report::create($validatedData);

            return response()->json($report, 201);  // Return the created report with a 201 status code

        } catch (\Exception $e) {
            \Log::error('Error creating report: '.$e->getMessage());
            return response()->json(['message' => 'Error creating report'], 500);
        }
    }

    /**
     * Display the specified report.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find a report by ID from the XML file
        $report = Report::find($id);

        if ($report) {
            return response()->json($report);  // Return the specific report as JSON
        }

        return response()->json(['error' => 'Report not found'], 404);
    }

    /**
     * Update the specified report.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find a report by ID from the XML file
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'employee_id' => 'required|integer',
                'photo' => 'nullable|string',
            ]);

            // Check if employee_id exists
            if (!Employee::find($validatedData['employee_id'])) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            // Update the report in the XML file with validated data
            Report::update($id, $validatedData);

            return response()->json(Report::find($id));  // Return the updated report as JSON

        } catch (\Exception $e) {
            \Log::error('Error updating report: '.$e->getMessage());
            return response()->json(['message' => 'Error updating report'], 500);
        }
    }

    /**
     * Remove the specified report from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find and delete a report by ID from the XML file
        if (Report::delete($id)) {
            return response()->json(['message' => 'Report deleted successfully']);
        }

        return response()->json(['error' => 'Report not found'], 404);
    }
}