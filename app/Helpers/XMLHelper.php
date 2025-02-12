<?php

namespace App\Helpers;

use SimpleXMLElement;

class XMLHelper
{
    /**
     * Read data from an XML file and return it as an array.
     *
     * @param string $filePath The path to the XML file.
     * @param string $nodeName The name of the child nodes (e.g., 'admin', 'employee').
     * @return array The data from the XML file as an array.
     */
    public static function addRecord($filePath, array $record, $rootElement = 'data', $nodeName = 'item')
    {
        if (!file_exists($filePath)) {
            self::writeToXmlFile($filePath, [$record], $rootElement, $nodeName);
        } else {
            // Load existing XML data
            $xml = simplexml_load_file($filePath);

            // Add a new record as a child node
            $item = $xml->addChild($nodeName);
            foreach ($record as $key => $value) {
                $item->addChild($key, htmlspecialchars($value));
            }

            // Save changes back to the file
            $xml->asXML($filePath);
        }
    }
    public static function updateRecordById($filePath, int|string $id, array $updatedData, string $nodeName)
    {
        if (!file_exists($filePath)) {
            return false;
        }
    
        // Load existing data
        $xml = simplexml_load_file($filePath);
    
        // Find and update the record by ID
        foreach ($xml->$nodeName as $item) {
            if ((string)$item->id === (string)$id) {  // Match IDs as strings
                foreach ($updatedData as $key => $value) {
                    if (isset($item->$key)) {
                        $item->$key = htmlspecialchars($value);
                    }
                }
                break;
            }
        }
    
        // Save changes back to the file
        return (bool)$xml->asXML($filePath);
    }
    public static function deleteRecordById($filePath, int|string $id, string $nodeName)
    {
        if (!file_exists($filePath)) {
            return false;
        }
    
        // Load existing data
        $xml = simplexml_load_file($filePath);
    
        // Find and remove the record by ID
        for ($i = 0; $i < count($xml->$nodeName); ++$i) {
            if ((string)$xml->$nodeName[$i]->id === (string)$id) {  // Match IDs as strings
                unset($xml->$nodeName[$i]);
                break;
            }
        }
    
        // Save changes back to the file
        return (bool)$xml->asXML($filePath);
    }
    public static function readXmlFile($filePath, $nodeName)
    {
        if (!file_exists($filePath)) {
            return [];
        }

        try {
            $xmlContent = file_get_contents($filePath);
            $xmlObject = simplexml_load_string($xmlContent);

            if ($xmlObject === false) {
                return [];
            }

            // Convert SimpleXMLElement object to JSON then decode into an array
            $json = json_encode($xmlObject);
            $array = json_decode($json, true);

            // Normalize single elements into arrays for consistency
            if (isset($array[$nodeName])) {
                $nodeData = (array)$array[$nodeName]; // Assign typecast result to a variable
                if (!is_array(reset($nodeData))) {
                    $array[$nodeName] = [$array[$nodeName]];
                }
            }

            return $array[$nodeName] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }
}