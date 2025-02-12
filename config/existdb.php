<?php

return [
    'host' => env('EXIST_DB_HOST', 'http://localhost:8080/exist'), // Host URL for eXist-db
    'username' => env('EXIST_DB_USERNAME', 'admin'), // Default username for eXist-db
    'password' => env('EXIST_DB_PASSWORD', ''), // Default password for eXist-db
    'database' => env('EXIST_DB_DATABASE', 'backend'), // Database name
    'collection' => env('EXIST_DB_COLLECTION', 'your_collection'), // Default collection to store documents
];
