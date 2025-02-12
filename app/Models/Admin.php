<?php

namespace App\Models;

use App\Helpers\XMLHelper;
use Illuminate\Support\Str; // Correctly import Str class
use Laravel\Sanctum\HasApiTokens;

class Admin
{
    use HasApiTokens; // Required for Sanctum token generation

    protected static $filePath = 'storage/app/admins.xml';

    public $id;
    public $username;
    public $email;
    public $password;
    public $api_token;

    /**
     * Generate an API token for the admin.
     *
     * @return string
     */
    public function generateToken()
    {
        $this->api_token = Str::random(60); // Use Str::random() to generate a random token

        // Save token in XML file (update admin record)
        self::update($this->id, ['api_token' => $this->api_token]);

        return $this->api_token;
    }

    /**
     * Validate an API token.
     *
     * @param string $token
     * @return bool
     */
    public static function validateToken($token)
    {
        foreach (self::all() as $admin) {
            if (isset($admin['api_token']) && $admin['api_token'] === $token) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all admins from the XML file.
     *
     * @return array
     */
    public static function all()
    {
        return XMLHelper::readXmlFile(self::$filePath, 'admin'); // Use 'admin' as nodeName
    }

    /**
     * Find an admin by ID and return an instance of the Admin model.
     *
     * @param int|string $id
     * @return self|null
     */
    public static function find($id)
    {
        $adminData = collect(self::all())->firstWhere('id', (string)$id);

        if (!$adminData) {
            return null; // Return null if admin not found
        }

        // Create and populate an Admin instance
        $admin = new self();
        foreach ($adminData as $key => $value) {
            $admin->$key = $value;
        }

        return $admin;
    }

    /**
     * Create a new admin and save it to the XML file.
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

        // Check if file exists or create a new one
        if (!file_exists(self::$filePath)) {
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><data></data>');
            if (!$xml->asXML(self::$filePath)) {
                \Log::error('Failed to create XML file at path:', ['path' => self::$filePath]);
                return null; // Return null if file creation failed
            }
        } else {
            $xml = simplexml_load_file(self::$filePath);
        }

        // Auto-generate ID based on existing records
        if (!isset($data['id'])) {
            $existingRecords = self::all();
            $data['id'] = count($existingRecords) + 1;  // Auto-increment ID
        }

        // Add a new admin element with child nodes
        $adminElement = $xml->addChild('admin');
        foreach ($data as $key => $value) {
            $adminElement->addChild($key, htmlspecialchars($value));
        }

        // Save changes back to the file
        if ($xml->asXML(self::$filePath)) {
            \Log::info('Admin saved successfully:', ['admin' => $data]);
            return self::find($data['id']);  // Return the newly created admin as an instance
        } else {
            \Log::error('Failed to save admin:', ['admin' => $data]);
            return null;  // Return null if saving failed
        }
    }
}