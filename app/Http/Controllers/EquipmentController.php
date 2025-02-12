<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Get all equipment.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get all equipment from the XML file
        $equipment = Equipment::all();
        return response()->json($equipment);  // Return all equipment as JSON
    }

    /**
     * Store a new piece of equipment.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'required|string|max:50',
                'type' => 'required|string|max:100',
                'availability' => 'required|boolean',
                'photo' => 'nullable|string',
                'maintenanceSchedule' => 'nullable|string',
            ]);

            // Create a new piece of equipment in the XML file
            $equipment = Equipment::create($validatedData);

            return response()->json($equipment, 201);  // Return the created equipment with a 201 status code

        } catch (\Exception $e) {
            \Log::error('Error creating equipment: '.$e->getMessage());
            return response()->json(['message' => 'Error creating equipment'], 500);
        }
    }

    /**
     * Display the specified piece of equipment.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find a piece of equipment by ID from the XML file
        $equipment = Equipment::find($id);

        if ($equipment) {
            return response()->json($equipment);  // Return the specific equipment as JSON
        }

        return response()->json(['error' => 'Equipment not found'], 404);
    }

    /**
     * Update a piece of equipment.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find a piece of equipment by ID from the XML file
        if (!Equipment::find($id)) {
            return response()->json(['error' => 'Equipment not found'], 404);
        }

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'required|string|max:50',
                'type' => 'required|string|max:100',
                'availability' => 'required|boolean',
                'photo' => 'nullable|string',
                'maintenanceSchedule' => 'nullable|string',
            ]);

            // Update the equipment in the XML file with validated data
            Equipment::update($id, $validatedData);

            return response()->json(Equipment::find($id));  // Return the updated equipment as JSON

        } catch (\Exception $e) {
            \Log::error('Error updating equipment: '.$e->getMessage());
            return response()->json(['message' => 'Error updating equipment'], 500);
        }
    }

    /**
     * Remove a piece of equipment from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find and delete a piece of equipment by ID from the XML file
        if (Equipment::delete($id)) {
            return response()->json(['message' => 'Equipment deleted successfully']);
        }

        return response()->json(['error' => 'Equipment not found'], 404);
    }
}