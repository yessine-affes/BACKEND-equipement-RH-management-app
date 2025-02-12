<?php

namespace App\Models;

use App\Helpers\XMLHelper;
use App\Models\Employee;

class Report
{
    protected static $filePath = 'public/storage/app/reports.xml';

    /**
     * Get all reports from the XML file.
     *
     * @return array
     */
    public static function all()
    {
        return XMLHelper::readXmlFile(self::$filePath, 'report'); // Use 'report' as nodeName
    }

    /**
     * Find a report by ID.
     *
     * @param int|string $id
     * @return array|null
     */
    public static function find($id)
    {
        return collect(self::all())->firstWhere('id', (string)$id);
    }

    /**
     * Create a new report and save it to the XML file.
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

        // Validate employee_id existence
        if (!Employee::find($data['employee_id'])) {
            throw new \Exception('Employee not found');
        }

        // Auto-generate ID based on existing records
        if (!isset($data['id'])) {
            $existingRecords = self::all();
            $data['id'] = count($existingRecords) + 1;  // Auto-increment ID
        }

        // Add the new report record to the XML file
        XMLHelper::addRecord(self::$filePath, $data, 'data', 'report');

        return self::find($data['id']);  // Return the newly created report
    }

    /**
     * Update a report by ID.
     *
     * @param int|string $id
     * @param array $data
     * @return bool
     */
    public static function update($id, array $data)
    {
        return XMLHelper::updateRecordById(self::$filePath, $id, $data, 'report');
    }

    /**
     * Delete a report by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public static function delete($id)
    {
        return XMLHelper::deleteRecordById(self::$filePath, $id, 'report');
    }

    /**
     * Get all reports associated with a specific employee.
     *
     * @param int|string $employeeId
     * @return array
     */
    public static function employeeReports($employeeId)
    {
        return array_filter(self::all(), fn($report) => isset($report['employee_id']) && intval($report['employee_id']) === intval($employeeId));
    }
}