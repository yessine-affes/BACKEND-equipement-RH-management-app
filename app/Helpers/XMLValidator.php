<?php

namespace App\Helpers;

class XMLValidator
{
    public static function validateXML($xmlContent, $xsdPath)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($xmlContent);

        libxml_use_internal_errors(true);
        if (!$dom->schemaValidate($xsdPath)) {
            $errors = libxml_get_errors();
            libxml_clear_errors();

            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = trim($error->message);
            }

            throw new \Exception("XML validation failed: " . implode(", ", $errorMessages));
        }

        return true;
    }
}
