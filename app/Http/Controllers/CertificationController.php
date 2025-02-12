<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    /**
     * Display a listing of certifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get all certifications from the XML file
        $certifications = Certification::all();
        return response()->json($certifications); // Return all certifications as JSON
    }

    /**
     * Store a newly created certification in storage.
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
                'description' => 'nullable|string',
                'employee_id' => 'required|integer',
                'issued_date' => 'required|date',
                'expiry_date' => 'nullable|date',
                'status' => 'required|string|max:50',
            ]);

            // Create a new certification in the XML file
            $certification = Certification::create($validatedData);

            return response()->json($certification, 201); // Return the created certification with a 201 status code

        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Error creating certification: '.$e->getMessage());
            return response()->json(['message' => 'Error creating certification'], 500);
        }
    }

    /**
     * Display the specified certification.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find a certification by ID from the XML file
        $certification = Certification::find($id);

        if ($certification) {
            return response()->json($certification); // Return the specific certification as JSON
        }

        return response()->json(['error' => 'Certification not found'], 404);
    }

    /**
     * Update the specified certification in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find a certification by ID from the XML file
        $certification = Certification::find($id);

        if (!$certification) {
            return response()->json(['error' => 'Certification not found'], 404);
        }

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'employee_id' => 'required|integer',
                'issued_date' => 'required|date',
                'expiry_date' => 'nullable|date',
                'status' => 'required|string|max:50',
            ]);

            // Update the certification data in the XML file
            Certification::update($id, $validatedData);

            return response()->json(Certification::find($id)); // Return the updated certification as JSON

        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Error updating certification: '.$e->getMessage());
            return response()->json(['message' => 'Error updating certification'], 500);
        }
    }

    /**
     * Remove the specified certification from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find and delete a certification by ID from the XML file
        if (Certification::delete($id)) {
            return response()->json(['message' => 'Certification deleted successfully']);
        }

        return response()->json(['error' => 'Certification not found'], 404);
    }

    /**
     * Get certifications by employee ID.
     *
     * @param int $id Employee ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCertificationsByEmployee($id)
    {
        try {
            // Fetch certifications for a specific employee from the XML file
            $certifications = Certification::employeeCertifications($id);

            return response()->json($certifications);

        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Error fetching certifications by employee: '.$e->getMessage());
            return response()->json(['message' => 'Error fetching certifications'], 500);
        }
    }
}