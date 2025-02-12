<?php

namespace App\Models;

use App\Helpers\XMLHelper;

class Token
{
    protected static $filePath = 'public/storage/app/tokens.xml';

    public $id;
    public $admin_id;
    public $token;
    public $created_at;

    /**
     * Get all tokens from the XML file.
     *
     * @return array
     */
    public static function all()
    {
        return XMLHelper::readXmlFile(self::$filePath, 'token'); // Use 'token' as nodeName
    }

    /**
     * Find a token by its value.
     *
     * @param string $token
     * @return self|null
     */
    public static function findByToken($token)
    {
        $tokenData = collect(self::all())->firstWhere('token', $token);

        if (!$tokenData) {
            return null; // Return null if token not found
        }

        // Create and populate a Token instance
        $tokenInstance = new self();
        foreach ($tokenData as $key => $value) {
            $tokenInstance->$key = $value;
        }

        return $tokenInstance;
    }

    /**
     * Create a new token and save it to the XML file.
     *
     * @param array $data
     * @return self|null
     */
    public static function create(array $data)
    {
        $directory = dirname(self::$filePath);

        // Ensure the directory exists
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // Create directory with appropriate permissions
        }

        // Auto-generate ID based on existing records
        if (!isset($data['id'])) {
            $existingRecords = self::all();
            $data['id'] = count($existingRecords) + 1;  // Auto-increment ID
        }

        // Add the new token record to the XML file
        XMLHelper::addRecord(self::$filePath, $data, 'data', 'token');

        return self::findByToken($data['token']);  // Return the newly created token as an instance
    }

    /**
     * Delete a token by its value.
     *
     * @param string $token
     * @return bool
     */
    public static function deleteByToken($token)
    {
        return XMLHelper::deleteRecordById(self::$filePath, $token, 'token');
    }
}