<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExistDbService
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $database;

    public function __construct()
    {
        $this->baseUrl = env('EXIST_DB_HOST') . ':' . env('EXIST_DB_PORT') . '/exist/rest/db';
        $this->username = env('EXIST_DB_USERNAME');
        $this->password = env('EXIST_DB_PASSWORD');
        $this->database = env('EXIST_DB_DATABASE'); // Get the database name from .env
    }

    private function sendRequest($method, $endpoint, $data = null)
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders(['Content-Type' => 'application/xml'])
            ->send($method, $this->baseUrl . $endpoint, [
                'body' => $data,
            ]);

        return $response->ok() ? $response->body() : $response->throw();
    }

    public function createDatabase($dbName)
    {
        $endpoint = '/db/create';
        $data = "<database><name>$dbName</name></database>";

        // Sending the request to create the database
        return $this->sendRequest('POST', $endpoint, $data);
    }

    public function createDocument($collection, $filename, $xmlContent)
    {
        $url = "/$this->database/$collection/$filename";
        return $this->sendRequest('PUT', $url, $xmlContent);
    }

    public function getDocument($collection, $documentName)
    {
        $endpoint = "/$this->database/$collection/$documentName.xml"; // Include the database in the endpoint
        return $this->sendRequest('GET', $endpoint);
    }

    public function updateDocument($collection, $documentName, $xmlData)
    {
        $endpoint = "/$this->database/$collection/$documentName.xml"; // Include the database in the endpoint
        return $this->sendRequest('PUT', $endpoint, $xmlData);
    }

    public function deleteDocument($collection, $documentName)
    {
        $endpoint = "/$this->database/$collection/$documentName.xml"; // Include the database in the endpoint
        return $this->sendRequest('DELETE', $endpoint);
    }

    public function queryDocuments($xquery)
    {
        $endpoint = '/_query'; // Endpoint for querying documents
        $data = "<query xmlns=\"http://exist.sourceforge.net/NS/exist\" start=\"1\" max=\"10\">$xquery</query>";
        return $this->sendRequest('POST', $endpoint, $data);
    }
}
