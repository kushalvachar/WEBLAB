
<?php
header("Content-Type: application/json");

// Keep your API key on the server, never in JavaScript.
$apiKey = getenv("ONLINECOMPILER_API_KEY");

if (!$apiKey) {
    http_response_code(500);
    echo json_encode(["error" => "API key not configured on server"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!is_array($data) ||
    empty($data["compiler"]) ||
    !isset($data["code"]) ||
    !is_string($data["code"])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid request data"]);
    exit;
}

$payload = json_encode([
    "compiler" => $data["compiler"],
    "code" => $data["code"],
    "input" => $data["input"] ?? ""
]);

$ch = curl_init(
    "https://api.onlinecompiler.io/api/run-code-sync/"
);

curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: " . $apiKey
    ],
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_TIMEOUT => 45
]);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);

    http_response_code(502);
    echo json_encode(["error" => $error]);
    exit;
}

curl_close($ch);

http_response_code($status ?: 502);
echo $response;
