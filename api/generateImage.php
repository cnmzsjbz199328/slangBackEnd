<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class AiApiService {

    /**
     * Sends a text prompt to an image generation service and returns the generated image as a byte array.
     *
     * @param string $prompt The text prompt for image generation.
     * @return string|null The generated image as a binary string, or null if the request fails.
     */
    public function generateImage($prompt) {
        $url = "https://weathered-heart-53ed.tj15982183241.workers.dev/";
        $data = [
            "prompt" => "Simple drawing of " . $prompt
        ];
        return $this->executeHttpPostForBytes($url, $data);
    }

    /**
     * Executes an HTTP POST request with a JSON body and returns the response as a binary string.
     *
     * @param string $url The URL to send the POST request to.
     * @param array $data The data to send as a JSON object.
     * @return string|null The response body as a binary string, or null if the request fails.
     */
    private function executeHttpPostForBytes($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log('Curl error: ' . curl_error($ch));
            return null;
        }
        curl_close($ch);
        return $response;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $prompt = $input['prompt'] ?? '';

    $aiService = new AiApiService();
    $imageData = $aiService->generateImage($prompt);

    if ($imageData) {
        header('Content-Type: image/png');
        echo base64_encode($imageData);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to generate image']);
    }
}
?>