<?php

namespace App\Models;

use App\Helpers\XMLHelper;

class Employee
{
    protected static $filePath = 'public/storage/app/employees.xml';

    const SPECIALITY_MINING = 'Mining';
    const SPECIALITY_PROCESSING = 'Processing';
    const SPECIALITY_TRANSPORT = 'Transport';
    const SPECIALITY_QUALITY_CONTROL = 'QualityControl';
    const SPECIALITY_MAINTENANCE = 'Maintenance';

    public static $specialities = [
        self::SPECIALITY_MINING,
        self::SPECIALITY_PROCESSING,
        self::SPECIALITY_TRANSPORT,
        self::SPECIALITY_QUALITY_CONTROL,
        self::SPECIALITY_MAINTENANCE,
    ];

    /**
     * Get all employees from the XML file.
     *
     * @return array
     */
    public static function all()
    {
        $employees = XMLHelper::readXmlFile(self::$filePath, 'employee');

        // Ensure 'photo' is always a string in the returned employees
        foreach ($employees as &$employee) {
            if (isset($employee['photo']) && is_array($employee['photo'])) {
                $employee['photo'] = implode(',', $employee['photo']);
            }
        }

        return $employees;
    }

    /**
     * Find an employee by ID.
     *
     * @param int|string $id
     * @return array|null
     */
    public static function find($id)
    {
        $employee = collect(self::all())->firstWhere('id', (string) $id);

        // Ensure 'photo' is always a string
        if ($employee && isset($employee['photo']) && is_array($employee['photo'])) {
            $employee['photo'] = implode(',', $employee['photo']);
        }

        return $employee;
    }

    /**
     * Create a new employee and save it to the XML file.
     *
     * @param array $data
     * @return array|null
     */
    public static function create(array $data)
    {
        $data['id'] = self::generateId(); // Ensure unique ID generation

        // Ensure 'photo' is a string before saving
        if (isset($data['photo']) && is_array($data['photo'])) {
            $data['photo'] = implode(',', $data['photo']);
        }

        XMLHelper::addRecord(self::$filePath, $data, 'data', 'employee');
        return self::find($data['id']);
    }

    /**
     * Update an employee by ID.
     *
     * @param int|string $id
     * @param array $data
     * @return bool
     */
    public static function update($id, array $data)
    {
        // Ensure 'photo' is a string before updating
        if (isset($data['photo']) && is_array($data['photo'])) {
            $data['photo'] = implode(',', $data['photo']);
        }

        return XMLHelper::updateRecordById(self::$filePath, $id, $data, 'employee');
    }

    /**
     * Delete an employee by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public static function delete($id)
    {
        return XMLHelper::deleteRecordById(self::$filePath, $id, 'employee');
    }

    /**
     * Generate a unique ID for a new employee.
     *
     * @return int
     */
    private static function generateId()
    {
        $existingEmployees = self::all();
        $ids = array_column($existingEmployees, 'id');
        return $ids ? max($ids) + 1 : 1;
    }
}
