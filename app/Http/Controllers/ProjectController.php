<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Get all projects.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get all projects from the XML file
        $projects = Project::all();
        return response()->json($projects);  // Return all projects as JSON
    }

    /**
     * Store a new project.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'required|string|max:50',
            ]);

            // Create a new project in the XML file
            $project = Project::create($validatedData);

            return response()->json($project, 201);  // Return the created project with a 201 status code

        } catch (\Exception $e) {
            \Log::error('Error creating project: '.$e->getMessage());
            return response()->json(['message' => 'Error creating project'], 500);
        }
    }

    /**
     * Display the specified project.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find a project by ID from the XML file
        $project = Project::find($id);

        if ($project) {
            return response()->json($project);  // Return the specific project as JSON
        }

        return response()->json(['error' => 'Project not found'], 404);
    }

    /**
     * Update the specified project.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find a project by ID from the XML file
        if (!Project::find($id)) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'required|string|max:50',
            ]);

            // Update the project in the XML file with validated data
            Project::update($id, $validatedData);

            return response()->json(Project::find($id));  // Return the updated project as JSON

        } catch (\Exception $e) {
            \Log::error('Error updating project: '.$e->getMessage());
            return response()->json(['message' => 'Error updating project'], 500);
        }
    }

    /**
     * Remove the specified project from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find and delete a project by ID from the XML file
        if (Project::delete($id)) {
            return response()->json(['message' => 'Project deleted successfully']);
        }

        return response()->json(['error' => 'Project not found'], 404);
    }
}