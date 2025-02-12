<?php

namespace App\Models;

use App\Helpers\XMLHelper;

class Equipment
{
    protected static $filePath = 'public/storage/app/equipments.xml';

    /**
     * Get all equipment from the XML file.
     *
     * @return array
     */
    public static function all()
    {
        return XMLHelper::readXmlFile(self::$filePath, 'equipment'); // Use 'equipment' as nodeName
    }

    /**
     * Find a piece of equipment by ID.
     *
     * @param int|string $id
     * @return array|null
     */
    public static function find($id)
    {
        return collect(self::all())->firstWhere('id', (string)$id);
    }

    /**
     * Create a new piece of equipment and save it to the XML file.
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

        // Add the new equipment record to the XML file
        XMLHelper::addRecord(self::$filePath, $data, 'data', 'equipment');

        return self::find($data['id']);  // Return the newly created equipment
    }

    /**
     * Update a piece of equipment by ID.
     *
     * @param int|string $id
     * @param array $data
     * @return bool
     */
    public static function update($id, array $data)
    {
        return XMLHelper::updateRecordById(self::$filePath, $id, $data, 'equipment');
    }

    /**
     * Delete a piece of equipment by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public static function delete($id)
    {
        return XMLHelper::deleteRecordById(self::$filePath, $id, 'equipment');
    }
}