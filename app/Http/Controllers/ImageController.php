<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Shared method to get the full path for a file.
     *
     * @param string $filename
     * @return string
     */
    private function getFilePath($filename)
    {
        return storage_path('app/public/images/' . $filename); // Full path to the images directory
    }

    /**
     * Upload an image.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('photo');

        // Preserve the original name of the file
        $originalName = $file->getClientOriginalName();

        // Store the file with its original name
        $path = $file->storeAs('images', $originalName, 'public');

        return response()->json([
            'message' => 'File uploaded successfully',
            'path' => Storage::url($path) // Accessible URL for the stored file
        ], 200);
    }

    /**
     * Get an image by filename.
     *
     * @param string $filename
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function getImage($filename)
    {
        // Get the exact path to the file
        $exactPath = $this->getFilePath($filename);

        // Check if the file exists
        if (file_exists($exactPath)) {
            // Return the file as a response
            return response()->file($exactPath);
        }

        return response()->json(['message' => 'File not found'], 404);
    }

    /**
     * Delete an image by filename.
     *
     * @param string $filename
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage($filename)
    {
        // Get the exact path to the file
        $exactPath = $this->getFilePath($filename);

        // Check if the file exists
        if (file_exists($exactPath)) {
            // Delete the file
            unlink($exactPath);

            return response()->json(['message' => 'File deleted successfully'], 200);
        }

        return response()->json(['message' => 'File not found'], 404);
    }
}
