<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource (Return JSON).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get all admins from the XML file
        $admins = Admin::all();
        return response()->json($admins);  // Return data as JSON
    }

    /**
     * Store a newly created resource in storage (Return JSON).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        \Log::info('Request received:', $request->all());
    
        // Validate the request data
        $validatedData = $request->validate([
            'username' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
    
        \Log::info('Validated data:', $validatedData);
    
        // Check for unique email constraint
        foreach (Admin::all() as $admin) {
            if ($admin['email'] === $validatedData['email']) {
                \Log::warning('Email already exists:', ['email' => $validatedData['email']]);
                return response()->json(['error' => 'Email already exists'], 400);
            }
        }
    
        // Hash password before saving
        $validatedData['password'] = bcrypt($request->password);
    
        // Create a new admin record
        $admin = Admin::create($validatedData);
    
        \Log::info('Admin created successfully:', ['admin' => $admin]);
    
        return response()->json($admin, 201);
    }

    /**
     * Display the specified resource (Return JSON).
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find an admin by ID from the XML file
        $admin = Admin::find($id);

        if ($admin) {
            return response()->json($admin);  // Return the specific admin as JSON
        }

        return response()->json(['error' => 'Admin not found'], 404);
    }

    /**
     * Update the specified resource in storage (Return JSON).
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find an admin by ID from the XML file
        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        // Update the admin data in the XML file
        if ($request->has('password')) {
            // Hash password if it's being updated
            $request->merge(['password' => Hash::make($request->password)]);
        }

        Admin::update($id, $request->all());

        return response()->json(Admin::find($id));  // Return the updated admin as JSON
    }

    /**
     * Remove the specified resource from storage (Return JSON).
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find an admin by ID from the XML file and delete it
        if (Admin::delete($id)) {
            return response()->json(['message' => 'Admin deleted successfully']);
        }

        return response()->json(['error' => 'Admin not found'], 404);
    }

    /**
     * Get details of the currently authenticated admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // Get the currently authenticated admin via Sanctum guard
        $admin = Auth::guard('sanctum')->user();

        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Return the admin details without sensitive information like password
        return response()->json([
            'id' => $admin->id,
            'username' => $admin->username,
            'email' => $admin->email,
            // Add any other fields you'd like to expose here...
        ], 200);
    }
}