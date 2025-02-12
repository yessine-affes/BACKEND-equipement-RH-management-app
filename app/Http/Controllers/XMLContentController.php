namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XMLContentController extends Controller
{
    /**
     * Get the content of the XML file and return it in the format <element>value</element>.
     *
     * @param string $entity The name of the XML file (e.g., 'admin', 'employee').
     * @return \Illuminate\Http\Response
     */
    public function getXMLContent($entity)
    {
        try {
            // Full file path for the XML file
            $filePath = storage_path("storage/app/{$entity}.xml");

            // Check if the file exists
            if (!file_exists($filePath)) {
                // Include the full file path in the error response
                $errorMessage = "<error>Le fichier XML pour '{$entity}' est introuvable. Chemin: {$filePath}</error>";
                return response()->make($errorMessage, 404, [
                    'Content-Type' => 'application/xml'
                ]);
            }

            // Load and parse the XML content
            $xmlContent = file_get_contents($filePath);
            $xmlObject = simplexml_load_string($xmlContent);

            // Convert content to custom XML format
            $customXml = $this->convertToCustomFormat($xmlObject);

            // Return the transformed content as XML
            return response($customXml, 200, [
                'Content-Type' => 'application/xml'
            ]);

        } catch (\Exception $e) {
            $errorMessage = "<error>Erreur lors de la lecture du fichier XML: " . htmlspecialchars($e->getMessage()) . "</error>";
            return response()->make($errorMessage, 500, [
                'Content-Type' => 'application/xml'
            ]);
        }
    }

    /**
     * Convert XML object to <element>value</element> format recursively.
     *
     * @param \SimpleXMLElement $xmlObject
     * @return string
     */
    private function convertToCustomFormat($xmlObject)
    {
        $result = "";

        foreach ($xmlObject as $key => $value) {
            // If the element has children, recursively process
            if ($value->children()) {
                $childContent = $this->convertToCustomFormat($value);
                $result .= "<{$key}>{$childContent}</{$key}>";
            } else {
                // Escape values to avoid XML errors
                $escapedValue = htmlspecialchars((string) $value);
                $result .= "<{$key}>{$escapedValue}</{$key}>";
            }
        }

        return $result;
    }
}
