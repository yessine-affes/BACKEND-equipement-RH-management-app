<?php

namespace App\Models;

use App\Helpers\XMLHelper;

class Certification
{
    protected static $filePath = 'public/storage/app/certifications.xml';

    /**
     * Get all certifications from the XML file.
     *
     * @return array
     */
    public static function all()
    {
        return XMLHelper::readXmlFile(self::$filePath, 'certification'); // Use 'certification' as nodeName
    }

    /**
     * Find a certification by ID.
     *
     * @param int|string $id
     * @return array|null
     */
    public static function find($id)
    {
        return collect(self::all())->firstWhere('id', (string)$id);
    }

    /**
     * Create a new certification and save it to the XML file.
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

        // Add the new certification record to the XML file
        XMLHelper::addRecord(self::$filePath, $data, 'data', 'certification');

        return self::find($data['id']);  // Return the newly created certification
    }

    /**
     * Update a certification by ID.
     *
     * @param int|string $id
     * @param array $data
     * @return bool
     */
    public static function update($id, array $data)
    {
        return XMLHelper::updateRecordById(self::$filePath, $id, $data, 'certification');
    }

    /**
     * Delete a certification by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public static function delete($id)
    {
        return XMLHelper::deleteRecordById(self::$filePath, $id, 'certification');
    }

    /**
     * Get all certifications associated with a specific employee.
     *
     * @param int|string $employeeId
     * @return array
     */
    public static function employeeCertifications($employeeId)
    {
        return array_filter(self::all(), fn($certification) => isset($certification['employee_id']) && intval($certification['employee_id']) === intval($employeeId));
    }
}