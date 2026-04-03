<?php
header("Content-Type: application/json");
require_once "api_key.php"; // Contains $GEMINI_API_KEY

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data['message'] ?? '';

if (!$userMessage) {
    echo json_encode(["error" => "No message provided"]);
    exit;
}

if (!isset($GEMINI_API_KEY) || empty($GEMINI_API_KEY)) {
    echo json_encode(["error" => "API Key missing"]);
    exit;
}

// Prepare the prompt for Mars AI Persona
$systemPrompt = "You are Mars AI, a futuristic, high-performance fitness architect. 
Your tone is robotic, precise, encouraging, and scientific (Cyberpunk style).
Keep answers concise (under 100 words) but data-rich.
Use bolding for keywords.
If the user asks for a workout or diet, suggest structured protocols.
Always end with a short motivating directive like 'EXECUTE.' or 'INITIATE.'";

$fullPrompt = $systemPrompt . "\nUser Query: " . $userMessage;

// API Request to Gemini Pro
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $GEMINI_API_KEY;

$requestBody = [
    "contents" => [
        [
            "parts" => [
                ["text" => $fullPrompt]
            ]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["error" => curl_error($ch)]);
} else {
    $decoded = json_decode($response, true);
    $aiText = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? "⚠️ **CONNECTION INTERRUPTED.** Unable to process neural link. Try again.";
    echo json_encode(["reply" => $aiText]);
}

curl_close($ch);
?>
