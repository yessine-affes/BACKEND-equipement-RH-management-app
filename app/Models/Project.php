<?php

namespace App\Models;

use App\Helpers\XMLHelper;

class Project
{
    protected static $filePath = 'public/storage/app/projects.xml';

    /**
     * Get all projects from the XML file.
     *
     * @return array
     */
    public static function all()
    {
        return XMLHelper::readXmlFile(self::$filePath, 'project'); // Use 'project' as nodeName
    }

    /**
     * Find a project by ID.
     *
     * @param int|string $id
     * @return array|null
     */
    public static function find($id)
    {
        return collect(self::all())->firstWhere('id', (string)$id);
    }

    /**
     * Create a new project and save it to the XML file.
     *
     * @param array $data
     * @return array|null
     */
    public static function create(array $data)
    {
        // Ensure the directory exists
        $directory = dirname(self::$filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // Create directory with appropriate permissions
        }

        // Auto-generate ID based on existing records
        if (!isset($data['id'])) {
            $existingRecords = self::all();
            $data['id'] = count($existingRecords) + 1;  // Auto-increment ID
        }

        // Add the new project record to the XML file
        XMLHelper::addRecord(self::$filePath, $data, 'data', 'project');

        return self::find($data['id']);  // Return the newly created project
    }

    /**
     * Update a project by ID.
     *
     * @param int|string $id
     * @param array $data
     * @return bool
     */
    public static function update($id, array $data)
    {
        return XMLHelper::updateRecordById(self::$filePath, $id, $data, 'project');
    }

    /**
     * Delete a project by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public static function delete($id)
    {
        return XMLHelper::deleteRecordById(self::$filePath, $id, 'project');
    }
}